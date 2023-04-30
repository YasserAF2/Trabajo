$(document).ready(function () {
  console.log('El documento se ha cargado correctamente.');
  $('.opciones-lista').click(function (event) {
    console.log('Se ha hecho clic en un elemento de la lista.');
    event.preventDefault();
    var url = $(this).attr('href');
    cargarVistaDinamica(url);
  });
});

function cargarVistaDinamica(url) {
  console.log('Cargando la vista dinámica desde la URL: ' + url);
  $.ajax({
    url: url,
    type: 'GET',
    success: function (respuesta) {
      console.log('La vista dinámica se ha cargado correctamente.');
      $('#vista-dinamica').html(respuesta);
    },
    error: function (xhr, status, error) {
      console.log(
        'Se ha producido un error al cargar la vista dinámica: ' + error
      );
    },
  });
}

window.addEventListener('scroll', function () {
  var btnVolverArriba = document.querySelector('.btn-volver-arriba');
  if (window.scrollY > 200) {
    btnVolverArriba.classList.add('show-btn-volver-arriba');
  } else {
    btnVolverArriba.classList.remove('show-btn-volver-arriba');
  }
});
