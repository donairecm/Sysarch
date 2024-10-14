// Get the modal and close button elements for the Units Sold modal
const unitsSoldModal = document.getElementById('unitsSoldModal');
const unitsSoldCloseButton = document.getElementById('unitsSoldCloseButton'); // Updated to target the SVG directly

// Function to show the modal with animation
document.querySelector('.units-sold').addEventListener('click', () => {
    unitsSoldModal.style.display = 'block'; // Set display block first to show modal
    setTimeout(() => {
        unitsSoldModal.classList.add('show'); // Add the 'show' class to trigger the CSS transitions
    }, 10); // Small delay to ensure the display block is applied before animation
});

// Function to hide the modal with animation
unitsSoldCloseButton.addEventListener('click', () => {
    unitsSoldModal.classList.remove('show'); // Remove 'show' class to trigger closing animation
    setTimeout(() => {
        unitsSoldModal.style.display = 'none'; // Hide modal after the transition ends
    }, 300); // Wait for the animation to finish (300ms matches CSS transition duration)
});

// Close the modal if the user clicks outside of the modal content
window.addEventListener('click', (event) => {
    if (event.target === unitsSoldModal) {
        unitsSoldModal.classList.remove('show'); // Remove 'show' class for closing animation
        setTimeout(() => {
            unitsSoldModal.style.display = 'none'; // Hide modal after the transition
        }, 300); // Wait for animation to finish
    }
});
