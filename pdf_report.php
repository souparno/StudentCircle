<?php

function get_pdf_report($landlord_name, $landlord_address, $data) {


    $SHOW_BORDER = 0;
    $headers = array('Item', 'Condition', 'Comments');
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

function write_to_pdf(&$pdf, $data,$x=20,$y=60) {

    foreach ($data as $value) :
        //put the pdf function here to write to the pdf
        $section_name = $value->name;
        $pdf->Text($x,$y+=20,"Name ".$section_name);
        if (isset($value->tags)) {
            $section_tags = $value->tags;
            $pdf->Text($x,$y,"Section Tags ".$section_tags);
        }
        if (isset($value->images)) {
            $images = $value->images;
            $pdf->Text($x,$y,"images ".$images);
        }
        if (isset($value->condition)) {
            $condition = $value->condition;
            $pdf->Text($x,$y,"condition ".$condition);
        }
        $pdf->Ln(15); 
        write_to_pdf($pdf, $value->child,$x+15,$y+15);
    endforeach;
}

?>
