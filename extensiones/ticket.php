<?php

session_start();

$nombre_usuario = $_SESSION["nombre_usuario"];

require_once "../controladores/Ventas.controlador.php";
require_once "../controladores/Producto.controlador.php";
require_once "../modelos/Ventas.modelo.php";
require_once "../modelos/Producto.modelo.php";

# Incluyendo librerias necesarias #
require "./code128.php";

$pdf = new PDF_Code128('P', 'mm', array(80, 258));
$pdf->SetMargins(4, 10, 4);
$pdf->AddPage();

/* ========================================
FORMATEAR PRECIOS
======================================== */

function formatearPrecio($precio) {
    return number_format($precio, 2, '.', ',');
}

/* ========================================
MOSTRANDO DATOS DE LA VENTA
======================================== */

$item = "id_venta";
$valor = $_GET["idVentaTicket"];

$respuesta = ControladorVenta::ctrMostrarListaVentas($item, $valor);
$respuesta_dv = ControladorVenta::ctrMostrarDetalleVenta($item, $valor);

$horaVenta = $respuesta["fecha_venta_a"];
$horaFormateada = date("h:i A", strtotime($horaVenta));

# Encabezado y datos de la empresa #
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", strtoupper("AVITAC")), 0, 'C', false);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "RUC: 10234533456234"), 0, 'C', false);
$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Direccion Ejemplo, Ejemplo"), 0, 'C', false);
$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Teléfono: 920468502"), 0, 'C', false);
$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Email: correo@ejemplo.com"), 0, 'C', false);

$pdf->Ln(1);
$pdf->Cell(0, 5, iconv("UTF-8", "ISO-8859-1", "------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(5);

$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Fecha: " . date("d/m/Y", strtotime($respuesta["fecha_venta"])) . " " . $horaFormateada), 0, 'C', false);
$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Cajero: ".$nombre_usuario.""), 0, 'C', false);
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", strtoupper("Ticket Nro: ".$respuesta["num_comprobante"]."")), 0, 'C', false);
$pdf->SetFont('Arial', '', 9);

$pdf->Ln(1);
$pdf->Cell(0, 5, iconv("UTF-8", "ISO-8859-1", "------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(5);

$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Cliente: ".$respuesta["razon_social"].""), 0, 'C', false);
$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Documento: ".$respuesta["numero_documento"].""), 0, 'C', false);
$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Teléfono: ".$respuesta["telefono"].""), 0, 'C', false);
$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Dirección: ".$respuesta["direccion"].""), 0, 'C', false);

$pdf->Ln(1);
$pdf->Cell(0, 5, iconv("UTF-8", "ISO-8859-1", "-------------------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(3);

# Tabla de productos #
$pdf->Cell(10, 5, iconv("UTF-8", "ISO-8859-1", "Cant. U."), 0, 0, 'C');
$pdf->Cell(22, 5, iconv("UTF-8", "ISO-8859-1", "Cant. KG."), 0, 0, 'C');
$pdf->Cell(16, 5, iconv("UTF-8", "ISO-8859-1", "Precio"), 0, 0, 'C');
$pdf->Cell(28, 5, iconv("UTF-8", "ISO-8859-1", "Total"), 0, 0, 'C');

$pdf->Ln(3);
$pdf->Cell(72, 5, iconv("UTF-8", "ISO-8859-1", "-------------------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(3);

/*----------  Detalles de la tabla  ----------*/

foreach ($respuesta_dv as $value) {
    $totalPrecioProducto = formatearPrecio($value["cantidad_kg"] * $value["precio_venta"]);

    $pdf->MultiCell(0, 2, iconv("UTF-8", "ISO-8859-1", ""), 0, 'C');
    $pdf->MultiCell(0, 4, iconv("UTF-8", "ISO-8859-1", "".$value["nombre_producto"].""), 0, 'C', false);
    
    $pdf->Cell(10, 4, iconv("UTF-8", "ISO-8859-1", "".$value["cantidad_u"].""), 0, 0, 'C');
    $pdf->Cell(22, 4, iconv("UTF-8", "ISO-8859-1", "".$value["cantidad_kg"].""), 0, 0, 'C');
    $pdf->Cell(16, 4, iconv("UTF-8", "ISO-8859-1", "S/ ".$value["precio_venta"].""), 0, 0, 'C');
    $pdf->Cell(28, 4, iconv("UTF-8", "ISO-8859-1", "S/ ".$totalPrecioProducto.""), 0, 0, 'C');
    $pdf->Ln(2);
    $pdf->Ln(2);
}

/*----------  Fin Detalles de la tabla  ----------*/

$pdf->Cell(72, 5, iconv("UTF-8", "ISO-8859-1", "-------------------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(5);

# Impuestos & totales #
$pdf->Cell(18, 5, iconv("UTF-8", "ISO-8859-1", ""), 0, 0, 'C');
$pdf->Cell(22, 5, iconv("UTF-8", "ISO-8859-1", "SUBTOTAL"), 0, 0, 'C');
$pdf->Cell(32, 5, iconv("UTF-8", "ISO-8859-1", "+ S/ ".formatearPrecio($respuesta["sub_total"]).""), 0, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(18, 5, iconv("UTF-8", "ISO-8859-1", ""), 0, 0, 'C');
$pdf->Cell(22, 5, iconv("UTF-8", "ISO-8859-1", "IVA (%)"), 0, 0, 'C');
$pdf->Cell(32, 5, iconv("UTF-8", "ISO-8859-1", "+ S/ ".formatearPrecio($respuesta["igv"]).""), 0, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(18, 5, iconv("UTF-8", "ISO-8859-1", ""), 0, 0, 'C');
$pdf->Cell(22, 5, iconv("UTF-8", "ISO-8859-1", "TOTAL"), 0, 0, 'C');
$pdf->Cell(32, 5, iconv("UTF-8", "ISO-8859-1", "+ S/ ".formatearPrecio($respuesta["total"]).""), 0, 0, 'C');

$pdf->Ln(5);
$pdf->Cell(0, 5, iconv("UTF-8", "ISO-8859-1", "------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(8);

$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", strtoupper("GRACIAS POR TU COMPRA!")), 0, 'C', false);
$pdf->SetFont('Arial', '', 9);

$pdf->Output("F", "path_to_directory/ticket".$valor.".pdf");

// Ahora redirige el navegador para descargar el archivo
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=ticket".$valor.".pdf");
readfile("path_to_directory/ticket".$valor.".pdf");
exit();
?>
