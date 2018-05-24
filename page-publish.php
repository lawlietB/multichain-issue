<?php
	$max_upload_size=multichain_max_data_size()-512; // take off space for file name and mime type

	if (@$_POST['publish']) {

		// $upload=@$_FILES['upload'];
		// $upload_file=@$upload['tmp_name'];

		// if (strlen($upload_file)) {
		// 	$upload_size=filesize($upload_file);

		// 	if ($upload_size>$max_upload_size) {
		// 		echo '<div class="bg-danger" style="padding:1em;">Uploaded file is too large ('.number_format($upload_size).' > '.number_format($max_upload_size).' bytes).</div>';
		// 		return;

		// 	} else
		// 		$data=file_to_txout_bin($upload['name'], $upload['type'], file_get_contents($upload_file));

		// } else
		// 	$data=string_to_txout_bin($_POST['text']);


		$key = $_POST['type'].'_'.$_POST['school'].'_'.$_POST['idstudent'].'_'.$_POST['yearissue'];

		$data_json =  new \stdClass();
		$data_json->type        = $_POST['type'];
		$data_json->school      = $_POST['school'];
		$data_json->studentname = $_POST['studentname'];
		$data_json->idstudent   = $_POST['idstudent'];
		$data_json->sex         = $_POST['sex'];
		$data_json->dateofbirth = $_POST['dateofbirth'];
		$data_json->majorin     = $_POST['majorin'];
		$data_json->modeofstudy = $_POST['modeofstudy'];
		$data_json->yearissue   = $_POST['yearissue'];

		$data_json = json_encode($data_json);

		$data = json_decode($data_json);
		$store_data = "Loại bằng:".$data->type
					 ."Trường cấp:".$data->school
					 ."Tên sinh viên/học viên:".$data->studentname
					 ."Mã số:".$data->idstudent
					 ."Giới tính:".$data->sex
					 ."Ngày sinh:".$data->dateofbirth
					 ."Chuyên ngành:".$data->majorin
					 ."Hình thức đào tạo:".$data->modeofstudy
					 ."Năm cấp:".$data->yearissue;
		$store_data = bin2hex($store_data);
		
		// Create a connection
		require('tfpdf.php');
		$pdf = new tFPDF();
		$pdf->AddPage('L');

		// Add a Unicode font (uses UTF-8)
		$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

		$pdf->SetFont('DejaVu','',0.1);
		$pdf->Cell(0,0,$key.":".$store_data.";",1,0,"C");


		$pdf->SetFont('DejaVu','',36);
		$pdf->Ln(40);
		$truong = '';
		if($_POST['school'] == "UIT")
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
		$pdf->Cell(0,30,$_POST['yearissue'],0,0,"C");

		$pdf->SetFont('DejaVu','',16);
		$pdf->Ln(40);
		$pdf->Cell(0,0,"         Cho: ".$_POST['studentname'].".           MSSV: ".$_POST['idstudent'],0,0,"L");
		$pdf->Ln(10);
		$pdf->Cell(0,0,"         Giới tính: ".$_POST['sex'],0,0,"L");
		$pdf->Ln(10);
		$pdf->Cell(0,0,"         Sinh ngày: ".$_POST['dateofbirth'],0,0,"L");
		$pdf->Ln(10);
		$pdf->Cell(0,0,"         Xếp Loại: ".$_POST['ranking'],0,0,"L");
		$pdf->Ln(10);
		$pdf->Cell(0,0,"         Hình thức đào tạo: ".$_POST['modeofstudy'],0,0,"L");

		// $pdf->Output();
		$pdf->Output('file/'.$key.'.pdf');
		
		if (no_displayed_error_result($publishtxid, multichain(
			'publishfrom', $_POST['from'], $_POST['name'], $key, $store_data
		)))
			output_success_text('Item successfully published in transaction '.$publishtxid);
	}

	$labels=multichain_labels();

	no_displayed_error_result($liststreams, multichain('liststreams', '*', true));

	if (no_displayed_error_result($getaddresses, multichain('getaddresses', true))) {
		foreach ($getaddresses as $index => $address)
			if (!$address['ismine'])
				unset($getaddresses[$index]);
				
		if (no_displayed_error_result($listpermissions,
			multichain('listpermissions', 'send', implode(',', array_get_column($getaddresses, 'address')))
		))
			$sendaddresses=array_get_column($listpermissions, 'address');
	}
	
?>

			<div class="row">

				<div class="col-sm-12">
					<h3>Phát hành chứng chỉ</h3>
					
					<form class="form-horizontal" method="post" enctype="multipart/form-data"  action="./?chain=<?php echo html($_GET['chain'])?>&page=<?php echo html($_GET['page'])?>">
						<div class="form-group" hidden>
							<label for="from" class="col-sm-2 control-label">From address:</label>
							<div class="col-sm-9">
							<select class="form-control" name="from" id="from">
<?php
	foreach ($sendaddresses as $address) {
?>
								<option value="<?php echo html($address)?>"><?php echo format_address_html($address, true, $labels)?></option>
<?php
	}
?>						
							</select>
							</div>
						</div>
						<div class="form-group" hidden>
							<label for="name" class="col-sm-2 control-label">To stream:</label>
							<div class="col-sm-9">
							<select class="form-control" name="name" id="name">
<?php
	foreach ($liststreams as $stream) 
		if ($stream['name']!='root') {
?>
								<option value="<?php echo html($stream['name'])?>"><?php echo html($stream['name'])?></option>
<?php
		}
?>						
							</select>
							</div>
						</div>
						<!-- <div class="form-group">
							<label for="key" class="col-sm-2 control-label">Optional key:</label>
							<div class="col-sm-9">
								<input class="form-control" name="key" id="key">
							</div>
						</div> -->



						<!-- <div class="form-group">
							<label for="upload" class="col-sm-2 control-label">Upload file:<br/><span style="font-size:75%; font-weight:normal;">Max <?php echo floor($max_upload_size/1024)?> KB</span></label>
							<div class="col-sm-9">
								<input class="form-control" type="file" name="upload" id="upload">
							</div>
						</div> -->
						<!-- <div class="form-group">
							<div class="col-sm-9">
								<textarea class="form-control" rows="4" name="text" id="text"></textarea>
							</div>
						</div> -->

						<!-- Data -->
						<div class="form-group" hidden>
							<label for="from" class="col-sm-2 control-label">From address:</label>
							<div class="col-sm-9">
							<select class="form-control col-sm-6" name="from" id="from">
<?php
	foreach ($issueaddresses as $address) {
?>
								<option value="<?php echo html($address)?>"><?php echo format_address_html($address, true, $labels)?></option>
<?php
	}
?>						
							</select>
							</div>
						</div>

						<div class="form-group" hidden>
							<label for="to" class="col-sm-2 control-label">To address:</label>
							<div class="col-sm-9">
							<select class="form-control col-sm-6" name="to" id="to">
<?php
	foreach ($receiveaddresses as $address) {
		if ($address==$getinfo['burnaddress'])
			continue;
?>
								<option value="<?php echo html($address)?>"><?php echo format_address_html($address, @$keymyaddresses[$address], $labels)?></option>
<?php
	}
?>						
							</select>
							</div>
						</div>

						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Loại chứng chỉ: </label>
							<div class="col-sm-9">
							<select name="type" id="type">
								<option value="BangTotNghiep">Bằng tốt nghiệp</option>
								<option value="BangCuNhan">Bằng cử nhân</option>
								<option value="BangKySu">Bằng kỹ sư</option>
								<option value="ChungChi">Chứng chỉ</option>
							</select>	
							</div>	
						</div>						

						<div class="form-group">
							<label for="school" class="col-sm-2 control-label">Trường phát hành:</label>
							<div class="col-sm-6">
								<select name="school" id="school">
									<option value="UIT">Trường Đại Học Công Nghệ Thông Tin</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="studentname" class="col-sm-2 control-label">Họ tên sinh viên:</label>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="studentname" id="studentname">
							</div>
						</div>

						<div class="form-group">
							<label for="studentname" class="col-sm-2 control-label">MSSV:</label>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="idstudent" id="idstudent">
							</div>
						</div>

						<div class="form-group">
							<label for="studentname" class="col-sm-2 control-label">Giới tính:</label>
							<div class="col-sm-6">
								<select name="sex" id="sex">
								<option value="Nam">Nam</option>
								<option value="Nu">Nữ</option>
							</select>
							</div>
						</div>

						<div class="form-group">
							<label for="studentname" class="col-sm-2 control-label">Ngày sinh:</label>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="dateofbirth" id="dateofbirth" type="date">
							</div>
						</div>

						<div class="form-group">
							<label for="studentname" class="col-sm-2 control-label">Ngành đào tạo:</label>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="majorin" id="majorin">
							</div>
						</div>

						<div class="form-group">
							<label for="studentname" class="col-sm-2 control-label">Xếp loại:</label>
							<div class="col-sm-6">
								<select name="ranking" id="ranking">
									<option value="Xuất sắc">Xuất sắc</option>
									<option value="Giỏi">Giỏi</option>
									<option value="Khá">Khá</option>
									<option value="Trung Bình">Trung Bình</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="studentname" class="col-sm-2 control-label">Hình thức đào tạo:</label>
							<div class="col-sm-6">
								<select name="modeofstudy" id="modeofstudy">
									<option value="Chính Quy">Chính Quy</option>
									<option value="Chất lượng cao">Chất lượng cao</option>
									<option value="Chương trình tiên tiến">Chương trình tiên tiến</option>
									<option value="Đào tạo từ xa">Đào tạo từ xa</option>
								</select>
							</div>
						</div>

						<div class="form-group" hidden>
							<label for="studentname" class="col-sm-2 control-label">Năm tốt nghiệp:</label>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="yearissue" id="yearissue" value="<?php echo date("Y"); ?>">
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-9">
								<!-- <input class="form-control" name="name" id="name" placeholder="asset1" type="hidden" value=""> -->
								<input class="btn btn-default" type="submit" name="publish" value="Phát hành">
							</div>
						</div>
						<!-- End Data -->
					</form>

				</div>
			</div>