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

    $pdf->SetFont('Arial', 'B', 10);
    write_to_pdf($pdf, $data);

    $pdfname = 'files/' . str_replace(".", "", microtime(true)) . '.pdf';
    $pdf->Output($pdfname, "F");
    return $pdfname;
}

function write_to_pdf(&$pdf, $data, $x = 10, &$y = 40, $tier = 1, $name = '') {

    $col = &$y;
    foreach ($data as $value) :

        if ($tier == 1):
            $name = $value->name;
        else:
            $name.="->" . $value->name;
        endif;

        // Prints the name of the menu in here
        $pdf->Text($x, $col, $name);


        if (isset($value->images)) {

            $images = $value->images;
            $_img = explode(",", $images);

            if (count($_img) > 0) {
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
                    }
                }
                if ($count > 1) {
                    if (($count - 1) % 4)
                        $col+=42;
                }
            }
        }

        if (isset($value->condition)):
            $pdf->Text($x, $col+=5, "Condition <" . $value->condition . ">");
        endif;

        if (isset($value->tags)):
            $pdf->Text($x, $col+=5, "Comments " . $value->tags);
        endif;

        if (290 - $col < 50):
            $pdf->AddPage();
            $col = 30;
        endif;

        write_to_pdf($pdf, $value->child, $x, $col+=10, $tier + 1, $name);



    endforeach;
}

function scale($y, &$pdf) {
    if ($y > 290) {
        $pdf->AddPage();
        return 40;
    }
    return $y;
}

?>
