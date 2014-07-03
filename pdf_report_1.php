<?php

function get_pdf_report($landlord_name, $landlord_address, $data) {


    $SHOW_BORDER = 0;
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

    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 9);
    write_to_pdf($pdf, $data);

    $pdfname = 'files/' . str_replace(".", "", microtime(true)) . '.pdf';
    $pdf->Output($pdfname, "F");
    return $pdfname;
}

function write_to_pdf(&$pdf, $data, $x = 10, &$y = 40, $tier = 1) {

    $col = &$y;

    foreach ($data as $value) :

        //$pdf->Rect(10, 30, $pdf->w - 20, 10, "DF");
        //$pdf->SetXY(10, 35);
        //$pdf->SetTextColor(255, 255, 0);
        $pdf->Text($x, $col, "Name : " . $value->name);

        if (isset($value->tags))
            $pdf->Text($x, $col+=5, "Section Tags : " . $value->tags);

        if (isset($value->condition))
            $pdf->Text($x, $col+=5, "condition : " . $value->condition);

        //if (isset($value->images))
        //    $pdf->Text($x, $col+=5, "images : " . $value->images);



        if ($tier == 1) {
            $images = "";
            get_images($value->child, $images);
            if (isset($value->images)) {
                $images = $value->images . "," . substr($images, 0, -1);
            } else {
                $images = substr($images, 0, -1);
            }
            $_img = explode(",", $images);

            if (count($_img) > 1) {
                $pos = 5;
                $pos2 = 0;
                $count = 1;
                $col+=2;
                foreach ($_img as $v) {
                    if ($v != "") {
                        $pdf->Image($v, $x + $pos, $col + $pos2, 40, 40);
                        if (!($count % 4)) {
                            $pos = 5;
                            $pos2 = 0;
                            $col+=42;
                        } else {
                            $pos+=50;
                        }
                        $count++;
                        //$pdf->Text($x, $col+=5, "images : " . $v);
                    }
                }
                if($count>1){ 
                    //$col+=round((($count - 1) % 4),-1) * 40;
                    if(($count - 1) % 4) $col+=42;
                    //$pdf->Text($x, $col, "count : " . $count ." and after div".  round((($count - 1) % 4),-1) * 40);
                }
            }
        }
        write_to_pdf($pdf, $value->child, $x + 10, $col+=10, $tier + 1);
    endforeach;
}

function get_images($data, &$image) {
    $images = &$image;
    foreach ($data as $value):
        if (isset($value->images)) {
            $images.=$value->images . ",";
        }
        get_images($value->child, $images);
    endforeach;
}

?>
