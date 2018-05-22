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
		$name = $_POST['type'].'_'.'UIT'.'_'.$_POST['value2'].'_'.$_POST['value8'];
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
				
<?php
}else{
?>
	
<?php
}
?>

<?php
//check admin
if(count($issueaddresses) > 0){
?>
				<div>
					<h3>Phát hành văn bằng/chứng chỉ</h3>
					
					<form class="form-horizontal" method="post" enctype="multipart/form-data" action="./?chain=<?php echo html($_GET['chain'])?>&page=list">
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
						<div class="form-group" hidden>
							<label for="qty" class="col-sm-2 control-label">Quantity:</label>
							<div class="col-sm-9">
								<input class="form-control" name="qty" id="qty" placeholder="1000.0" value="1">
								<span id="helpBlock" class="help-block">In this demo, the asset will be open, allowing further issues in future.</span>
							</div>
						</div>
						<div class="form-group" hidden>
							<label for="units" class="col-sm-2 control-label">Units:</label>
							<div class="col-sm-9">
								<input class="form-control" name="units" id="units" placeholder="0.01" value="1">
							</div>
						</div>
						
						<!-- <div class="form-group">
							<label for="upload" class="col-sm-2 control-label">Upload file:<br/><span style="font-size:75%; font-weight:normal;">Max <?php echo floor($max_upload_size/1024)?> KB</span></label>
							<div class="col-sm-9">
								<input class="form-control" type="file" name="upload" id="upload">
							</div>
						</div> -->

						<div class="form-group">
							<label for="key1" class="col-sm-2 control-label">Trường phát hành:</label>
							<div class="col-sm-3" hidden>
								<input class="form-control input-sm" name="key0" id="key0" value="school">
							</div>
							<div class="col-sm-6">
								<select name="value0" id="value0">
									<option value="UIT">Trường Đại Học Công Nghệ Thông Tin</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="key1" class="col-sm-2 control-label">Họ tên sinh viên:</label>
							<div class="col-sm-3" hidden>
								<input class="form-control input-sm" name="key1" id="key1" value="name">
							</div>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="value1" id="value1">
							</div>
						</div>

						<div class="form-group">
							<label for="key1" class="col-sm-2 control-label">MSSV:</label>
							<div class="col-sm-3" hidden>
								<input class="form-control input-sm" name="key2" id="key2" value="idstudent">
							</div>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="value2" id="value2">
							</div>
						</div>

						<div class="form-group">
							<label for="key1" class="col-sm-2 control-label">Giới tính:</label>
							<div class="col-sm-3" hidden>
								<input class="form-control input-sm" name="key3" id="key3" value="sex">
							</div>
							<div class="col-sm-6">
								<select name="value3" id="value3">
								<option value="Nam">Nam</option>
								<option value="Nu">Nữ</option>
							</select>
							</div>
						</div>

						<div class="form-group">
							<label for="key1" class="col-sm-2 control-label">Ngày sinh:</label>
							<div class="col-sm-3" hidden>
								<input class="form-control input-sm" name="key4" id="key4" value="dateofbirth">
							</div>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="value4" id="value4" type="date">
							</div>
						</div>

						<div class="form-group">
							<label for="key1" class="col-sm-2 control-label">Ngành đào tạo:</label>
							<div class="col-sm-3" hidden>
								<input class="form-control input-sm" name="key5" id="key5" value="major in">
							</div>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="value5" id="value5">
							</div>
						</div>

						<div class="form-group">
							<label for="key1" class="col-sm-2 control-label">Xếp loại:</label>
							<div class="col-sm-3" hidden>
								<input class="form-control input-sm" name="key6" id="key6" value="ranking">
							</div>
							<div class="col-sm-6">
								<select name="value6" id="value6">
									<option value="Xuất sắc">Xuất sắc</option>
									<option value="Giỏi">Giỏi</option>
									<option value="Khá">Khá</option>
									<option value="Trung Bình">Trung Bình</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="key1" class="col-sm-2 control-label">Hình thức đào tạo:</label>
							<div class="col-sm-3" hidden>
								<input class="form-control input-sm" name="key7" id="key7" value="mode of study">
							</div>
							<div class="col-sm-6">
								<select name="value7" id="value7">
									<option value="Chính Quy">Chính Quy</option>
									<option value="Chất lượng cao">Chất lượng cao</option>
									<option value="Chương trình tiên tiến">Chương trình tiên tiến</option>
									<option value="Đào tạo từ xa">Đào tạo từ xa</option>
								</select>
							</div>
						</div>

						<div class="form-group" hidden>
							<label for="key1" class="col-sm-2 control-label">Năm tốt nghiệp:</label>
							<div class="col-sm-3" hidden>
								<input class="form-control input-sm" name="key8" id="key8" value="yearissue">
							</div>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="value8" id="value8" value="<?php echo date("Y"); ?>">
							</div>
						</div>
						




						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-9">
								<!-- <input class="form-control" name="name" id="name" placeholder="asset1" type="hidden" value=""> -->
								<input class="btn btn-default" type="submit" name="issueasset" value="Phát hành">
							</div>
						</div>
					</form>

				</div>
			</div>
<?php
}
?>