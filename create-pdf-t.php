<?php
use setasign\Fpdi;

// create_graduation_certificate('BUI LE HUYNH', '28/05/1996', 'Gioi', 'Chinh Quy', '', '');


function test($name, $dob)
{
    require('fpdf.php');
    require('FPDI/src/autoload.php');
    $pdf = new Fpdi\Fpdi();
    $pdf->AddPage('L'); //Theo chieu ngang

    $pdf->setSourceFile("bangtotnghiepmau.pdf");
    $tplId = $pdf->importPage(1);
    $pdf->useTemplate($tplId, 0, 2, 298);

    
    $pdf->SetFont('Helvetica','',0.1);
    $pdf->Cell(0,0,"abc:dbc;",0,0,"C");

    $pdf->SetFont('Helvetica');

    //Tieng Viet
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(197, 102);
    $pdf->Write(0, $name);

    $pdf->SetXY(197, 109.7);
    $pdf->Write(0, '28/05/1996');

    $pdf->SetXY(213 , 116);
    $pdf->Write(0, 'Gioi');

    $pdf->SetXY(213 , 123);
    $pdf->Write(0, 'Chinh Quy');



    // Tieng Anh
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(197, 102);
    $pdf->Write(0, $name);

    $pdf->SetXY(197, 109.7);
    $pdf->Write(0, '28/05/1996');

    $pdf->SetXY(213 , 116);
    $pdf->Write(0, 'Gioi');

    $pdf->SetXY(213 , 123);
    $pdf->Write(0, 'Chinh Quy');
    $pdf->Output();
}

test('BUI LE HUYNH', '28/05');