<?php

// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");

require('tfpdf.php');

$pdf = new tFPDF();
$pdf->AddPage('L');

// Add a Unicode font (uses UTF-8)
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

$pdf->SetFont('DejaVu','',0.1);
$pdf->Cell(0,30,$_POST['txid'].";",1,0,"C");


$pdf->SetFont('DejaVu','',36);
$pdf->Ln(40);
$truong = strtoupper ($_POST['truong']);
$pdf->Cell(0,0,$truong,0,0,"C");

$pdf->SetFont('DejaVu','',18);
$pdf->Ln(15);
$pdf->Cell(0,0,"Cấp",0,0,"C");

$pdf->SetFont('DejaVu','',60);
$pdf->Ln(10);
$loaibang = strtoupper ($_POST['loaibang']);
$pdf->Cell(0,30,$loaibang,0,0,"C");
$pdf->SetFont('DejaVu','',20);
$pdf->Ln(15);
$pdf->Cell(0,30,$_POST['namtotnghiep'],0,0,"C");

$pdf->SetFont('DejaVu','',16);
$pdf->Ln(40);
$pdf->Cell(0,0,"        Cho: ".$_POST['hoten'].". MSSV: ".$_POST['mssv'],0,0,"L");
$pdf->Ln(10);
$pdf->Cell(0,0,"        Giới tính: ".$_POST['gioitinh'],0,0,"L");
$pdf->Ln(10);
$pdf->Cell(0,0,"        Sinh ngày: ".$_POST['ngaysinh'],0,0,"L");
$pdf->Ln(10);
$pdf->Cell(0,0,"        Xếp Loại: ".$_POST['xeploai'],0,0,"L");
$pdf->Ln(10);
$pdf->Cell(0,0,"        Hình thức đào tạo: ".$_POST['hinhthucdaotao'],0,0,"L");

// $pdf->Output();
$pdf->Output('../file/'.$_POST['filename'].'pdf');
?>
