<?php
require_once 'vendor/fpdf/fpdf.php';

class orderPDF extends FPDF
{
	// En-tête
	function Header()
	{
		// Logo
		$this->Image('files/CourtCircuitSenartLogo02Noir.png',10,6,50);
		// Police Arial gras 15
		$this->SetFont('Arial','B',18);
		// Décalage à droite
		$this->Cell(110);
		// Titre
		$this->Cell(80,10,'BON de COMMANDE',1,0,'C');
		// Police Arial gras 11
		$this->SetFont('Arial','B',8);
		// Saut de ligne
		$this->Ln(18);
		// Téléphone
		$this->Cell(30, 8, 'Tel : 06.58.19.93.04', 0,0);
		// Saut de ligne
		$this->Ln(3);
		// Email
		$this->Cell(30, 8, 'Email : contact@courtcircuit.bio',0,2);
		// Line
		$this->Line(50, 46, 155, 46);
		// Saut de ligne
		$this->Ln(12);
	}
	// Order details
	function orderDetail($order){
		// Left col title
		$this->Cell(80, 8, 'IDENTIFIANTS de COMMANDE', 0, 0, 'L');
		// Right decay
		$this->Cell(20);
		// Customer
		$this->Cell(80, 8, 'DESTINATAIRE',0,1, 'L');
		// Order ref
		$this->Cell(80, 8, 'REF : #'.$order->ref(), 1, 0,'L');
		// Right decay
		$this->Cell(20);
		// Customer name
		$this->Cell(80, 8, utf8_decode($order->customer()->name()).' '.utf8_decode($order->customer()->lastname()), 'LTR', 1, 'L');
		// Order date
		$this->Cell(80,8, 'Date : '.$order->order_dateTime()->format('d/m/Y, H\hi'), 1, 0, 'L');
		// Right decay
		$this->Cell(20);
		// Customer address
		$this->Cell(80, 8, utf8_decode($order->delivery_address()).', '.$order->delivery_zip().' '.$order->delivery_town(), 'LR', 1, 'L');
		// Order delivery date
		$this->Cell(80,8, 'Livraison le : '.$order->delivery_dateTime()->format('d/m/Y'), 1, 0, 'L');
		// Right decay
		$this->Cell(20);
		// Customer phone
		$this->Cell(80, 8, $order->customer()->phone(), 'LR', 1, 'L');
		// Order delivery infos
		$this->Cell(180, 8, 'Commentaire : '.utf8_decode($order->delivery_instructions()), 1, 1, 'L');
		// Line
		$this->Line(50, 97, 155, 97);
		// Saut de ligne
		$this->Ln(12);
	}
	// OrderLines details
	function orderlinesDetails($order){
		// Table tr
		$this->Cell(45, 8, 'PRODUIT', 1, 0, 'C');
		$this->Cell(45, 8, 'P.U', 1, 0, 'C');
		$this->Cell(45, 8, 'QUANTITE', 1, 0, 'C');
		$this->Cell(45, 8, 'TOTAL', 1, 1, 'C');
		// Table orderlines
		foreach( $order->list_ol() as $ol ){
			$this->Cell(45, 8, utf8_decode($ol->product()->label()), 1, 0, 'C');
			$this->Cell(45, 8, $ol->product()->price().' '.chr(128), 1, 0, 'C');
			$this->Cell(45, 8, $ol->amount(), 1, 0, 'C');
			$this->Cell(45, 8, $ol->value().' '.chr(128), 1, 1, 'C');
		}
		// delivery cost
		$this->SetFont('Arial', 'IB', 8);
		$this->Cell(135, 8, 'Frais de livraison', 1, 0, 'C');
		if($order->delivery_cost() == 5){
			$this->Cell(45, 8, '5 '.chr(128), 1, 1, 'C');
		}
		else{
			$this->Cell(45, 8, 'Gratuits', 1, 1, 'C');
		}
		// Sum orderlines
		$this->Cell(135, 8, 'Montant de la commande', 1, 0, 'C');
		$this->Cell(45, 8, $order->total_amount().' '.chr(128), 1, 1, 'C');
		// Total amount
		$this->Cell(135, 8, 'TOTAL A PAYER ', 0, 0, 'R');
		$this->SetFillColor(0);
		$this->SetTextColor(255);
		$this->Cell(45, 8, $order->total_amount().' '.chr(128), 1, 1, 'C', true);
		$this->SetTextColor(0);
		$this->Cell(180, 8, "* TVA non applicable art-293B du CGI", 'T', 1, 'L');
		// Payment mode
		$this->Cell(45, 8, utf8_decode('Mode de règlement : Chèque ou espèce'), 0, 1, 'L');
		$this->Cell(45, 8, utf8_decode("Date d'échéance : ").$order->delivery_dateTime()->format('d/m/Y'), 0, 1, 'L');
	}
	// Pied de page
	function Footer()
	{
		// Positionnement à 1,5 cm du bas
		$this->SetY(-15);
		// Police Arial italique 8
		$this->SetFont('Arial','I',8);
		// Association infos
		$this->Cell(180, 8, utf8_decode("Association loi 1901 ou assimilé - Capital de 1 646 ").chr(128)." - SIRET: en attente", 'T', 0, 'C');
		// Numéro de page
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
	}
}
