<?php

// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

require_once('tfpdf.php');
require_once('TcpdfFpdi.php');
require_once('autoload.php');

$pdf = new Fpdi();

$pageCount = $pdf->setSourceFile('bangtotnghiepmau.pdf');
$pageId = $pdf->importPage(1, PdfReader\PageBoundaries::MEDIA_BOX);

$pdf->addPage();
$pdf->useImportedPage($pageId, 10, 10, 90);

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
?>
