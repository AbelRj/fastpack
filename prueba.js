
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
