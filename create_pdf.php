<?php

// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");

use setasign\Fpdi;

require('fpdf.php');
require('FPDI/src/autoload.php');

$pdf = new Fpdi\Fpdi();

$pdf->setSourceFile('bangtotnghiepmau.pdf');
$tplId = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplId, 10, 10, 100);

$pdf->Output();

// require('tfpdf.php');

// $pdf = new tFPDF();
// $pdf->AddPage('L');

// // Add a Unicode font (uses UTF-8)
// $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

// $pdf->setSourceFile('bangtotnghiepmau.pdf');
// $tplIdx = $pdf->importPage(1); 
// $pdf->useTemplate($tplIdx, 0, 0);

// $pdf->SetFont('DejaVu','',36);
// $pdf->SetTextColor(255,0,0); 
// $pdf->SetXY(25, 25); 
// $pdf->Write(0, "This is just a simple text");
// // $pdf->Output('file/'.$_POST['filename'].'pdf');
// $pdf->Output();

// Create a connection
		// require('fpdf.php');
		// $pdf = new FPDF();
		// $pdf->AddPage('L');

		// // Add a Unicode font (uses UTF-8)
		// $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
		// // store hash in PDF
		// $pdf->SetFont('DejaVu','',0.1);
		// $pdf->Cell(0,0,$key.":".$hash.";",1,0,"C");

		// //Start design pdf
		// $pdf->SetFont('DejaVu','',36);
		// $pdf->Ln(40);
		// $truong = '';
		// if($_POST['school'] == "UIT")
		// 	$truong = "TRƯỜNG ĐẠI HỌC CÔNG NGHỆ THÔNG TIN";
		// $pdf->Cell(0,0,$truong,0,0,"C");

		// $pdf->SetFont('DejaVu','',18);
		// $pdf->Ln(15);
		// $pdf->Cell(0,0,"Cấp",0,0,"C");

		// $pdf->SetFont('DejaVu','',60);
		// $pdf->Ln(10);
		// $loaibang='';
		// if($_POST['type'] == "BangTotNghiep")
		// 	$loaibang="BẰNG TỐT NGHIỆP";
		// else if($_POST['type'] == "BangCuNhan")
		// 	$loaibang="BẰNG CỬ NHÂN";
		// else if($_POST['type'] == "BangKySu")
		// 	$loaibang="BẰNG KỸ SƯ";
		// else if($_POST['type'] == "ChungChi")
		// 	$loaibang="CHỨNG CHỈ";
		// $pdf->Cell(0,30,$loaibang,0,0,"C");
		
		// $pdf->SetFont('DejaVu','',20);
		// $pdf->Ln(15);
		// $pdf->Cell(0,30,$_POST['yearissue'],0,0,"C");

		// $pdf->SetFont('DejaVu','',16);
		// $pdf->Ln(40);
		// $pdf->Cell(0,0,"         Cho: ".$_POST['studentname'].".           MSSV: ".$_POST['idstudent'],0,0,"L");
		// $pdf->Ln(10);
		// $pdf->Cell(0,0,"         Giới tính: ".$_POST['sex'],0,0,"L");
		// $pdf->Ln(10);
		// $pdf->Cell(0,0,"         Sinh ngày: ".$_POST['dateofbirth'],0,0,"L");
		// $pdf->Ln(10);
		// $pdf->Cell(0,0,"         Xếp Loại: ".$_POST['ranking'],0,0,"L");
		// $pdf->Ln(10);
		// $pdf->Cell(0,0,"         Hình thức đào tạo: ".$_POST['modeofstudy'],0,0,"L");
		// // End design pdf
		// $pdf->Output('file/'.$key.'.pdf');
		
?>
