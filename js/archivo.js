window.addEventListener('scroll', function () {
  var btnVolverArriba = document.querySelector('.btn-volver-arriba');
  if (window.scrollY > 200) {
    btnVolverArriba.classList.add('show-btn-volver-arriba');
  } else {
    btnVolverArriba.classList.remove('show-btn-volver-arriba');
  }
});
