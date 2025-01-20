document.addEventListener('DOMContentLoaded', () => {
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    const submenuItems = document.querySelectorAll('.submenu-container a');
    const profileMenuItems = document.querySelectorAll('.profile-menu a');
    const pages = document.querySelectorAll('.page');

    function activatePage(targetPage) {
        // Remove 'active' from all pages
        pages.forEach(page => page.classList.remove('active'));

        // Add 'active' to the selected page
        const pageToActivate = document.getElementById(targetPage);
        if (pageToActivate) {
            pageToActivate.classList.add('active');
        }
    }

    function activateSidebarItem(targetItem) {
        // Remove 'active' from all sidebar, submenu, and profile menu items
        sidebarItems.forEach(link => link.classList.remove('active'));
        submenuItems.forEach(link => link.classList.remove('active'));
        profileMenuItems.forEach(link => link.classList.remove('active'));

        // Add 'active' to the clicked item
        targetItem.classList.add('active');
    }

    // Main sidebar item click handling
    sidebarItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const targetPage = item.getAttribute('data-page');
            activatePage(targetPage);
            activateSidebarItem(item);
        });
    });

    // Submenu item click handling
    submenuItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const targetPage = item.getAttribute('data-page');
            activatePage(targetPage);
            activateSidebarItem(item);
        });
    });

    // Profile menu item click handling
    profileMenuItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const targetPage = item.getAttribute('data-page');
            activatePage(targetPage);
            activateSidebarItem(item);
        });
    });

    // Optional: Set the first main item active by default
    sidebarItems[0]?.classList.add('active');
    pages[0]?.classList.add('active');
});
