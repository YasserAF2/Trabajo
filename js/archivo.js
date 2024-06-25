/* document.addEventListener('DOMContentLoaded', function () {
  // Verificar el ancho de la pantalla
  if (window.innerWidth < 480) {
    console.log('DOM completamente cargado.');

    // Obtener la altura del viewport
    var viewportHeight = window.innerHeight;
    console.log('Altura del viewport:', viewportHeight);

    // Obtener la altura de otros elementos en la pantalla y restarlas
    var headerHeight = document.getElementById('header').offsetHeight;
    var footerHeight = document.getElementById('footer').offsetHeight;

    // Calcular la altura disponible para el div1
    var availableHeight = viewportHeight - headerHeight - footerHeight;
    console.log('Altura disponible para div1:', availableHeight);

    // Obtener el elemento div1
    var div1 = document.getElementById('div1');

    // Verificar si div1 existe antes de establecer la altura
    if (div1) {
      div1.style.height = availableHeight + 'px';
      console.log('Se estableció la altura de div1:', div1.style.height);
    } else {
      console.log('El elemento div1 no se encontró en el DOM.');
    }
  } else {
    console.log('La pantalla es igual o menor a 480px. No se realizaron cambios.');
  }
});
 */