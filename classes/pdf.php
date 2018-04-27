<?php 
	require("Fpdf/fpdf.php");

	class pdf{
		private $Title,$Img,$Email,$Name,$Age,$Country, $title, $desc, $price, $totalHours;

		public function __construct($Title,$Img,$Email,$Name,$Age,$Country, $title, $desc, $price, $totalHours){
			$this->Title = $Title;
			$this->Img = $Img;
			$this->Name = $Name;
			$this->Age = $Age;
			$this->Country = $Country;
			$this->Email = $Email;
			$this->title = $title;
			$this->desc = $desc;
			$this->price = $price;
			$this->totalHours = $totalHours;
		}

		public function Send_Pdf(){
			$pd = new FPDF();
			$pd->AddPage();
			$pd->SetFont('arial','B',30);
			$pd->Cell(0,10,$this->Title,'B',1,'C',0,'google.com');
			
			

			$pd->Ln();
			$pd->SetFont('arial','I',15);
			$pd->SetX(70);
			$pd->Cell(0,10,"Name : ".$this->Name,0,1,'L',0,'google.com');


			$pd->Ln();
			$pd->SetX(70);
			$pd->Cell(0,10,"Email : ".$this->Email,0,1,'L',0,'google.com');


			$pd->Ln();
			$pd->SetX(70);
			$pd->Cell(0,10,"Age : ".$this->Age,0,1,'L',0,'google.com');

			$pd->Ln();
			$pd->SetX(70);
			$pd->Cell(0,10,"Country : ".$this->Country,0,1,'L',0,'google.com');


			$pd->Image($this->Img,1,25,60,60);

			$pd->Ln();
			$pd->Cell(0,10,"Job Title : ".$this->title,0,1,'L',0,'google.com');


			$pd->Ln();
			$pd->Cell(0,10,"Job Description : ".$this->desc,0,1,'L',0,'google.com');


			$pd->Ln();
			$pd->Cell(0,10,"Job Price : ".$this->price,0,1,'L',0,'google.com');


			$pd->Ln();
			$pd->Cell(0,10,"Job Total Hours : ".$this->totalHours,0,1,'L',0,'google.com');


			


			$pd->Output();
		}

	}


?>