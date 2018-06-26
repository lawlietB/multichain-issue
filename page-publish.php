<?php
use setasign\Fpdi;
function stripUnicode($str)
{
	if(!$str) return false;
	 $unicode = array(
		'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
		'd'=>'đ',
		'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
		'i'=>'í|ì|ỉ|ĩ|ị',
		'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
		'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
		'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
		'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
		'D'=>'Đ',
		'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
		'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
		'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
		'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
		'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
	 );
  	foreach($unicode as $nonUnicode=>$uni) $str = preg_replace("/($uni)/i",$nonUnicode,$str);
  	return $str;
}
function create_certificate($student_name, $id, $dob, $listenmark, $readmark, $testdate, $key, $hash)
{
	$student_name = stripUnicode($student_name);

	require('fpdf.php');
    require('FPDI/src/autoload.php');
    $pdf = new Fpdi\Fpdi();
    $pdf->AddPage('L'); //Theo chieu ngang

    $pdf->setSourceFile("BangTOIEC.pdf");
    $tplId = $pdf->importPage(1);
    $pdf->useTemplate($tplId, 0, 2, 298);
    
    $pdf->SetFont('Helvetica','',0.1);
	$pdf->Cell(0,0,$key.":".$hash.";",0,0,"C");

    $pdf->SetFont('Helvetica','',14);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(90, 45);
    $pdf->Write(0, $student_name);
    

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(70, 67);
    $pdf->Write(0, $id);

    $pdf->SetXY(130, 67);
    $pdf->Write(0, $dob);

    $pdf->SetXY(70, 93);
    $pdf->Write(0, $testdate);

    $pdf->SetXY(130, 93);
    $pdf->Write(0, $testdate);

    $pdf->SetFont('Helvetica','', 18);    
    //Diem Listening min: 164.5, max: 228.5
    $pdf->Image('DiemTOIEC.png', 164.5 + ($listenmark - 5) * 0.1306 , 41, '', '', '', '');
    $pdf->SetXY(166 + ($listenmark - 5) * 0.1306, 50);
    $pdf->Write(0, $listenmark);

    //Diem Reading min: 164, max:228
    $diem_doc = 350;
    $pdf->Image('DiemTOIEC.png', 164.5 + ($readmark - 5) * 0.1306, 82, '', '', '', '');
    $pdf->SetXY(166 + ($readmark - 5) * 0.1306, 91);
    $pdf->Write(0, $readmark);

    $pdf->SetFont('Helvetica','', 22);    
    $pdf->SetXY(264, 84);
    $pdf->Write(0, $listenmark + $listenmark);

	$pdf->Output('file/'.$key.'.pdf','F');
}

function create_graduation_certificate($student_name, $dob, $ranking, $mos, $key, $hash)
{
	//Process data
	$student_name = stripUnicode($student_name);
	$ranking = stripUnicode($ranking);
	$mos = stripUnicode($mos);
	require('fpdf.php');
	require('FPDI/src/autoload.php');
	
	$pdf = new Fpdi\Fpdi();
	$pdf->AddPage('L'); //Theo chieu ngang
	
	$pdf->setSourceFile("bangtotnghiepmau.pdf");
	$tplId = $pdf->importPage(1);
	$pdf->useTemplate($tplId, 0, 2, 298);
	
	// store hash in PDF
	$pdf->SetFont('Helvetica','',0.1);
	$pdf->Cell(0,0,$key.":".$hash.";",0,0,"C");

	//Thong tin Bang tot nghiep
	$pdf->SetFont('Helvetica','',12);
	//Tieng Viet
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(197, 102);
	$pdf->Write(0, $student_name);
	
	$pdf->SetXY(197, 109.7);
	$pdf->Write(0, $dob);
	
	$pdf->SetXY(213 , 116);
	$pdf->Write(0, $ranking);
	
	$pdf->SetXY(213 , 123);
	$pdf->Write(0, $mos);
	
	// Tieng Anh
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(57, 108);
	$pdf->Write(0, $student_name);

	$pdf->SetXY(65, 115);
	$pdf->Write(0, $dob);

	$pdf->SetXY(76 , 122);
	$pdf->Write(0, 'Good');

	$pdf->SetXY(70 , 130);
	$pdf->Write(0, 'Full-time');

	$pdf->Output('file/'.$key.'.pdf','F');
}


	$max_upload_size=multichain_max_data_size()-512; // take off space for file name and mime type

	if (@$_POST['publish']) {
		$key = $_POST['type'].$_POST['school'].$_POST['idstudent'].$_POST['yearissue'];
		no_displayed_error_result($createtxid, multichain('createfrom',
			$_POST['from'], 'stream', $key, true));

		$data_json =  new \stdClass();
		if($_POST['type'] == "BangCuNhan" || $_POST['type'] == "BangKySu")
		{
			if($_POST['type'] == "BangCuNhan")
				$data_json->type="Bằng Cử Nhân";
			if($_POST['type'] == "BangKySu")			
				$data_json->type="Bằng Kỹ Sư";

			$data_json->school      = $_POST['school'];
			$data_json->studentname = $_POST['studentname'];
			$data_json->idstudent   = $_POST['idstudent'];
			$data_json->sex         = $_POST['sex'];
			$data_json->dateofbirth = $_POST['dateofbirth'];
			$data_json->majorin     = $_POST['majorin'];
			$data_json->ranking     = $_POST['ranking'];
			$data_json->modeofstudy = $_POST['modeofstudy'];
			$data_json->yearissue   = $_POST['yearissue'];
		
			$data_json = json_encode($data_json);
		
			$data = json_decode($data_json);
			$store_data = "Loại bằng:".$data->type
						.";Trường cấp:".$data->school
						.";Tên sinh viên/học viên:".$data->studentname
						.";Mã số:".$data->idstudent
						.";Giới tính:".$data->sex
						.";Ngày sinh:".$data->dateofbirth
						.";Chuyên ngành:".$data->majorin
						.";Xếp loại:".$data->ranking
						.";Hình thức đào tạo:".$data->modeofstudy
						.";Năm cấp:".$data->yearissue;
		}
		else if($_POST['type'] == "ChungChi")
		{
			$data_json->type="Chứng Chỉ";
			$data_json->school      = $_POST['school'];
			$data_json->studentname = $_POST['studentname'];
			$data_json->idstudent   = $_POST['idstudent'];
			$data_json->sex         = $_POST['sex'];
			$data_json->dateofbirth = $_POST['dateofbirth'];
			$data_json->testdate    = $_POST['testdate'];			
			$data_json->readmark    = $_POST['readmark'];
			$data_json->listenmark  = $_POST['listenmark'];
			$data_json = json_encode($data_json);
			
			$data = json_decode($data_json);
			$store_data = "Loại bằng:".$data->type
						.";Trường cấp:".$data->school
						.";Tên sinh viên/học viên:".$data->studentname
						.";Mã số:".$data->idstudent
						.";Giới tính:".$data->sex
						.";Ngày sinh:".$data->dateofbirth
						.";Điểm nghe:".$data->listenmark
						.";Điểm đọc:".$data->readmark
						.";Ngày thi:".$data->testdate;
		}
					
		$store_data = bin2hex($store_data);
		$hash = hash("sha256",$store_data);
		
		if($_POST['type'] == "BangCuNhan" || $_POST['type'] == "BangKySu")
			create_graduation_certificate($_POST['studentname'], $_POST['dateofbirth'], $_POST['ranking'], $_POST['modeofstudy'], $key, $hash);
		else if($_POST['type'] == "ChungChi")
			create_certificate($_POST['studentname'], $_POST['idstudent'], $_POST['dateofbirth'], $_POST['listenmark'], $_POST['readmark'], $_POST['testdate'], $key, $hash);
		

		if (no_displayed_error_result($publishtxid, multichain(
			'publishfrom', $_POST['from'],$key, $key, $store_data
		)))
			output_success_text('Lưu thành công với mã transaction là: '.$publishtxid.' với dữ liệu là: '.$hash);
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
								<!-- <option value="BangTotNghiep">Bằng tốt nghiệp</option> -->
								<option value="BangCuNhan">Bằng cử nhân</option>
								<option value="BangKySu">Bằng kỹ sư</option>
								<option value="ChungChi">Chứng chỉ</option>
							</select>	
							</div>	
						</div>						

						<div class="form-group">
							<label for="school" class="col-sm-2 control-label">Tổ chức phát hành:</label>
							<div class="col-sm-6">
								<select name="school" id="school">
									<option value="UIT">Trường Đại Học Công Nghệ Thông Tin</option>
									<option value="IIG">Tổ chức IIG</option>
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
							<label for="studentname" class="col-sm-2 control-label">Mã số:</label>
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

						<div class="form-group" id="_majorin">
							<label for="studentname" class="col-sm-2 control-label">Ngành đào tạo:</label>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="majorin" id="majorin">
							</div>
						</div>

						<div class="form-group" id="_ranking">
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

						<div class="form-group" id="_modeofstudy">
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

						<div class="form-group" id="_listenmark">
							<label for="studentname" class="col-sm-2 control-label">Điểm nghe:</label>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="listenmark" id="listenmark" min="5" max="495" type="number" step="5">
							</div>
						</div>

						<div class="form-group" id="_readmark">
							<label for="studentname" class="col-sm-2 control-label">Điểm đọc:</label>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="readmark" id="readmark" min="5" max="495" type="number" step="5">
							</div>
						</div>

						<div class="form-group" id="_testdate">
							<label for="studentname" class="col-sm-2 control-label">Ngày thi:</label>
							<div class="col-sm-6">
								<input class="form-control input-sm" name="testdate" id="testdate" type="date">
							</div>
						</div>


						<div class="form-group" id="_yearissue" hidden>
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
			<script>
				// $loaichungchi = $('#type').find(':selected').attr('value');
				$("#_listenmark *").attr("disabled", "disabled").attr("hidden",true);	
				$("#_readmark *").attr("disabled", "disabled").attr("hidden",true);	
				$("#_testdate *").attr("disabled", "disabled").attr("hidden",true);	
				$('#type').change(function(){
					$loaichungchi = $(this).find(':selected').attr('value');
					if($loaichungchi == "BangCuNhan" || $loaichungchi == "BangKySu")
					{
						$("#_listenmark *").attr("disabled", "disabled").attr("hidden",true);	
						$("#_readmark *").attr("disabled", "disabled").attr("hidden",true);	
						$("#_testdate *").attr("disabled", "disabled").attr("hidden",true);
						$("#_modeofstudy *").attr("disabled", false).attr("hidden",false);	
						$("#_majorin *").attr("disabled", false).attr("hidden",false);	
						$("#_ranking *").attr("disabled", false).attr("hidden",false);	
					}
					else if($loaichungchi == "ChungChi"){
						$("#_listenmark *").attr("disabled", false).attr("hidden",false);	
						$("#_readmark *").attr("disabled", false).attr("hidden",false);
						$("#_testdate *").attr("disabled", false).attr("hidden",false);
						$("#_modeofstudy *").attr("disabled", "disabled").attr("hidden",true);	
						$("#_majorin *").attr("disabled", "disabled").attr("hidden",true);
						$("#_ranking *").attr("disabled", "disabled").attr("hidden",true);
					}
				});	

			</script>
