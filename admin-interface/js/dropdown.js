document.addEventListener('DOMContentLoaded', function() {
    const notificationIcon = document.querySelector('[data-tooltip="Notifications"]');
    const notificationDropdown = document.getElementById('notification-dropdown');
    const settingsIcon = document.querySelector('[data-tooltip="Settings"]');
    const settingsDropdown = document.getElementById('settings-dropdown');
    
    let activeIcon = null; // Track the currently active icon
    let hideTimeout = null; // To manage exit animation timing

    function toggleDropdown(icon, dropdown) {
        if (icon === activeIcon) {
            // Deactivate the active dropdown
            icon.classList.remove('active');
            dropdown.classList.remove('show'); // Begin exit animation
            dropdown.classList.add('hide');
            
            // Set timeout to fully disable the dropdown after the animation duration
            hideTimeout = setTimeout(() => {
                dropdown.classList.remove('hide');
                activeIcon = null;
            }, 300); // Match the duration of the CSS transition
        } else {
            // Clear any pending hide timeout
            clearTimeout(hideTimeout);

            // Deactivate previous active icon and dropdown
            if (activeIcon) {
                activeIcon.classList.remove('active');
                const activeDropdown = activeIcon === notificationIcon ? notificationDropdown : settingsDropdown;
                activeDropdown.classList.remove('show');
                activeDropdown.classList.add('hide');
                
                // Delay pointer-events:none to allow exit animation
                setTimeout(() => activeDropdown.classList.remove('hide'), 300);
            }

            // Activate the clicked icon and show dropdown
            icon.classList.add('active');
            dropdown.classList.add('show');
            dropdown.classList.remove('hide');
            activeIcon = icon;
        }
    }

    // Toggle notifications dropdown
    notificationIcon.addEventListener('click', function(event) {
        event.stopPropagation();
        toggleDropdown(notificationIcon, notificationDropdown);
    });

    // Toggle settings dropdown
    settingsIcon.addEventListener('click', function(event) {
        event.stopPropagation();
        toggleDropdown(settingsIcon, settingsDropdown);
    });

    // Hide dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (activeIcon && !notificationDropdown.contains(event.target) && !notificationIcon.contains(event.target) &&
            !settingsDropdown.contains(event.target) && !settingsIcon.contains(event.target)) {
            toggleDropdown(activeIcon, activeIcon === notificationIcon ? notificationDropdown : settingsDropdown);
        }
    });
});
