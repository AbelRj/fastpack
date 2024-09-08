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

//3. Nivel Educacional Familiar
        // Función para agregar una fila a una tabla específica
        function agregarFilaNEF(tablaId) {
            var tablaNEF = document.getElementById(tablaId);
            var nuevaFila = tablaNEF.insertRow();
            var columnas = tablaNEF.rows[0].cells.length;
    
            // Agregar inputs a la nueva fila
            for (var i = 0; i < columnas - 1; i++) {
                var nuevaCelda = nuevaFila.insertCell(i);
                nuevaCelda.innerHTML = `<input type="text" name="${tablaId}_dato_${tablaNEF.rows.length}">`;
            }
    
            // Agregar botón de eliminación en la última celda
            var btnEliminar = nuevaFila.insertCell(columnas - 1);
            btnEliminar.innerHTML = '<button type="button" onclick="eliminarFila(this)">Eliminar</button>';
        }
    
        // Función para eliminar la fila
        function eliminarFila(boton) {
            var btnEliminar = boton.parentNode.parentNode;
            btnEliminar.parentNode.removeChild(btnEliminar);
        }

 //5. ¿Apoya a algún familiar económicamente? 
        // Función para agregar una fila a una tabla específica
        function agregarFilaAPF(tablaId) {
            var tablaAPF = document.getElementById(tablaId);
            var nuevaFila = tablaAPF.insertRow();
            var columnas = tablaAPF.rows[0].cells.length;
    
            // Agregar inputs a la nueva fila
            for (var i = 0; i < columnas - 1; i++) {
                var nuevaCelda = nuevaFila.insertCell(i);
                nuevaCelda.innerHTML = `<input type="text" name="${tablaId}_dato_${tablaAPF.rows.length}">`;
            }
    
            // Agregar botón de eliminación en la última celda
            var btnEliminar = nuevaFila.insertCell(columnas - 1);
            btnEliminar.innerHTML = '<button type="button" onclick="eliminarFila(this)">Eliminar</button>';
        }
    
        // Función para eliminar la fila
        function eliminarFila(boton) {
            var btnEliminar = boton.parentNode.parentNode;
            btnEliminar.parentNode.removeChild(btnEliminar);
        }

         //Situacion economica directa
    function agregarFilaSEDirecta(tablaId) {
        var table = document.getElementById(tablaId);
        var newRow = table.insertRow();
        var cols = table.rows[0].cells.length;

        for (var i = 0; i < cols - 1; i++) {
            var newCell = newRow.insertCell(i);
            if (i === 1) {
                newCell.innerHTML = `<input type="number" name="monto_ingreso_${table.rows.length}" class="monto_ingreso" oninput="calcularTotal()">`;
            } else {
                newCell.innerHTML = `<input type="text" name="dato_${table.rows.length}">`;
            }
        }

        var deleteCell = newRow.insertCell(cols - 1);
        deleteCell.innerHTML = '<button type="button" onclick="eliminarFila(this)">Eliminar</button>';
    }

    function eliminarFila(boton) {
        var row = boton.parentNode.parentNode;
        row.parentNode.removeChild(row);
        calcularTotal(); // Recalcular total al eliminar una fila
    }

    function calcularTotal() {
        var inputs = document.getElementsByClassName('monto_ingreso');
        var total = 0;

        for (var i = 0; i < inputs.length; i++) {
            var value = parseFloat(inputs[i].value) || 0; // Convierte a número o usa 0
            total += value;
        }

        document.getElementById('total_ingreso_familiar').value = total;
    }

    //egresos importantes
    function agregarFilaEI(tablaId) {
        var table = document.getElementById(tablaId);
        var newRow = table.insertRow();
        var cols = table.rows[0].cells.length;

        for (var i = 0; i < cols - 1; i++) {
            var newCell = newRow.insertCell(i);
            if (i === 1) {
                newCell.innerHTML = `<input type="number" name="monto_egreso_${table.rows.length}" class="monto_egreso" oninput="calcularTotalEgresos()">`;
            } else {
                newCell.innerHTML = `<input type="text" name="dato_${table.rows.length}">`;
            }
        }

        var deleteCell = newRow.insertCell(cols - 1);
        deleteCell.innerHTML = '<button type="button" onclick="eliminarFila(this)">Eliminar</button>';
    }

    function eliminarFila(boton) {
        var row = boton.parentNode.parentNode;
        row.parentNode.removeChild(row);
        calcularTotalEgresos(); // Recalcular total al eliminar una fila
    }

    function calcularTotalEgresos() {
        var inputs = document.getElementsByClassName('monto_egreso');
        var total = 0;

        for (var i = 0; i < inputs.length; i++) {
            var value = parseFloat(inputs[i].value) || 0; // Convierte a número o usa 0
            total += value;
        }

        document.getElementById('total_egresos').value = total;
    }

    //15. Detalles Adicionales de Salud de Usted o su Grupo Familiar
        // Función para agregar una fila a una tabla específica
        function agregarFilaDASF(tablaId) {
            var tablaDASF = document.getElementById(tablaId);
            var nuevaFila = tablaDASF.insertRow();
            var columnas = tablaDASF.rows[0].cells.length;
    
            // Agregar inputs a la nueva fila
            for (var i = 0; i < columnas - 1; i++) {
                var nuevaCelda = nuevaFila.insertCell(i);
                nuevaCelda.innerHTML = `<input type="text" name="${tablaId}_dato_${tablaDASF.rows.length}">`;
            }
    
            // Agregar botón de eliminación en la última celda
            var btnEliminar = nuevaFila.insertCell(columnas - 1);
            btnEliminar.innerHTML = '<button type="button" onclick="eliminarFila(this)">Eliminar</button>';
        }
    
        // Función para eliminar la fila
        function eliminarFila(boton) {
            var btnEliminar = boton.parentNode.parentNode;
            btnEliminar.parentNode.removeChild(btnEliminar);
        }

            //boton editar
    document.getElementById('enableButton').addEventListener('click', function () {
        var inputs = document.querySelectorAll('input[readonly]'); // Selecciona todos los inputs con readonly
        inputs.forEach(function (input) {
            input.readOnly = false; // Habilita la edición de los inputs
        });
    });