document.addEventListener('DOMContentLoaded', () => {
  const menuToggle = document.querySelector('.menu-toggle');
  const menu = document.querySelector('nav ul.menu');
  const menuItems = document.querySelectorAll('nav ul.menu > li');

  // Mostrar/Ocultar menú principal en mobile
  menuToggle.addEventListener('click', () => {
    menu.classList.toggle('active');
  });

  // Mostrar/Ocultar submenús al pasar el mouse por encima (mouseenter)
  menuItems.forEach((item) => {
    const submenu = item.querySelector('ul'); // Verificar si hay un submenú

    if (submenu) {
      // Mostrar submenú al pasar el mouse por encima
      item.addEventListener('mouseenter', () => {
        submenu.classList.add('active'); // Mostrar submenú
      });

      // Ocultar submenú inmediatamente cuando el mouse sale del área del item
      item.addEventListener('mouseleave', () => {
        submenu.classList.remove('active'); // Ocultar submenú sin retraso
      });
    }
  });

  // Si el mouse sale de todo el menú, cerramos todos los submenús inmediatamente
  menu.addEventListener('mouseleave', () => {
    menuItems.forEach(item => {
      const submenu = item.querySelector('ul');
      if (submenu) {
        submenu.classList.remove('active'); // Cierra todos los submenús inmediatamente
      }
    });
  });
});
