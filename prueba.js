//2. Grupo Familiar
// Función para agregar una nueva fila al grupo familiar
function agregarFilaGF() {
    var table = document.getElementById("grupo_familiar").getElementsByTagName('tbody')[0];
    var newRow = table.insertRow(); // Insertar nueva fila

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
        <button type="button" onclick="eliminarFila(this)">Eliminar</button>
        <input type="hidden" >

        </td>
    `;
}
// Función para eliminar una fila
function eliminarFilaGP(button) {
    const row = button.closest('tr');
    row.style.display = 'none'; // Oculta la fila visualmente
    const hiddenInput = row.querySelector('input[type="hidden"]');
    hiddenInput.name = "eliminar_familiar[]"; // Cambia el nombre para que se envíe en el formulario POST

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