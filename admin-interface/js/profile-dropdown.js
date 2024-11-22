function toggleDropdown() {
    const dropdown = document.getElementById('profilePopOverContent');
    const chevron = document.getElementById('chevron');

    // Toggle the dropdown visibility with smooth animation
    dropdown.classList.toggle('show');

    // Rotate the chevron icon
    chevron.classList.toggle('rotate');

    // Add or remove event listener based on the dropdown state
    if (dropdown.classList.contains('show')) {
        document.addEventListener('click', handleOutsideClick);
    } else {
        document.removeEventListener('click', handleOutsideClick);
    }
}

function handleOutsideClick(event) {
    const dropdown = document.getElementById('profilePopOverContent');
    const profileContainer = document.querySelector('.profile-container');

    // Check if the clicked element is outside both the dropdown and profile container
    if (!dropdown.contains(event.target) && !profileContainer.contains(event.target)) {
        dropdown.classList.remove('show');
        document.getElementById('chevron').classList.remove('rotate');
        document.removeEventListener('click', handleOutsideClick);
    }
}
