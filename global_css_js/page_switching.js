document.addEventListener('DOMContentLoaded', () => {
    const allNavItems = document.querySelectorAll('.sidebar-item, .submenu-container a, .profile-menu a');
    const pages = document.querySelectorAll('.page');

    function activatePage(targetPage) {
        // Remove 'active' from all pages
        pages.forEach(page => page.classList.remove('active'));

        // Add 'active' to the selected page
        const pageToActivate = document.getElementById(targetPage);
        if (pageToActivate) {
            pageToActivate.classList.add('active');
        } else {
            console.warn(`Page with id '${targetPage}' not found.`);
        }
    }

    function activateNavItem(targetItem) {
        // Remove 'active' from all navigation items
        allNavItems.forEach(link => link.classList.remove('active'));

        // Add 'active' to the clicked item
        targetItem.classList.add('active');
    }

    // General navigation item click handling
    allNavItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const targetPage = item.getAttribute('data-page');

            // Activate the corresponding page and navigation item
            if (targetPage) {
                activatePage(targetPage);
                activateNavItem(item);
            } else {
                console.warn(`'data-page' attribute not found on`, item);
            }
        });
    });

    // Optional: Set the first item active by default
    allNavItems[0]?.classList.add('active');
    pages[0]?.classList.add('active');
});
