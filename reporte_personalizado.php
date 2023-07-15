<?php
require 'fpdf/fpdf.php';

class PDF extends FPDF {
    function Header() {
        // Logo
        $this->Image('Img/Icono.png', 5, 10, 30 );
        // Title
        $this->SetFont('Arial','B',20);
        $this->SetTextColor(0, 0, 0);
        $this->SetXY(0, 40);
        $this->Cell(210,10, 'Reporte del formulario #'.$_GET['id'],0,0,'C');
        // Line break
        $this->Ln(20);
    }

    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I', 8);
        // Page number
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
    }
}

$mysqli = new mysqli("localhost","root","","administración");

if(mysqli_connect_errno()){
    echo 'ConexionFallida : ', mysqli_connect_error();
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM formularios WHERE id = $id";
$resultado = $mysqli->query($query);

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 30);

$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(128, 128, 128);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(10,10,'ID',1,0,'C', true);
$pdf->Cell(40,10,'Nombre',1,0,'C', true);
$pdf->Cell(50,10,'Correo electronico',1,0,'C', true);
$pdf->Cell(50,10,'Mensaje',1,0,'C', true);
$pdf->Cell(40,10,'Fecha de envio',1,1,'C', true);

$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0, 0, 0);

if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $pdf->Cell(10,6,$row['id'],1,0,'C');
    $pdf->SetXY(20, $pdf->GetY());
    $pdf->MultiCell(40,6,utf8_decode($row['nombre']),1,'C');
    $pdf->SetXY(60, $pdf->GetY());
    $pdf->MultiCell(50,6,utf8_decode($row['correo']),1,'C');
    $pdf->SetXY(110, $pdf->GetY());
    $pdf->MultiCell(50,6,utf8_decode($row['mensaje']),1,'C');
    $pdf->SetXY(160, $pdf->GetY());
    $pdf->Cell(40,6,$row['fecha_envio'],1,1,'C');
} else {
    $pdf->Cell(0,6,'No se encontró el formulario solicitado.',0,1);
}

$pdf->Output();
$mysqli->close();
?>