<?php
require('fpdf.php');

class PDF extends FPDF {

	public $intestazione;
	public $logo;
	private $ids;

	function PDF($t, $l) {
		parent::__construct();
		$this->intestazione = $t;
		$this->logo = $l;
		$this->AliasNbPages(); // To allow counting the total number of pages
	}

	function Header() {
		$this->Image($this->logo,10,6,30);
		// Arial bold 15

		$this->SetFont('Arial','B',15);
		// Calculate width of title and position
		$w = $this->GetStringWidth($this->intestazione)+6;
		$this->SetX((297-$w)/2);
		// Colors of frame and text
	 	$this->SetDrawColor(180,0,0);
		$this->SetTextColor(0,107,0);
		// Thickness of frame (1 mm)
		$this->SetLineWidth(1);
		// Title
		$this->Cell($w,9,$this->intestazione,'B',1,'C',false);
		// Line break
		$this->Image($this->logo,255,6,30);
		$this->Ln(10);
	}

	function Footer() {
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Text color in gray
		$this->SetTextColor(128);
		// Page number
		$this->Cell(0,10,'Pagina '.$this->PageNo().' di {nb}',0,0,'C');
	}



	// Draw the Table
	function FancyTable($header, $w, $data) {
		// Colors, line width and bold font
		$this->SetFillColor(0,107,0);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,107,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');

		// Header
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],'LR',0,'C',true);
		$this->Ln();

		// Color and font restoration
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');

		// Data
		$fill = false;
		foreach($data as $row) {
			$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
			$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
			$this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
			$this->Cell($w[3],6,$row[3],'LR',0,'C',$fill);
			$this->Cell($w[4],6,$row[4],'LR',0,'L',$fill);
			$this->Cell($w[5],6,$row[5],'LR',0,'L',$fill);
			$this->Cell($w[6],6,$row[6],'LR',0,'L',$fill);
			$this->Cell($w[7],6,$row[7],'LR',0,'L',$fill);
			$this->Cell($w[8],6,$row[8],'LR',0,'C',$fill);

			// To show images we first draw an empty cell and then overplace images
			$this->Cell($w[9],6,'','LR',0,'C',$fill);
			$curr_x = $this->GetX() - $w[9] + 1;
			foreach($row[9] as $curr_img) { // Row 9 is a vector
				$this->Image($curr_img, $curr_x, $this->GetY()+1.5, 3);
				$curr_x += 4;
			}

			// Close the line
			$this->Ln();
			$fill = !$fill;
		}

		// Closing line
		$this->Cell(array_sum($w),0,'','T');
	}
}


?>
