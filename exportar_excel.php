<?php
require 'vendor/autoload.php'; // Autoload para PhpSpreadsheet
include("bd.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\IOFactory;

// Crear nuevo objeto de hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados de las columnas
$sheet->setCellValue('A1', 'Id');
$sheet->setCellValue('B1', 'Rut');
$sheet->setCellValue('C1', 'Nombre-Apellido');
$sheet->setCellValue('D1', 'Sexo');
$sheet->setCellValue('E1', 'Fecha-nacimiento');
$sheet->setCellValue('F1', 'País');
$sheet->setCellValue('G1', 'Profesión');
$sheet->setCellValue('H1', 'Domicilio');
$sheet->setCellValue('I1', 'Teléfono');
$sheet->setCellValue('J1', 'Celular');
$sheet->setCellValue('K1', 'Correo electrónico');
$sheet->setCellValue('L1', 'Estado civil');

// Obtener los datos de la base de datos
$sentencia = $conexion->prepare("SELECT * FROM trabajador");
$sentencia->execute();
$lista_trabajadores = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Llenar los datos en la hoja de cálculo
$fila = 2; // Comienza en la fila 2 porque la primera fila tiene los encabezados
foreach ($lista_trabajadores as $trabajador) {
    $sheet->setCellValue('A' . $fila, $trabajador['id']);
    $sheet->setCellValue('B' . $fila, $trabajador['rut']);
    $sheet->setCellValue('C' . $fila, $trabajador['nombre_apellido']);
    $sheet->setCellValue('D' . $fila, $trabajador['sexo']);
    $sheet->setCellValue('E' . $fila, $trabajador['fecha_nacimiento']);
    $sheet->setCellValue('F' . $fila, $trabajador['nacionalidad']);
    $sheet->setCellValue('G' . $fila, $trabajador['profesion']);
    $sheet->setCellValue('H' . $fila, $trabajador['domicilio']);
    $sheet->setCellValue('I' . $fila, $trabajador['telefono']);
    $sheet->setCellValue('J' . $fila, $trabajador['celular']);
    $sheet->setCellValue('K' . $fila, $trabajador['correo_electronico']);
    $sheet->setCellValue('L' . $fila, $trabajador['estado_civil']);
    $fila++;
}

// Ajustar automáticamente el ancho de las columnas
foreach (range('A', 'L') as $columna) {
    $sheet->getColumnDimension($columna)->setAutoSize(true);
}

// Crear el archivo Excel y enviarlo al navegador
$writer = new Xlsx($spreadsheet);
$nombreArchivo = 'trabajadores.xlsx';

// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="trabajadores.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');

exit;
