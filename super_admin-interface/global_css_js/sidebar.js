// Sidebar Collapse logic: basically it closes when clicked
const sidebar = document.getElementById('sidebar');
const sidebarLinks = document.querySelectorAll('#sidebar a'); // Specifically select <a> elements

// Function to simulate closing the sidebar
function closeSidebarOnClick() {
    sidebar.classList.remove('hover'); // Remove hover-open class or state
    sidebar.style.pointerEvents = 'none'; // Disable hover temporarily to prevent flicker

    setTimeout(() => {
        sidebar.style.pointerEvents = ''; // Re-enable hover after the transition
    }, 300); // Match the CSS transition duration
}

// Add click event to all sidebar <a> elements
sidebarLinks.forEach(link => {
    link.addEventListener('click', closeSidebarOnClick);
});

// Sidebar Active functionality
const sidebarItems = document.querySelectorAll('#sidebar li'); // Specifically select <li> elements

// Function to handle active class switching
function setActiveClass(event) {
    // Remove 'active' class from all <li>
    sidebarItems.forEach(item => item.classList.remove('active'));

    // Add 'active' class to the clicked <li>
    const clickedItem = event.currentTarget;
    clickedItem.classList.add('active');
}

// Attach event listener to all <li> elements
sidebarItems.forEach(item => {
    item.addEventListener('click', setActiveClass);
});
