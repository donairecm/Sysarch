document.addEventListener('DOMContentLoaded', () => {
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    const pages = document.querySelectorAll('.page');

    sidebarItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const targetPage = item.getAttribute('data-page');

            // Remove 'active' from all pages
            pages.forEach(page => page.classList.remove('active'));

            // Add 'active' to the selected page
            document.getElementById(targetPage).classList.add('active');

            // Remove 'active' from all sidebar items
            sidebarItems.forEach(link => link.classList.remove('active'));

            // Add 'active' to the clicked item
            item.classList.add('active');
        });
    });

    // Optional: Set the first item active by default
    sidebarItems[0].classList.add('active');
    pages[0].classList.add('active');
});
