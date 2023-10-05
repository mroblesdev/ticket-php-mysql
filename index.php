<?php

/**
 * Este script PHP genera un ticket en PDF con información de una venta.
 * Requiere la inclusión de FPDF, conexión a la base de datos y una clase para convertir números a letras.
 *
 * @link https://github.com/mroblesdev
 * @author mroblesdev
 */

require 'conexion.php';
require 'fpdf/fpdf.php';
require 'helpers/NumeroALetras.php';

define('MONEDA', '$');
define('MONEDA_LETRA', 'pesos');
define('MONEDA_DECIMAL', 'centavos');

$idVenta = isset($_GET['id']) ? $mysqli->real_escape_string($_GET['id']) : 1;

if (filter_var($idVenta, FILTER_VALIDATE_INT) === false) {
    $idVenta = 1;
}

$sqlVenta = "SELECT folio, total, DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha_venta, DATE_FORMAT(fecha,'%H:%i') AS hora
FROM ventas WHERE id = $idVenta LIMIT 1";
$resultado = $mysqli->query($sqlVenta);

$numeroFilas = $resultado->num_rows;
if ($numeroFilas == 0) {
    echo 'No hay datos que coincidan con la consulta';
    exit;
}

$row_venta = $resultado->fetch_assoc();

$total = number_format($row_venta['total'], 2, '.');

$sqlDetalle = "SELECT nombre, cantidad, precio FROM detalle_venta WHERE id_venta = $idVenta";
$resultadoDetalle = $mysqli->query($sqlDetalle);

$pdf = new FPDF('P', 'mm', array(80, 200));
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);
$pdf->SetFont('Arial', 'B', 9);

$pdf->Image('images/logo.png', 15, 2, 45);

$pdf->Ln(7);

$pdf->MultiCell(70, 5, 'MI TIENDA CDP', 0, 'C');

$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17, 5, mb_convert_encoding('Núm ticket: ', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(53, 5, $row_venta['folio'], 0, 1, 'L');

$pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');

$pdf->Cell(10, 4, 'Cant.', 0, 0, 'L');
$pdf->Cell(30, 4, mb_convert_encoding('Descripción', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->Cell(15, 4, 'Precio', 0, 0, 'C');
$pdf->Cell(15, 4, 'Importe', 0, 1, 'C');

$pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');

$totalProductos = 0;
$pdf->SetFont('Arial', '', 7);

while ($row_detalle = $resultadoDetalle->fetch_assoc()) {
    $importe = number_format($row_detalle['cantidad'] * $row_detalle['precio'], 2, '.', ',');
    $totalProductos += $row_detalle['cantidad'];

    $pdf->Cell(10, 4, $row_detalle['cantidad'], 0, 0, 'L');

    $yInicio = $pdf->GetY();
    $pdf->MultiCell(30, 4, mb_convert_encoding($row_detalle['nombre'], 'ISO-8859-1', 'UTF-8'), 0, 'L');
    $yFin = $pdf->GetY();

    $pdf->SetXY(45, $yInicio);

    $pdf->Cell(15, 4, MONEDA . ' ' . number_format($row_detalle['precio'], 2, '.', ','), 0, 0, 'C');

    $pdf->SetXY(60, $yInicio);
    $pdf->Cell(15, 4, MONEDA . ' ' . $importe, 0, 1, 'R');
    $pdf->SetY($yFin);
}

$resultadoDetalle->close();

$pdf->Ln();

$pdf->Cell(70, 4, mb_convert_encoding('Número de articulos:  ' . $totalProductos, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(70, 5, sprintf('Total: %s  %s', MONEDA, number_format($total, 2, '.', ',')), 0, 1, 'R');

$pdf->Ln(2);

$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(70, 4, 'Son ' . strtolower(NumeroALetras::convertir($total, MONEDA_LETRA, MONEDA_DECIMAL)), 0, 'L', 0);

$pdf->Ln();

$pdf->Cell(35, 5, 'Fecha: ' . $row_venta['fecha_venta'], 0, 0, 'C');
$pdf->Cell(35, 5, 'Hora: ' . $row_venta['hora'], 0, 1, 'C');

$pdf->Ln();

$pdf->MultiCell(70, 5, 'AGRADECEMOS SU PREFERENCIA VUELVA PRONTO!!!', 0, 'C');

$resultado->close();
$mysqli->close();

$pdf->Output();
