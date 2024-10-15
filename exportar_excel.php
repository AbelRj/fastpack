<?php
require 'vendor/autoload.php'; // Autoload para PhpSpreadsheet
include("bd.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Crear nuevo objeto de hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('datos trabajador');

// Encabezados de las columnas para el grupo familiar
$sheet->setCellValue('A1', 'Datos del trabajador'); // Título de la sección
$sheet->mergeCells('A1:L1'); // Fusiona las celdas de A1 a H1


// Aplicar estilo al título "Grupo Familiar"
$sheet->getStyle('A1')->getFont()->setColor(new Color(Color::COLOR_WHITE));
$sheet->getStyle('A1')->getFont()->setBold(true);
$sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID);
$sheet->getStyle('A1')->getFill()->getStartColor()->setARGB('FF007FFF'); // Color rosado

// Encabezados de las columnas
$sheet->setCellValue('A2', 'Id');
$sheet->setCellValue('B2', 'Rut');
$sheet->setCellValue('C2', 'Nombre-Apellido');
$sheet->setCellValue('D2', 'Sexo');
$sheet->setCellValue('E2', 'Fecha-nacimiento');
$sheet->setCellValue('F2', 'País');
$sheet->setCellValue('G2', 'Profesión');
$sheet->setCellValue('H2', 'Domicilio');
$sheet->setCellValue('I2', 'Teléfono');
$sheet->setCellValue('J2', 'Celular');
$sheet->setCellValue('K2', 'Correo electrónico');
$sheet->setCellValue('L2', 'Estado civil');

// Obtener los datos de la base de datos
$sentencia = $conexion->prepare("SELECT * FROM trabajador");
$sentencia->execute();
$lista_trabajadores = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Llenar los datos en la hoja de cálculo
$fila = 3; // Comienza en la fila 2 porque la primera fila tiene los encabezados
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


// Crear una nueva hoja para el Grupo Familiar
$sheet2 = $spreadsheet->createSheet(1); // Crea una segunda hoja
$sheet2->setTitle('Grupo Familiar'); // Cambia el título de la hoja

// Encabezados de las columnas para el grupo familiar
$sheet2->setCellValue('A1', 'ficha social'); // Título de la sección
$sheet2->mergeCells('A1:I1'); // Fusiona las celdas de A1 a H1


// Aplicar estilo al título "Grupo Familiar"
$sheet2->getStyle('A1')->getFont()->setColor(new Color(Color::COLOR_WHITE));
$sheet2->getStyle('A1')->getFont()->setBold(true);
$sheet2->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID);
$sheet2->getStyle('A1')->getFill()->getStartColor()->setARGB('FF007FFF'); // Color rosado


// Encabezados de la segunda fila
$sheet2->setCellValue('A2', 'ID Trabajador');
$sheet2->setCellValue('B2', 'ID Familiar');
$sheet2->setCellValue('C2', 'Nombre y Apellido');
$sheet2->setCellValue('D2', 'Parentesco');
$sheet2->setCellValue('E2', 'Fecha de Nacimiento');
$sheet2->setCellValue('F2', 'Sexo');
$sheet2->setCellValue('G2', 'Estado Civil');
$sheet2->setCellValue('H2', 'Nivel Educacional');
$sheet2->setCellValue('I2', 'Actividad');

// Obtener los datos de la base de datos de familiares
$sentenciaFamiliares = $conexion->prepare("SELECT * FROM grupo_familiar"); // Asegúrate de que la tabla sea correcta
$sentenciaFamiliares->execute();
$lista_familiares = $sentenciaFamiliares->fetchAll(PDO::FETCH_ASSOC);

// Llenar los datos en la hoja de cálculo de familiares
$fila = 3; // Comienza en la fila 3 porque las dos primeras filas tienen los encabezados
foreach ($lista_familiares as $familiar) {
    $sheet2->setCellValue('A' . $fila, $familiar['trabajador_id']);
    $sheet2->setCellValue('B' . $fila, $familiar['id']); // Asegúrate de que 'id' sea el nombre correcto
    $sheet2->setCellValue('C' . $fila, $familiar['nombre_apellido']);
    $sheet2->setCellValue('D' . $fila, $familiar['parentesco']);
    $sheet2->setCellValue('E' . $fila, $familiar['fecha_nacimiento']);
    $sheet2->setCellValue('F' . $fila, $familiar['sexo']);
    $sheet2->setCellValue('G' . $fila, $familiar['estado_civil']);
    $sheet2->setCellValue('H' . $fila, $familiar['nivel_educacional']);
    $sheet2->setCellValue('I' . $fila, $familiar['actividad']);
    $fila++;
}

// Ajustar automáticamente el ancho de las columnas de familiares
foreach (range('A', 'I') as $columna) {
    $sheet2->getColumnDimension($columna)->setAutoSize(true);
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
