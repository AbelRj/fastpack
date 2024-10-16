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
$sheet->setCellValue('A1', 'Datos del Trabajador'); // Título de la sección
$sheet->mergeCells('A1:L1'); // Fusiona las celdas de A1 a H1


// Aplicar estilo al título "Grupo Familiar"
$sheet->getStyle('A1')->getFont()->setColor(new Color(Color::COLOR_WHITE));
$sheet->getStyle('A1')->getFont()->setBold(true);
$sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID);
$sheet->getStyle('A1')->getFill()->getStartColor()->setARGB('00BFFF'); // Color rosado

// Estilo para los encabezados de las columnas
$cellStyleArray = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'], // Letra blanca
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Texto centrado
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'C71585'], // Fondo rosado oscuro
    ],
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => 'FFFFFF'], // Bordes blancos
        ],
    ],
];

// Aplicar el estilo a cada encabezado
$sheet->setCellValue('A2', 'Id');
$sheet->getStyle('A2')->applyFromArray($cellStyleArray);

$sheet->setCellValue('B2', 'Rut');
$sheet->getStyle('B2')->applyFromArray($cellStyleArray);

$sheet->setCellValue('C2', 'Nombre-Apellido');
$sheet->getStyle('C2')->applyFromArray($cellStyleArray);

$sheet->setCellValue('D2', 'Sexo');
$sheet->getStyle('D2')->applyFromArray($cellStyleArray);

$sheet->setCellValue('E2', 'Fecha-nacimiento');
$sheet->getStyle('E2')->applyFromArray($cellStyleArray);

$sheet->setCellValue('F2', 'País');
$sheet->getStyle('F2')->applyFromArray($cellStyleArray);

$sheet->setCellValue('G2', 'Profesión');
$sheet->getStyle('G2')->applyFromArray($cellStyleArray);

$sheet->setCellValue('H2', 'Domicilio');
$sheet->getStyle('H2')->applyFromArray($cellStyleArray);

$sheet->setCellValue('I2', 'Teléfono');
$sheet->getStyle('I2')->applyFromArray($cellStyleArray);

$sheet->setCellValue('J2', 'Celular');
$sheet->getStyle('J2')->applyFromArray($cellStyleArray);

$sheet->setCellValue('K2', 'Correo electrónico');
$sheet->getStyle('K2')->applyFromArray($cellStyleArray);

$sheet->setCellValue('L2', 'Estado civil');
$sheet->getStyle('L2')->applyFromArray($cellStyleArray);

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


// Crear una nueva hoja para el Grupo Familiar y Apoyo Económico
$sheet2 = $spreadsheet->createSheet(1); // Crea una segunda hoja
$sheet2->setTitle('ficha social'); // Cambia el título de la hoja

// Encabezados de las columnas para el grupo familiar, apoyo económico, emprendimiento, mascotas, ingresos y egresos
$sheet2->setCellValue('A1', 'Trabajador'); // Título de la sección
$sheet2->mergeCells('A1:A1'); // Fusiona las celdas de A1 a H1
$sheet2->setCellValue('B1', 'Grupo Familiar'); // Título de la sección
$sheet2->mergeCells('B1:H1'); // Fusiona las celdas de A1 a H1

$sheet2->setCellValue('I1', 'Apoyo Económico'); // Título de la sección
$sheet2->mergeCells('I1:J1'); // Fusiona las celdas de I1 a J1

$sheet2->setCellValue('K1', 'Emprendimiento'); // Título de la sección
$sheet2->mergeCells('K1:K1'); // Fusiona las celdas de K1 a K1

$sheet2->setCellValue('L1', 'Mascotas'); // Título de la sección
$sheet2->mergeCells('L1:M1'); // Fusiona las celdas de L1 a M1

$sheet2->setCellValue('N1', 'Situacion Económica - Ingresos'); // Título de la sección
$sheet2->mergeCells('N1:O1'); // Fusiona las celdas de N1 a O1

$sheet2->setCellValue('P1', 'Situacion Económica - Egresos'); // Título de la sección
$sheet2->mergeCells('P1:R1'); // Fusiona las celdas de P1 a R1

$sheet2->setCellValue('S1', 'Condiciones de Habitalidad'); // Título de la sección
$sheet2->mergeCells('S1:Y1'); // Fusiona las celdas de S1 a Y1


// Estilo para los encabezados
$headerStyleArray = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'], // Letra blanca
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Texto centrado
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => '00BFFF'], // Fondo rosado
    ],
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => 'FFFFFF'], // Bordes blancos
        ],
    ],
];

// Encabezado del trabajador
$sheet2->setCellValue('A1', 'Trabajador');
$sheet2->mergeCells('A1:A1'); // Fusiona las celdas de A1 a A1
$sheet2->getStyle('A1')->applyFromArray($headerStyleArray);

// Encabezado del grupo familiar
$sheet2->setCellValue('B1', 'Grupo Familiar');
$sheet2->mergeCells('B1:H1'); // Fusiona las celdas de B1 a H1
$sheet2->getStyle('B1:H1')->applyFromArray($headerStyleArray);

// Encabezado de apoyo económico
$sheet2->setCellValue('I1', 'Apoyo Económico');
$sheet2->mergeCells('I1:J1'); // Fusiona las celdas de I1 a J1
$sheet2->getStyle('I1:J1')->applyFromArray($headerStyleArray);

// Encabezado de emprendimiento
$sheet2->setCellValue('K1', 'Emprendimiento');
$sheet2->mergeCells('K1:K1'); // Fusiona las celdas de K1 a K1
$sheet2->getStyle('K1')->applyFromArray($headerStyleArray);

// Encabezado de mascotas
$sheet2->setCellValue('L1', 'Mascotas');
$sheet2->mergeCells('L1:M1'); // Fusiona las celdas de L1 a M1
$sheet2->getStyle('L1:M1')->applyFromArray($headerStyleArray);

// Encabezado de situación económica - Ingresos
$sheet2->setCellValue('N1', 'Situacion Económica - Ingresos');
$sheet2->mergeCells('N1:O1'); // Fusiona las celdas de N1 a O1
$sheet2->getStyle('N1:O1')->applyFromArray($headerStyleArray);

// Encabezado de situación económica - Egresos
$sheet2->setCellValue('P1', 'Situacion Económica - Egresos');
$sheet2->mergeCells('P1:R1'); // Fusiona las celdas de P1 a R1
$sheet2->getStyle('P1:R1')->applyFromArray($headerStyleArray);

// Encabezado de condiciones de habitabilidad
$sheet2->setCellValue('S1', 'Condiciones de Habitabilidad');
$sheet2->mergeCells('S1:Y1'); // Fusiona las celdas de S1 a Y1
$sheet2->getStyle('S1:Y1')->applyFromArray($headerStyleArray);


// Estilo para las celdas con fondo rosado oscuro, letra blanca y bordes blancos
$cellStyleArray = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'], // Letra blanca
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Texto centrado
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'C71585'], // Fondo rosado oscuro
    ],
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => 'FFFFFF'], // Bordes blancos
        ],
    ],
];

// Aplicar el estilo a cada celda
$sheet2->setCellValue('A2', 'Rut-Trabajador');
$sheet2->getStyle('A2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('B2', 'Nombre Familiar');
$sheet2->getStyle('B2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('C2', 'Parentesco');
$sheet2->getStyle('C2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('D2', 'Fecha de Nacimiento Familiar');
$sheet2->getStyle('D2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('E2', 'Sexo Familiar');
$sheet2->getStyle('E2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('F2', 'Estado Civil Familiar');
$sheet2->getStyle('F2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('G2', 'Nivel Educacional Familiar');
$sheet2->getStyle('G2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('H2', 'Actividad Familiar');
$sheet2->getStyle('H2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('I2', 'Nombre Apoyo Económico');
$sheet2->getStyle('I2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('J2', 'Motivo Apoyo Económico');
$sheet2->getStyle('J2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('K2', 'Nombre');
$sheet2->getStyle('K2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('L2', 'Tipo');
$sheet2->getStyle('L2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('M2', 'Cantidad');
$sheet2->getStyle('M2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('N2', 'Nombre');
$sheet2->getStyle('N2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('O2', 'Monto');
$sheet2->getStyle('O2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('P2', 'Descripción');
$sheet2->getStyle('P2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('Q2', 'Monto');
$sheet2->getStyle('Q2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('R2', 'Observaciones');
$sheet2->getStyle('R2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('S2', 'Tipo de Vivienda');
$sheet2->getStyle('S2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('T2', 'Material de Vivienda');
$sheet2->getStyle('T2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('U2', 'N° Habitaciones');
$sheet2->getStyle('U2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('V2', 'N° Baños');
$sheet2->getStyle('V2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('W2', 'N° Cocina');
$sheet2->getStyle('W2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('X2', 'N° Logia');
$sheet2->getStyle('X2')->applyFromArray($cellStyleArray);

$sheet2->setCellValue('Y2', 'Condiciones de Habitalidad');
$sheet2->getStyle('Y2')->applyFromArray($cellStyleArray);


// Obtener los datos de cada sección
$sentenciaFamiliares = $conexion->prepare("SELECT gf.*, t.rut FROM grupo_familiar gf JOIN trabajador t ON gf.trabajador_id = t.id");
$sentenciaFamiliares->execute();
$lista_familiares = $sentenciaFamiliares->fetchAll(PDO::FETCH_ASSOC);

$sentenciaApoyoE = $conexion->prepare("SELECT ae.*, t.rut FROM apoyo_economico ae JOIN trabajador t ON ae.trabajador_id = t.id");
$sentenciaApoyoE->execute();
$lista_ApoyoE = $sentenciaApoyoE->fetchAll(PDO::FETCH_ASSOC);

$sentenciaEmprendimiento = $conexion->prepare("SELECT e.*, t.rut FROM emprendimiento e JOIN trabajador t ON e.trabajador_id = t.id");
$sentenciaEmprendimiento->execute();
$lista_Emprendimiento = $sentenciaEmprendimiento->fetchAll(PDO::FETCH_ASSOC);

$sentenciaMascotas = $conexion->prepare("SELECT m.*, t.rut FROM mascotas m JOIN trabajador t ON m.trabajador_id = t.id");
$sentenciaMascotas->execute();
$lista_Mascotas = $sentenciaMascotas->fetchAll(PDO::FETCH_ASSOC);

$sentenciaIngresos = $conexion->prepare("SELECT i.*, t.rut FROM ingresos i JOIN trabajador t ON i.trabajador_id = t.id");
$sentenciaIngresos->execute();
$lista_Ingresos = $sentenciaIngresos->fetchAll(PDO::FETCH_ASSOC);

$sentenciaEgresos = $conexion->prepare("SELECT e.*, t.rut FROM egresos e JOIN trabajador t ON e.trabajador_id = t.id");
$sentenciaEgresos->execute();
$lista_Egresos = $sentenciaEgresos->fetchAll(PDO::FETCH_ASSOC);

$sentenciaHabitalidad = $conexion->prepare("SELECT h.*, t.rut FROM condiciones_habitabilidad h JOIN trabajador t ON h.trabajador_id = t.id");
$sentenciaHabitalidad->execute();
$lista_Habitalidad = $sentenciaHabitalidad->fetchAll(PDO::FETCH_ASSOC);

// Convertir los arrays en arrays indexados por el RUT del trabajador
$familiares_por_rut = [];
foreach ($lista_familiares as $familiar) {
    $familiares_por_rut[$familiar['rut']][] = $familiar;
}

$apoyo_por_rut = [];
foreach ($lista_ApoyoE as $apoyo) {
    $apoyo_por_rut[$apoyo['rut']][] = $apoyo;
}

$emprendimiento_por_rut = [];
foreach ($lista_Emprendimiento as $emprendimiento) {
    $emprendimiento_por_rut[$emprendimiento['rut']][] = $emprendimiento;
}

$mascotas_por_rut = [];
foreach ($lista_Mascotas as $mascota) {
    $mascotas_por_rut[$mascota['rut']][] = $mascota;
}

$ingresos_por_rut = [];
foreach ($lista_Ingresos as $ingreso) {
    $ingresos_por_rut[$ingreso['rut']][] = $ingreso;
}

$egresos_por_rut = [];
foreach ($lista_Egresos as $egreso) {
    $egresos_por_rut[$egreso['rut']][] = $egreso;
}

$habitabilidad_por_rut = [];
foreach ($lista_Habitalidad as $habitabilidad) {
    $habitabilidad_por_rut[$habitabilidad['rut']][] = $habitabilidad;
}

// Recorrer los trabajadores y combinar datos de todas las secciones
$fila = 3; // Comienza en la fila 3
foreach ($lista_trabajadores as $trabajador) {
    $rut_trabajador = $trabajador['rut'];

    // Obtener los familiares, apoyo económico, emprendimiento, mascotas, ingresos y egresos del trabajador actual
    $familiares = isset($familiares_por_rut[$rut_trabajador]) ? $familiares_por_rut[$rut_trabajador] : [];
    $apoyos = isset($apoyo_por_rut[$rut_trabajador]) ? $apoyo_por_rut[$rut_trabajador] : [];
    $emprendimientos = isset($emprendimiento_por_rut[$rut_trabajador]) ? $emprendimiento_por_rut[$rut_trabajador] : [];
    $mascotas = isset($mascotas_por_rut[$rut_trabajador]) ? $mascotas_por_rut[$rut_trabajador] : [];
    $ingresos = isset($ingresos_por_rut[$rut_trabajador]) ? $ingresos_por_rut[$rut_trabajador] : [];
    $egresos = isset($egresos_por_rut[$rut_trabajador]) ? $egresos_por_rut[$rut_trabajador] : [];
    $habitabilidad = isset($habitabilidad_por_rut[$rut_trabajador]) ? $habitabilidad_por_rut[$rut_trabajador] : [];


    // Obtener el mayor número de filas necesarias entre todas las secciones
    $max_filas = max(count($familiares), count($apoyos), count($emprendimientos), count($mascotas), count($ingresos), count($egresos), 1);

    for ($i = 0; $i < $max_filas; $i++) {
        $sheet2->setCellValue('A' . $fila, $rut_trabajador); // Siempre el mismo rut

        // Llenar datos del grupo familiar
        if (isset($familiares[$i])) {
            $familiar = $familiares[$i];
            $sheet2->setCellValue('B' . $fila, $familiar['nombre_apellido']);
            $sheet2->setCellValue('C' . $fila, $familiar['parentesco']);
            $sheet2->setCellValue('D' . $fila, $familiar['fecha_nacimiento']);
            $sheet2->setCellValue('E' . $fila, $familiar['sexo']);
            $sheet2->setCellValue('F' . $fila, $familiar['estado_civil']);
            $sheet2->setCellValue('G' . $fila, $familiar['nivel_educacional']);
            $sheet2->setCellValue('H' . $fila, $familiar['actividad']);
        }

        // Llenar datos del apoyo económico
        if (isset($apoyos[$i])) {
            $apoyo = $apoyos[$i];
            $sheet2->setCellValue('I' . $fila, $apoyo['a_quien']);
            $sheet2->setCellValue('J' . $fila, $apoyo['motivo']);
        }

        // Llenar datos de emprendimiento
        if (isset($emprendimientos[$i])) {
            $emprendimiento = $emprendimientos[$i];
            $sheet2->setCellValue('K' . $fila, $emprendimiento['descripcion']);
        }

        // Llenar datos de mascotas
        if (isset($mascotas[$i])) {
            $mascota = $mascotas[$i];
            $sheet2->setCellValue('L' . $fila, $mascota['tipo_mascota']);
            $sheet2->setCellValue('M' . $fila, $mascota['cantidad']);
        }

        // Llenar datos de ingresos
        if (isset($ingresos[$i])) {
            $ingreso = $ingresos[$i];
            $sheet2->setCellValue('N' . $fila, $ingreso['nombre_persona']);
            $sheet2->setCellValue('O' . $fila, $ingreso['monto']);
        }

        // Llenar datos de egresos
        if (isset($egresos[$i])) {
            $egreso = $egresos[$i];
            $sheet2->setCellValue('P' . $fila, $egreso['descripcion']);
            $sheet2->setCellValue('Q' . $fila, $egreso['monto']);
            $sheet2->setCellValue('R' . $fila, $egreso['observaciones']);
        }

              // Llenar datos de condiciones de habitabilidad
              if (isset($habitabilidad[$i])) {
                $habitat = $habitabilidad[$i];
                $sheet2->setCellValue('S' . $fila, $habitat['tipo_vivienda']);
                $sheet2->setCellValue('T' . $fila, $habitat['material_vivienda']);
                $sheet2->setCellValue('U' . $fila, $habitat['num_habitaciones']);
                $sheet2->setCellValue('V' . $fila, $habitat['num_banos']);
                $sheet2->setCellValue('W' . $fila, $habitat['num_cocina']);
                $sheet2->setCellValue('X' . $fila, $habitat['num_logia']);
                $sheet2->setCellValue('Y' . $fila, $habitat['condiciones_habitabilidad']);
            }

        $fila++; // Avanza a la siguiente fila
    }
}


// Ajustar automáticamente el ancho de las columnas de la ficha social
foreach (range('A', 'Y') as $columna) {
    $sheet2->getColumnDimension($columna)->setAutoSize(true);
}




$sheet3 = $spreadsheet->createSheet(2); // Crea una segunda hoja
$sheet3->setTitle('declaracion de salud'); // Cambia el título de la hoja

$sheet3->setCellValue('A1', 'Declaración Salud'); // Título de la sección
$sheet3->mergeCells('A1:S1'); // Fusiona las celdas de A1 a H1
// Estilo para los encabezados de las columnas
$hStyleArray = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'], // Letra blanca
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Texto centrado
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'C71585'], // Fondo rosado oscuro
    ],
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => 'FFFFFF'], // Bordes blancos
        ],
    ],
];

// Aplicar el estilo a cada encabezado
$sheet3->setCellValue('A2', 'Rut');
$sheet3->getStyle('A2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('B2', 'Salud cancer');
$sheet3->getStyle('B2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('C2', 'Salud sistema nervioso');
$sheet3->getStyle('C2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('D2', 'Salud mental');
$sheet3->getStyle('D2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('E2', 'Salud ojo');
$sheet3->getStyle('E2')->applyFromArray($cellStyleArray);

$sheet3->setCellValue('F2', 'Salud naríz o oidos');
$sheet3->getStyle('F2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('G2', 'Salud respiratorio');
$sheet3->getStyle('G2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('H2', 'Salud corazón');
$sheet3->getStyle('H2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('I2', 'Salud vascular');
$sheet3->getStyle('I2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('J2', 'Salud metabólico');
$sheet3->getStyle('J2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('K2', 'Salud digestivo');
$sheet3->getStyle('K2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('L2', 'Salud hepatitis o sida');
$sheet3->getStyle('L2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('M2', 'Salud renal');
$sheet3->getStyle('M2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('N2', 'Salud reproductor femenino');
$sheet3->getStyle('N2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('O2', 'Salud autoimune');
$sheet3->getStyle('O2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('P2', 'Salud tiroides');
$sheet3->getStyle('P2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('Q2', 'Salud esquelético');
$sheet3->getStyle('Q2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('R2', 'Salud congénito');
$sheet3->getStyle('R2')->applyFromArray($hStyleArray);

$sheet3->setCellValue('S2', 'Salud embarazo');
$sheet3->getStyle('S2')->applyFromArray($hStyleArray);

$sentenciaDS = $conexion->prepare("SELECT ds.*, t.rut FROM declaracion_salud ds JOIN trabajador t ON ds.trabajador_id = t.id");
$sentenciaDS->execute();
$lista_DSalud = $sentenciaDS->fetchAll(PDO::FETCH_ASSOC);

// Llenar los datos en la hoja de cálculo
$fila = 3; // Comienza en la fila 2 porque la primera fila tiene los encabezados
foreach ($lista_DSalud as $DSalud) {
    $sheet3->setCellValue('A' . $fila, $DSalud['rut']);
    $sheet3->setCellValue('B' . $fila, $DSalud['salud_cancer']);
    $sheet3->setCellValue('C' . $fila, $DSalud['salud_sistema_nervioso']);
    $sheet3->setCellValue('D' . $fila, $DSalud['salud_salud_mental']);
    $sheet3->setCellValue('E' . $fila, $DSalud['salud_ojo']);
    $sheet3->setCellValue('F' . $fila, $DSalud['salud_nariz_oidos']);
    $sheet3->setCellValue('G' . $fila, $DSalud['salud_respiratorio']);
    $sheet3->setCellValue('H' . $fila, $DSalud['salud_corazon']);
    $sheet3->setCellValue('I' . $fila, $DSalud['salud_vascular']);
    $sheet3->setCellValue('J' . $fila, $DSalud['salud_metabolico']);
    $sheet3->setCellValue('K' . $fila, $DSalud['salud_digestivo']);
    $sheet3->setCellValue('L' . $fila, $DSalud['salud_hepatitis_sida']);
    $sheet3->setCellValue('M' . $fila, $DSalud['salud_renal']);
    $sheet3->setCellValue('N' . $fila, $DSalud['salud_reproductor_femenino']);
    $sheet3->setCellValue('O' . $fila, $DSalud['salud_autoinmune']);
    $sheet3->setCellValue('P' . $fila, $DSalud['salud_tiroides']);
    $sheet3->setCellValue('Q' . $fila, $DSalud['salud_esqueletico']);
    $sheet3->setCellValue('R' . $fila, $DSalud['salud_congenito']);
    $sheet3->setCellValue('S' . $fila, $DSalud['salud_embarazo']);
    $fila++;
}

// Ajustar automáticamente el ancho de las columnas
foreach (range('A', 'S') as $columna) {
    $sheet3->getColumnDimension($columna)->setAutoSize(true);
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
