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
