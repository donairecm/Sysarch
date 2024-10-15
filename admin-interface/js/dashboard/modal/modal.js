// Function to show a modal with animation
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'flex'; // Display the modal (flex centers it)
    
    // Small timeout to allow display setting before animation starts
    setTimeout(() => {
        modal.classList.add('open'); // Trigger the "open" animation (growing)
    }, 10); // Minimal delay to ensure CSS transition applies
}

// Function to close a modal with animation
function closeModal(modal) {
    modal.classList.remove('open'); // Remove the "open" class to stop the animation
    modal.classList.add('close');   // Add "close" class to trigger the shrinking animation

    // Wait for the animation to finish before fully hiding the modal (300ms)
    setTimeout(() => {
        modal.classList.remove('close'); // Remove the "close" class
        modal.style.display = 'none';    // Fully hide the modal after animation ends
    }, 300); // Duration of the animation (matching the CSS transition)
}

// Attach click events to dashboard items to open modals
document.querySelectorAll('.dashboard-item').forEach(item => {
    item.addEventListener('click', () => {
        const modalId = item.getAttribute('data-modal');
        showModal(modalId);
    });
});

// Attach close events to modals (click on SVG or outside the modal to close)
document.querySelectorAll('.modal').forEach(modal => {
    const closeButton = modal.querySelector('.close-btn');
    closeButton.addEventListener('click', () => closeModal(modal));

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal(modal);
        }
    });
});


