<?php
	define('const_issue_custom_fields', 15);
	
	$max_upload_size=multichain_max_data_size()-512; // take off space for file name and mime type

	$success=false; // set default value

	if (@$_POST['issueasset']) {
		$multiple=(int)round(1/$_POST['units']);
		
		$addresses=array( // array of addresses to issue units to
			$_POST['to'] => array(
				'issue' => array(
					'raw' => (int)($_POST['qty']*$multiple)
				)
			)
		);
		
		$custom=array();
		
		for ($index=0; $index<const_issue_custom_fields; $index++)
			if (strlen(@$_POST['key'.$index]))
				$custom[$_POST['key'.$index]]=$_POST['value'.$index];
		$name = $_POST['type'].'_'.$_POST['value0'].'_'.$_POST['value2'].'_'.$_POST['value8'];
		$datas=array( // to create array of data items
			array( // metadata for issuance details
				'name' => $name,
				'multiple' => $multiple,
				'open' => true,
				'details' => $custom,
			)
		);
		
		$upload=@$_FILES['upload'];
		$upload_file=@$upload['tmp_name'];

		if (strlen($upload_file)) {
			$upload_size=filesize($upload_file);

			if ($upload_size>$max_upload_size) {
				echo '<div class="bg-danger" style="padding:1em;">Uploaded file is too large ('.number_format($upload_size).' > '.number_format($max_upload_size).' bytes).</div>';
				return;

			} else {
				$datas[0]['details']['@file']=fileref_to_string(2, $upload['name'], $upload['type'], $upload_size); // will be in output 2
				$datas[1]=bin2hex(file_to_txout_bin($upload['name'], $upload['type'], file_get_contents($upload_file)));
			}
		}
		
		if (!count($datas[0]['details'])) // to ensure it's converted to empty JSON object rather than empty JSON array
			$datas[0]['details']=new stdClass();
		
		$success=no_displayed_error_result($issuetxid, multichain('createrawsendfrom', $_POST['from'], $addresses, $datas, 'send'));
				
		if ($success)
			output_success_text('Asset successfully issued in transaction '.$issuetxid);

					// post to create file pdf
		$data = array(
			'txid' => $issuetxid,
			'truong' => $_POST['value0'],
			'loaibang' => $_POST['type'],
			'nganhdaotao' => $_POST['value5'],
			'hoten' => $_POST['value1'],
			'mssv' => $_POST['value2'],
			'ngaysinh' => $_POST['value5'],
			'gioitinh' => $_POST['value3'],
			'xeploai' => $_POST['value6'],
			'hinhthucdaotao' => $_POST['value7'],
			'namtotnghiep' => $_POST['value8'],
			'filename' => $name
		);
		# Create a connection
		require('tfpdf.php');
		$pdf = new tFPDF();
		$pdf->AddPage('L');

		// Add a Unicode font (uses UTF-8)
		$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

		$pdf->SetFont('DejaVu','',0.1);
		$pdf->Cell(0,0,$issuetxid.";",1,0,"C");


		$pdf->SetFont('DejaVu','',36);
		$pdf->Ln(40);
		$truong = '';
		if($_POST['value0'] == "UIT")
			$truong = "TRƯỜNG ĐẠI HỌC CÔNG NGHỆ THÔNG TIN";
		$pdf->Cell(0,0,$truong,0,0,"C");

		$pdf->SetFont('DejaVu','',18);
		$pdf->Ln(15);
		$pdf->Cell(0,0,"Cấp",0,0,"C");

		$pdf->SetFont('DejaVu','',60);
		$pdf->Ln(10);
		$loaibang='';
		if($_POST['type'] == "BangTotNghiep")
			$loaibang="BẰNG TỐT NGHIỆP";
		else if($_POST['type'] == "BangCuNhan")
			$loaibang="BẰNG CỬ NHÂN";
		else if($_POST['type'] == "BangKySu")
			$loaibang="BẰNG KỸ SƯ";
		else if($_POST['type'] == "ChungChi")
			$loaibang="CHỨNG CHỈ";
		$pdf->Cell(0,30,$loaibang,0,0,"C");
		
		$pdf->SetFont('DejaVu','',20);
		$pdf->Ln(15);
		$pdf->Cell(0,30,$_POST['value8'],0,0,"C");

		$pdf->SetFont('DejaVu','',16);
		$pdf->Ln(40);
		$pdf->Cell(0,0,"         Cho: ".$_POST['value1'].".           MSSV: ".$_POST['value2'],0,0,"L");
		$pdf->Ln(10);
		$pdf->Cell(0,0,"         Giới tính: ".$_POST['value3'],0,0,"L");
		$pdf->Ln(10);
		$pdf->Cell(0,0,"         Sinh ngày: ".$_POST['value5'],0,0,"L");
		$pdf->Ln(10);
		$pdf->Cell(0,0,"         Xếp Loại: ".$_POST['value6'],0,0,"L");
		$pdf->Ln(10);
		$pdf->Cell(0,0,"         Hình thức đào tạo: ".$_POST['value7'],0,0,"L");

		// $pdf->Output();
		$pdf->Output('file/'.$name.'.pdf');
	}

	$getinfo=multichain_getinfo();

	$issueaddresses=array();
	$keymyaddresses=array();
	$receiveaddresses=array();
	$labels=array();

	if (no_displayed_error_result($getaddresses, multichain('getaddresses', true))) {

		if (no_displayed_error_result($listpermissions,
			multichain('listpermissions', 'issue', implode(',', array_get_column($getaddresses, 'address')))
		))
			$issueaddresses=array_get_column($listpermissions, 'address');
		
		foreach ($getaddresses as $address)
			if ($address['ismine'])
				$keymyaddresses[$address['address']]=true;
				
		if (no_displayed_error_result($listpermissions, multichain('listpermissions', 'receive')))
			$receiveaddresses=array_get_column($listpermissions, 'address');

		$labels=multichain_labels();
	}
?>

			<div class="row">
<?php
//check admin
if(count($issueaddresses) > 0){
?>
				<div class="col-sm-6">
<?php
}else{
?>
				<div class="col-sm-10">
<?php
}
?>
					<h3>Danh sách chứng chỉ</h3>
			
<?php
	if (no_displayed_error_result($listassets, multichain('listassets', '*', true))) {

		foreach ($listassets as $asset) {
			$name=$asset['name'];
			$issuer=$asset['issues'][0]['issuers'][0];
?>
						<table class="table table-bordered table-condensed table-break-words <?php echo ($success && ($name==@$_POST['name'])) ? 'bg-success' : 'table-striped'?>">
							<tr>
								<th style="width:30%;">Name</th>
								<td><?php echo html($name)?> <?php echo $asset['open'] ? '' : '(closed)'?></td>
							</tr>
							<!-- <tr>
								<th>Quantity</th>
								<td>
								<?php 
								// echo html($asset['issueqty'])?>
								</td>
							</tr>
							<tr>
								<th>Units</th>
								<td><?php 
								// echo html($asset['units'])?></td>
							</tr> -->
							<tr>
								<th>Địa chỉ trường phát hành</th>
								<td class="td-break-words small"><?php echo format_address_html($issuer, @$keymyaddresses[$issuer], $labels)?></td>
							</tr>
<?php
			$details=array();
			$detailshistory=array();
			
			foreach ($asset['issues'] as $issue)
				foreach ($issue['details'] as $key => $value) {
					$detailshistory[$key][$issue['txid']]=$value;
					$details[$key]=$value;
				}
			
			if (count(@$detailshistory['@file'])) {
?>
							<tr>
								<th>File:</th>
								<td><?php
								
				$countoutput=0;
				$countprevious=count($detailshistory['@file'])-1;

				foreach ($detailshistory['@file'] as $txid => $string) {
					$fileref=string_to_fileref($string);
					if (is_array($fileref)) {
					
						$file_name=$fileref['filename'];
						$file_size=$fileref['filesize'];
						
						if ($countoutput==1) // first previous version
							echo '<br/><small>('.$countprevious.' previous '.(($countprevious>1) ? 'files' : 'file').': ';
						
						echo '<a href="./download-file.php?chain='.html($_GET['chain']).'&txid='.html($txid).'&vout='.html($fileref['vout']).'">'.
							(strlen($file_name) ? html($file_name) : 'Download').
							'</a>'.
							( ($file_size && !$countoutput) ? html(' ('.number_format(ceil($file_size/1024)).' KB)') : '');
						
						$countoutput++;
					}
				}
				
				if ($countoutput>1)
					echo ')</small>';
								
								?></td>
							</tr>	
<?php
			}
			
			foreach ($details as $key => $value) {
				if ($key=='@file')
					continue;
?>
							<tr>
								<th><?php echo html($key)?></th>
								<td><?php echo html($value)?><?php
								
				if (count($detailshistory[$key])>1)
					echo '<br/><small>(previous values: '.html(implode(', ', array_slice(array_reverse($detailshistory[$key]), 1))).')</small>';
				
								?></td>
							</tr>
<?php
			}
?>							
						</table>
<?php
		}
	}
?>
				</div>
				</div>
			</div>
