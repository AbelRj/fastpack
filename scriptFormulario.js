//2. Grupo Familiar
// Función para agregar una nueva fila al grupo familiar
function agregarFilaGF() {
    var table = document.getElementById("grupo_familiar");
    table.style.display = "table"; // Mostrar la tabla si está oculta

    var tbody = table.getElementsByTagName('tbody')[0];
    var newRow = tbody.insertRow(); // Insertar nueva fila

    // Agregar las celdas con los inputs correspondientes
    newRow.innerHTML = `
        <td><input type="text" name="nombre_apellido_familiar[]"></td>
        <td><input type="text" name="parentesco[]"></td>
        <td><input type="date" name="fecha_nacimiento_familiar[]"></td>
        <td><input type="text" name="sexo_familiar[]"></td>
        <td><input type="text" name="estado_civil_familiar[]"></td>
        <td><input type="text" name="nivel_educacional[]"></td>
        <td><input type="text" name="actividad_familiar[]"></td>
        <td>
        <button type="button" onclick="ocultarFilaGP(this)">Eliminar</button>
        <input type="hidden" name="id_familiar[]" value="new"> <!-- Agregar un valor especial -->
        </td>
    `;

    // Ocultar el mensaje de "No hay miembros familiares registrados"
    var noMiembrosMsg = document.getElementById("no-miembros-msg");
    if (noMiembrosMsg) {
        noMiembrosMsg.style.display = "none";
    }
}

// Función para ocultar una fila y agregar el name "eliminar_familiar[]"
function ocultarFilaGP(button) {
    const row = button.closest('tr');
    const idFamiliar = row.querySelector('input[name="id_familiar[]"]').value;

    if (idFamiliar === "new") {
        // Si es una fila nueva, eliminarla del DOM
        row.remove();
    } else {
        // Ocultar la fila y agregar un input hidden para marcarla como eliminada
        row.style.display = 'none';

        const hiddenInputEliminar = document.createElement('input');
        hiddenInputEliminar.type = "hidden";
        hiddenInputEliminar.name = "eliminar_familiar[]";
        hiddenInputEliminar.value = idFamiliar;

        row.appendChild(hiddenInputEliminar);
    }

    // Verificar si todas las filas están ocultas
    var table = document.getElementById("grupo_familiar");
    var tbody = table.getElementsByTagName('tbody')[0];
    var rows = tbody.getElementsByTagName('tr');
    var allHidden = true;

    for (var i = 0; i < rows.length; i++) {
        if (rows[i].style.display !== 'none') {
            allHidden = false;
            break;
        }
    }

    if (allHidden) {
        table.style.display = "none";
        var noMiembrosMsg = document.getElementById("no-miembros-msg");
        if (noMiembrosMsg) {
            noMiembrosMsg.style.display = "block";
        }
    }
}
/*---------------------------------------------------------------------------------------------------*/ 
//5. ¿Apoya a algún familiar económicamente? 
// Función para manejar el cambio en los radio buttons
function handleRadioChange(radio) {
    var currentValue = radio.value;
    var contenedor = document.getElementById('contenedor_apoyo_economico');
    var isCurrentlyDisplayed = contenedor.style.display === 'block';

    // Solo ejecutar la función si hay un cambio en la selección
    if ((currentValue === 'si' && !isCurrentlyDisplayed) || (currentValue === 'no' && isCurrentlyDisplayed)) {
        mostrarTablaApoyo(currentValue === 'si');
    }
}
// Función para mostrar/ocultar la tabla de apoyo económico
function mostrarTablaApoyo(mostrar) {
    var contenedor = document.getElementById('contenedor_apoyo_economico');
    var tablaAPF = document.getElementById('apoyo_economico').getElementsByTagName('tbody')[0];

    if (mostrar) {
        contenedor.style.display = 'block'; // Mostrar la tabla
        Array.from(tablaAPF.rows).forEach(row => {
            if (row.style.display === 'none') {
                row.style.display = ''; // Hacer visible la fila
            }
        });
        if (tablaAPF.rows.length === 0) {
            agregarFilaAPF('apoyo_economico');
        }
    } else {
        contenedor.style.display = 'none'; // Ocultar la tabla
        Array.from(tablaAPF.rows).forEach(row => {
            // Marca las filas para eliminación
            var hiddenInput = row.querySelector('input[type="hidden"]');
            if (hiddenInput) {
                hiddenInput.name = 'eliminar_apoyoF[]';
            }
        });
    }
}
// Función para agregar una fila a una tabla específica
function agregarFilaAPF(tablaId) {
    var tablaAPF = document.getElementById(tablaId).getElementsByTagName('tbody')[0];
    var nuevaFila = tablaAPF.insertRow();

    nuevaFila.innerHTML = `
          <td><input type="text" name="a_quien_apoya[]"></td>
                    <td><input type="text" name="motivo_apoyo[]"></td>
                    <td>
                    <button type="button" onclick="eliminarFila(this)">Eliminar</button>
                    <input type="hidden" >
                    </td>

        </td>
    `
}
// Función para eliminar la fila
function eliminarFilaAPF(button) {
    const row = button.closest('tr');
    const tablaAPF = document.getElementById('apoyo_economico').getElementsByTagName('tbody')[0];
    const radioNo = document.querySelector('input[name="apoyo_economico"][value="no"]');
    row.style.display = 'none'; // Oculta la fila visualmente
    const hiddenInput = row.querySelector('input[type="hidden"]');
    hiddenInput.name = "eliminar_apoyoF[]"; // Cambia el nombre para que se envíe en el formulario POST


    // Ocultar la fila visualmente
    row.style.display = 'none';

    // Verificar si ya no quedan filas visibles en la tabla
    let visibleRows = Array.from(tablaAPF.rows).filter(row => row.style.display !== 'none');

    if (visibleRows.length === 0) {
        // Si no hay filas visibles, marcar el radio "No"
        radioNo.checked = true;
        mostrarTablaApoyo(false); // Ocultar la tabla
    }
}
/*-------------------------------------------------------------------------------------------------------*/
//Emprendimiento
// Función para manejar el cambio en los radio buttons
function handleRadioChangeE(radio) {
    var currentValue = radio.value;
    var contenedor = document.getElementById('contenedor_emprendimiento');
    var isCurrentlyDisplayed = contenedor.style.display === 'block';

    // Solo ejecutar la función si hay un cambio en la selección
    if ((currentValue === 'si' && !isCurrentlyDisplayed) || (currentValue === 'no' && isCurrentlyDisplayed)) {
        mostrarTablaEmprendimiento(currentValue === 'si');
    }
}

// Función para mostrar/ocultar el contenedor del emprendimiento
function mostrarTablaEmprendimiento(mostrar) {
    var contenedor = document.getElementById('contenedor_emprendimiento');
    if (mostrar) {
        contenedor.style.display = 'block'; // Mostrar el campo
    } else {
        contenedor.style.display = 'none'; // Ocultar el campo
    }
}
//mascotas
function agregarFilaM() {
    var table = document.getElementById("mascotas").getElementsByTagName('tbody')[0];
    var newRow = table.insertRow(); // Insertar nueva fila
    newRow.innerHTML = `
        <td><input type="text" name="tipo_mascota[]"></td>
        <td><input type="number" name="cantidad_mascota[]"></td>
        <td>
            <button type="button" onclick="eliminarFilaM(this)">Eliminar</button>
            <input type="hidden" name="id_mascota[]">
        </td>
    `;
}

function handleRadioChangeM(radio) {
    var contenedor = document.getElementById('contenedor_mascotas');
    var mostrar = radio.value === 'si';
    mostrarTablaMascotas(mostrar);
}

function mostrarTablaMascotas(mostrar) {
    var contenedor = document.getElementById('contenedor_mascotas');
    var tablaMascotas = document.getElementById('mascotas').getElementsByTagName('tbody')[0];
    

    if (mostrar) {
        contenedor.style.display = 'block';
        if (tablaMascotas.rows.length === 0) {
            agregarFilaM();
        }
    } else {
        contenedor.style.display = 'none';
        Array.from(tablaMascotas.rows).forEach(row => {
            var hiddenInput = row.querySelector('input[type="hidden"]');
            hiddenInput.name = 'eliminar_mascota[]'; // Marcar para eliminación
        });
    }
}

function eliminarFilaM(button) {
    var row = button.closest('tr');
    row.style.display = 'none';
    var hiddenInput = row.querySelector('input[type="hidden"]');
    hiddenInput.name = "eliminar_mascota[]"; // Marcar para eliminación
}

//Situacion economica directa

window.onload = function() {
    calcularTotal(); // Llama a la función para calcular el total al cargar la página
    calcularTotalEgresos();
};
function agregarFilaI(tablaId) {
    var table = document.getElementById(tablaId).getElementsByTagName('tbody')[0];
    var newRow = table.insertRow();
    var tabla = document.getElementById(tablaId);

    tabla.style.display = 'table'; // Mostrar la tabla si está oculta
    newRow.innerHTML = `
        <td><input type="text" name="nombre_ingreso[]"></td>
        <td><input type="number" name="monto_ingreso[]" class="monto_ingreso" oninput="calcularTotal()"></td>
        <td><button type="button" onclick="eliminarFilaI(this)">Eliminar</button>
        <input type="hidden" value="new"></td>
    `;

    // Ocultar el mensaje de "No hay ingresos registrados"
    var noIngresosMsg = document.getElementById("no-ingresos-msg");
    noIngresosMsg.style.display = "none";
}
function eliminarFilaI(button) {
    const row = button.closest('tr');
    const hiddenInput = row.querySelector('input[type="hidden"]');

    if (!hiddenInput || hiddenInput.value === "new") {
        row.remove(); // Eliminar fila si es nueva
    } else {
        row.style.display = 'none'; // Ocultar fila visualmente si ya tiene ID
        hiddenInput.name = "eliminar_ingreso[]";
    }

    calcularTotal(); // Recalcular el total

    // Verificar si todas las filas están ocultas o eliminadas
    var tabla = document.getElementById("ingresos_familiares");
    var tbody = tabla.getElementsByTagName('tbody')[0];
    var rows = tbody.getElementsByTagName('tr');
    var allHidden = true;

    for (var i = 0; i < rows.length; i++) {
        if (rows[i].style.display !== 'none') {
            allHidden = false;
            break;
        }
    }

    if (allHidden) {
        tabla.style.display = "none"; // Ocultar tabla
        var noIngresosMsg = document.getElementById("no-ingresos-msg");
        noIngresosMsg.style.display = "block"; // Mostrar mensaje de "No hay ingresos registrados"
    }
}
function mostrarTablaIngresos() {
    var tabla = document.getElementById('ingresos_familiares');
    tabla.style.display = 'block';
}

function calcularTotal() {
    var inputs = document.getElementsByClassName('monto_ingreso');
    var total = 0;

    for (var i = 0; i < inputs.length; i++) {
        var fila = inputs[i].closest('tr');
        // Si la fila está oculta, no sumar su monto
        if (fila.style.display !== 'none') {
            var value = parseFloat(inputs[i].value) || 0;
            total += value;
        }
    }

    document.getElementById('total_ingreso_familiar').value = total;
}

//egresos importantes

function agregarFilaE(tablaId) {
    var table = document.getElementById(tablaId).getElementsByTagName('tbody')[0];
    var newRow = table.insertRow();
    var tabla = document.getElementById(tablaId);

    tabla.style.display = 'table'; // Mostrar la tabla si está oculta
    newRow.innerHTML = `
        <td><input type="text" name="descripcion_egreso[]"></td>
        <td><input type="number" name="monto_egreso[]" class="monto_egreso" oninput="calcularTotalEgresos()"></td>
        <td><input type="text" name="observacion_egreso[]"></td>
        <td><button type="button" onclick="eliminarFilaE(this)">Eliminar</button><input type="hidden" value="new"></td>
    `;

    // Ocultar el mensaje de "No hay egresos registrados"
    var noEgresosMsg = document.getElementById("no-egresos-msg");
    noEgresosMsg.style.display = "none";
}

function eliminarFilaE(button) {
    const row = button.closest('tr');
    const hiddenInput = row.querySelector('input[type="hidden"]');

    if (!hiddenInput || hiddenInput.value === "new") {
        row.remove(); // Eliminar fila si es nueva
    } else {
        row.style.display = 'none'; // Ocultar fila visualmente si ya tiene ID
        hiddenInput.name = "eliminar_egreso[]";
    }

    calcularTotalEgresos(); // Recalcular el total

    // Verificar si todas las filas están ocultas o eliminadas
    var tabla = document.getElementById("egresos_importantes");
    var tbody = tabla.getElementsByTagName('tbody')[0];
    var rows = tbody.getElementsByTagName('tr');
    var allHidden = true;

    for (var i = 0; i < rows.length; i++) {
        if (rows[i].style.display !== 'none') {
            allHidden = false;
            break;
        }
    }

    if (allHidden) {
        tabla.style.display = "none"; // Ocultar tabla
        var noEgresosMsg = document.getElementById("no-egresos-msg");
        noEgresosMsg.style.display = "block"; // Mostrar mensaje de "No hay egresos registrados"
    }
}

function calcularTotalEgresos() {
    var inputs = document.getElementsByClassName('monto_egreso');
    var total = 0;

    for (var i = 0; i < inputs.length; i++) {
        var fila = inputs[i].closest('tr');
        // Si la fila está oculta, no sumar su monto
        if (fila.style.display !== 'none') {
            var value = parseFloat(inputs[i].value) || 0;
            total += value;
        }
    }

    document.getElementById('total_egresos').value = total;
}

//15. Detalles Adicionales de Salud de Usted o su Grupo Familiar
// Función para agregar una nueva fila al detalle de enfermedades
function agregarFilaEA() {
    var table = document.getElementById("enfermedades_adicionales");
    table.style.display = "table"; // Mostrar la tabla si está oculta

    var tbody = table.getElementsByTagName('tbody')[0];
    var newRow = tbody.insertRow(); // Insertar nueva fila

    // Agregar las celdas con los inputs correspondientes
    newRow.innerHTML = `
        <td><input type="text" name="nombre_persona[]"></td>
        <td><input type="text" name="enfermedad[]"></td>
        <td><input type="date" name="fecha_diagnostico[]"></td>
        <td><input type="text" name="estado_actual[]"></td>
        <td><button type="button" onclick="eliminarFila(this)">Eliminar</button>
            <input type="hidden" name="id_enfermedad[]" value="new"> <!-- Agregar un valor especial -->
        </td>
    `;

    // Ocultar el mensaje de "No hay enfermedades registradas"
    var noEnfermedadesMsg = document.getElementById("no-enfermedades-msg");
    if (noEnfermedadesMsg) {
        noEnfermedadesMsg.style.display = "none";
    }
}

// Función para eliminar una fila
function eliminarFila(button) {
    const row = button.closest('tr');
    const idEnfermedad = row.querySelector('input[name="id_enfermedad[]"]').value;

    if (idEnfermedad === "new") {
        // Si es una fila nueva, eliminarla del DOM
        row.remove();
    } else {
        // Ocultar la fila y agregar un input hidden para marcarla como eliminada
        row.style.display = 'none';

        const hiddenInputEliminar = document.createElement('input');
        hiddenInputEliminar.type = "hidden";
        hiddenInputEliminar.name = "eliminar_enfermedad[]";
        hiddenInputEliminar.value = idEnfermedad;

        row.appendChild(hiddenInputEliminar);
    }

    // Verificar si todas las filas están ocultas
    var table = document.getElementById("enfermedades_adicionales");
    var tbody = table.getElementsByTagName('tbody')[0];
    var rows = tbody.getElementsByTagName('tr');
    var allHidden = true;

    for (var i = 0; i < rows.length; i++) {
        if (rows[i].style.display !== 'none') {
            allHidden = false;
            break;
        }
    }

    if (allHidden) {
        table.style.display = "none";
        var noEnfermedadesMsg = document.getElementById("no-enfermedades-msg");
        if (noEnfermedadesMsg) {
            noEnfermedadesMsg.style.display = "block";
        }
    }
}
