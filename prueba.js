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
                    <button type="button" onclick="eliminarFilaAPF(this)">Eliminar</button>
                    <input type="hidden" value="new">
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

    if (!hiddenInput || hiddenInput.value === "new") {
        row.remove(); // Eliminar fila si es nueva
    } else {
        row.style.display = 'none'; // Ocultar fila visualmente si ya tiene ID
    hiddenInput.name = "eliminar_apoyoF[]"; // Cambia el nombre para que se envíe en el formulario POST
    }


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