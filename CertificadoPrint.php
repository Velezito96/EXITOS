<?php
use FontLib\Table\Type\post;
require_once('tcpdf/tcpdf.php');
date_default_timezone_set("America/Bogota");
setlocale(LC_ALL, 'es_ES');
$fecha   = date('d-m-Y'); 
class MYPDF extends TCPDF {
  public function Header(){
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 0);
        $img_file = 'img/plantillaCertificados/certificado.jpg';
        $this->Image($img_file, 0, 00, 29.7, 21.0, '', '', '', false, 600, '', false, false, 0);   
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        $this->setPageMark();
    }

  
  
}


$pdf = new MYPDF('PDF_PAGE_ORIENTATION', 'cm', 'Landscape', true, 'UTF-8', false);

$pdf->SetMargins(0, 0, 0);
$pdf->SetHeaderMargin(2);
$pdf->setPrintFooter(false);
$pdf->setPrintHeader(true);
$pdf->SetAutoPageBreak(false);
$pdf->SetCreator('UrianViera');
$pdf->SetAuthor('UrianViera');
$pdf->AddPage('L','A4' );


$pdf->SetXY(0, 4.5);
$descripcion='EL CENTRO DE ALTOS ESTUDIOS EMPRESARIALES ÉXITOS DEL SABER';
$pdf->SetFont('helvetica','B',15); 
$pdf->Cell(29.7,1,$descripcion,0,0,'C');


$pdf->SetXY(0, 5.3 );
$descripcion='EN CONVENIO CON LA CÁMARA DE COMERCIO DE JAÉN';
$pdf->SetFont('helvetica','B',15); 
$pdf->Cell(29.7,1,$descripcion,0,0,'C');

$pdf->SetXY(0, 6.1 );
$descripcion='OTORGAN EL PRESENTE';
$pdf->SetFont('helvetica','B',15); 
$pdf->Cell(29.7,1,$descripcion,0,0,'C');

$pdf->SetXY(0, 0.3 );
$descripcion='CERTIFICADO A';
$pdf->SetFont('helvetica','B',15);
$pdf->Cell(29.7,1,$descripcion,0,0,'C');

$pdf->SetXY(0, 8.8);
$nombre=$_POST['contenedorNombres'];
$pdf->SetFont('helvetica','B',30);
$pdf->Cell(29.7,1,$nombre,0,0,'C');

$pdf->SetXY(0, 10.3);
$descripcion='Por haber culminado y aprobado satisfactoriamente todos los módulos del curso de';
$pdf->SetFont('helvetica','',15); 
$pdf->Cell(29.7,1,$descripcion,0,0,'C');

$pdf->SetXY(0, 11.5);
$NombreCurso=$_POST['contenedorNombreCursos'];
$pdf->SetFont('helvetica','B',25); 
$pdf->Cell(29.7,1,$NombreCurso,0,0,'C');

$pdf->SetXY(0, 13);
$fechaDuracion=$_POST['contenedorFechaDuracions'];
$pdf->SetFont('helvetica','',15); 
$pdf->Cell(29.7,1,$fechaDuracion,0,0,'C');

$pdf->SetXY(0, 14);
$horas=$_POST['contenedorHoras'];
$pdf->SetFont('helvetica','',15); 
$pdf->Cell(29.7,1,$horas,0,0,'C');

$pdf->SetXY(0, 15.5);
$fechaEntrega=$_POST['contenedorFechaEntregas'];
$pdf->SetFont('helvetica','',15); 
$pdf->Cell(24.7,1,$fechaEntrega,0,0,'R');


$img_file = 'img/logos/firma.png';
$pdf->Image($img_file, 12, 15.7, 5, 2, '', '', '', false, 600, '', false, false, 0);


$pdf->SetXY(0, 17);
$descripcion='________________________';
$pdf->SetFont('helvetica','',15); 
$pdf->Cell(18,1,$descripcion,0,0,'R');

$pdf->SetXY(0, 17.7);
$descripcion='Ing. Neiler Wilter Pérez Díaz';
$pdf->SetFont('helvetica','',15); 
$pdf->Cell(17.8,1,$descripcion,0,0,'R');

$pdf->SetXY(0, 18.2);
$descripcion='Gerente';
$pdf->SetFont('helvetica','',15); 
$pdf->Cell(15.3,1,$descripcion,0,0,'R');

$pdf->SetXY(4.3, 19.7);
$codigo=strtoupper($_POST['numero']);
$pdf->SetFont('helvetica','',10); 
$pdf->Cell(28.7,0, "Cod. Reg.".$codigo,0,0);

$style = array(
  'border' => 0,
  'vpadding' => 'auto',
  'hpadding' => 'auto',
  'fgcolor' => array(0, 0, 0),
  'bgcolor' => false,
  'module_width' => 1,
  'module_height' => 1
);




$pdf->write2DBarcode('http://edstriunfador.com/frmCertificadoCodReg.php?numero='.$codigo, 'QRCODE,L', 4, 16, 4, 4, $style, 'N');

$pdf->Ln(20);
$pdf->SetFont('Helvetica', 'B', 15, '', 'false');
$pdf->SetFillColor(0, 100, 0, 0);
$pdf->SetTextColor(0, 0, 0, 0);
$pdf->SetXY(5, 120);
$pdf->Cell(200,0, 'Urian Viera - Programador & Desarrollador Web ',0,0,'C');
$pdf->Output($nombre.'-'.$fecha.'.pdf', 'I');


?>


