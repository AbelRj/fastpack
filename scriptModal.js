
  document.addEventListener('DOMContentLoaded', (event) => {
    const form = document.getElementById('form'); // Cambia 'yourFormId' por el id real de tu formulario
    const modal = new bootstrap.Modal(document.getElementById('exampleModal'));
    const confirmButton = document.getElementById('confirmButton');

    form.addEventListener('submit', function (event) {
      event.preventDefault(); // Evita el envío del formulario

      // Muestra el modal
      modal.show();
    });

    confirmButton.addEventListener('click', function () {
      form.submit(); // Envía el formulario
    
    });
  });