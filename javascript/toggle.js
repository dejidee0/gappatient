// test.js
function toggleMenu() {
    var navMenu = document.getElementById("nav-menu");
    var card = document.querySelector('.top-white-card');
    navMenu.classList.toggle("nav-menu-visible");

    if (navMenu.classList.contains("nav-menu-visible")) {
        // Menu is now open, adjust the top margin of the card
        var navHeight = navMenu.offsetHeight; // Get the height of the nav menu
        card.style.marginTop = (navHeight + 60) + 'px'; // Adjust 60px to your fixed header's height
    } else {
        // Menu is closed, reset the top margin of the card
        card.style.marginTop = '60px'; // Adjust 60px to your fixed header's height
    }
}