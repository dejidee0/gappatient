function toggleInfo(button) {
    var serviceCard = button.closest('.service-card');
    var infoCard = serviceCard.querySelector('.service-more-info');

    // Check the current state and toggle accordingly
    if (infoCard.style.display === 'none' || !infoCard.style.display) {
        // First, hide all open info cards
        var allInfoCards = document.querySelectorAll('.service-more-info');
        allInfoCards.forEach(function(card) {
            card.style.display = 'none';
        });

        // Then, show the clicked one
        infoCard.style.display = 'block';
    } else {
        // If it's already showing, hide it
        infoCard.style.display = 'none';
    }
}