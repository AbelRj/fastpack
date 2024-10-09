$(document).ready(function() {
    $('#table_id').DataTable({
        "scrollX": true,  // Habilitar el desplazamiento horizontal
        "autoWidth": false,  // Evitar que las columnas se ajusten automáticamente
        "responsive": false, // Deshabilitar el comportamiento responsive
        "pageLength": 5,   // Mostrar 5 filas por página
        "lengthMenu": [5, 10, 20, 30],  // Opciones de filas por página
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json",  // Archivo de traducción en español
            "search": "Buscar:",  // Texto de búsqueda
            "lengthMenu": "Mostrar _MENU_ registros",  // Texto de selección de filas
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "zeroRecords": "No se encontraron resultados",
            "infoEmpty": "Mostrando 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros en total)"
        }
    });
});