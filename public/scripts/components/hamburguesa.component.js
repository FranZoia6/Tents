class Hamburguesa {

    constructor() {
  
      const menuBtn = document.querySelector('.hamburguesa');
      const menu = document.querySelector('.menu');
      menuBtn.addEventListener('click', function() {
        menu.classList.toggle('show');
      });
  
    }
    
}
  