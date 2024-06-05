/* window.addEventListener('scroll', function () {
  var btnVolverArriba = document.querySelector('.btn-volver-arriba');
  if (window.scrollY > 200) {
    btnVolverArriba.classList.add('show-btn-volver-arriba');
  } else {
    btnVolverArriba.classList.remove('show-btn-volver-arriba');
  }
}); */

function confirmarAccion(accion, id) {
  var mensaje = '';

  if (accion === 'aceptar') {
    mensaje = '¿Estás seguro de que deseas aceptar esta solicitud?';
  } else if (accion === 'rechazar') {
    mensaje = '¿Estás seguro de que deseas rechazar esta solicitud?';
  }

  if (confirm(mensaje)) {
    // Si el usuario confirma, redirecciona al controlador con la acción y el id
    window.location.href = 'index.php?action=' + accion + '&id=' + id;
  }
}

function confirmarAccionLicencia(accion, id) {
  var mensaje = '';

  if (accion === 'aprobarLicencia') {
    mensaje = '¿Estás seguro de que deseas aceptar esta solicitud?';
  } else if (accion === 'rechazarLicencia') {
    mensaje = '¿Estás seguro de que deseas rechazar esta solicitud?';
  }

  if (confirm(mensaje)) {
    // Si el usuario confirma, redirecciona al controlador con la acción y el id
    window.location.href = 'index.php?action=' + accion + '&id=' + id;
  }
}
