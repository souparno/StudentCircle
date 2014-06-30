<?php

require('fpdf.php');

class PDF extends FPDF {

    function Header() {
        $this->SetTextColor(0,0,0);
        $this->SetLineWidth(0.2);
        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(240, 240, 240);
        $this->Rect(0.2, 0.2, $this->w - 0.3, 20, "FD");
        $this->Image('img/logo.png', 2, 2, 25, 12.5);
        $this->SetFont('Arial', 'B', 15);
        //$this->Cell(80);
        $this->SetX(0);
        $this->SetY(2);
        $this->Cell(0, 15, 'StudentCircle Lettings', 0, 0, 'C');
        $this->Ln(20);
    }

    function Footer() {
        /* $this->SetLineWidth(0.2);
          $this->SetDrawColor(0,0,0);
          $this->SetFillColor(240,240,240);
          $this->Rect(0.2,$this->h - 20.3,$this->w - 0.3,20,"FD"); */
    }

    function FancyTable($header, $data) {
        // Colors, line width and bold font
        $this->SetFillColor(255, 102, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(50, 35, 105);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('Arial','',12);
        // Data
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row[2], 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        //$this->Cell(array_sum($w), 0, '', 'T');
    }

}
?>