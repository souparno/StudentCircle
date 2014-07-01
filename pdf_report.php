<?php

function get_pdf_report($landlord_name, $landlord_address, $data) {


    $SHOW_BORDER = 0;
    //$headers = array('Item', 'Condition', 'Comments');
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 60);
    $pdf->Cell(0, 140, "Property Inventory", $SHOW_BORDER, 0, 'C');
    $pdf->Ln(30);
    $pdf->SetFont('Arial', 'B', 30);
    $pdf->Cell(0, 170, $landlord_name, $SHOW_BORDER, 0, 'C');
    $pdf->Ln(30);
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(0, 170, $landlord_address, $SHOW_BORDER, 0, 'C');
    // $pdf->FancyTable($headers, $data);

    $pdf->AddPage();
    write_to_pdf($pdf, $data);

    $pdfname = 'files/' . str_replace(".", "", microtime(true)) . '.pdf';
    $pdf->Output($pdfname, "F");
    return $pdfname;
}

function write_to_pdf(&$pdf, $data, $x = 0, &$y = 30, $tier = 1) {

    $col = &$y;

    foreach ($data as $value) :

        $pdf->Text($x, $col, "Name : " . $value->name);
        if (isset($value->tags))
            $pdf->Text($x, $col+=10, "Section Tags : " . $value->tags);

        if (isset($value->images))
            $pdf->Text($x, $col+=10, "images : " . $value->images);

        if (isset($value->condition))
            $pdf->Text($x, $col+=10, "condition : " . $value->condition);

        write_to_pdf($pdf, $value->child, $x+=15, $col+=15, $tier + 1);
        $x-=15;

    endforeach;
}

?>
