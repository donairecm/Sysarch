/*
document.addEventListener('DOMContentLoaded', () => {
    const icons = document.querySelectorAll('.ic-container');
    const tooltip = document.createElement('div');
    tooltip.classList.add('tooltip');
    document.body.appendChild(tooltip);

    icons.forEach(icon => {
        icon.addEventListener('mouseenter', (e) => {
            const tooltipText = e.currentTarget.getAttribute('data-tooltip');
            tooltip.textContent = tooltipText;
            tooltip.style.opacity = '1';
        });

        icon.addEventListener('mousemove', (e) => {
            tooltip.style.left = `${e.pageX}px`;
            tooltip.style.top = `${e.pageY}px`;
        });

        icon.addEventListener('mouseleave', () => {
            tooltip.style.opacity = '0';
        });
    });
});
*/

// Profile 
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

// Alerts Popover
/* function toggleAlertsPopover() {
    const alertsPopover = document.getElementById('alertsPopOverContent');
    const alertsIcon = document.querySelector('.ic-container[data-tooltip="Alerts"]');
    const [alertsFirstSVG, alertsSecondSVG] = alertsIcon.querySelectorAll('svg');

    alertsPopover.classList.toggle('show');
    alertsIcon.classList.toggle('active'); // Toggle the active class

    if (alertsPopover.classList.contains('show')) {
        alertsFirstSVG.classList.add('show');
        alertsSecondSVG.classList.remove('show');
        document.addEventListener('click', handleOutsideClickAlerts);
    } else {
        alertsFirstSVG.classList.remove('show');
        alertsSecondSVG.classList.add('show');
        document.removeEventListener('click', handleOutsideClickAlerts);
    }
} 

function handleOutsideClickAlerts(event) {
    const alertsPopover = document.getElementById('alertsPopOverContent');
    const alertsIcon = document.querySelector('.ic-container[data-tooltip="Alerts"]');
    const [alertsFirstSVG, alertsSecondSVG] = alertsIcon.querySelectorAll('svg');

    if (!alertsPopover.contains(event.target) && !alertsIcon.contains(event.target)) {
        alertsPopover.classList.remove('show');
        alertsIcon.classList.remove('active'); // Remove the active class
        alertsFirstSVG.classList.remove('show');
        alertsSecondSVG.classList.add('show');
        document.removeEventListener('click', handleOutsideClickAlerts);
    }
}*/

// Notifications Popover
function toggleNotificationsPopover() {
    const notificationsPopover = document.getElementById('notificationsPopOverContent');
    const notificationsIcon = document.querySelector('.ic-container[data-tooltip="Notifications"]');
    const [notificationsFirstSVG, notificationsSecondSVG] = notificationsIcon.querySelectorAll('svg');

    notificationsPopover.classList.toggle('show');
    notificationsIcon.classList.toggle('active'); // Toggle the active class

    if (notificationsPopover.classList.contains('show')) {
        notificationsFirstSVG.classList.add('show');
        notificationsSecondSVG.classList.remove('show');
        document.addEventListener('click', handleOutsideClickNotifications);
    } else {
        notificationsFirstSVG.classList.remove('show');
        notificationsSecondSVG.classList.add('show');
        document.removeEventListener('click', handleOutsideClickNotifications);
    }
}

function handleOutsideClickNotifications(event) {
    const notificationsPopover = document.getElementById('notificationsPopOverContent');
    const notificationsIcon = document.querySelector('.ic-container[data-tooltip="Notifications"]');
    const [notificationsFirstSVG, notificationsSecondSVG] = notificationsIcon.querySelectorAll('svg');

    if (!notificationsPopover.contains(event.target) && !notificationsIcon.contains(event.target)) {
        notificationsPopover.classList.remove('show');
        notificationsIcon.classList.remove('active'); // Remove the active class
        notificationsFirstSVG.classList.remove('show');
        notificationsSecondSVG.classList.add('show');
        document.removeEventListener('click', handleOutsideClickNotifications);
    }
}

/* User Activities Popover
function toggleUserActivitiesPopOver() {
    const useractivitiespopover = document.getElementById('userActivitiesPopOverContent');
uaIcon = document.querySelector('.ic-container[data-tooltip="user-activities-ic-nav"]');
    const [logsFirstSVG, logsSecondSVG] = uaIcon.querySelectorAll('svg');

    useractivitiespopover.classList.toggle('show');
    uaIcon.classList.toggle('active'); // Toggle the active class

    if (useractivitiespopover.classList.contains('show')) {
        logsFirstSVG.classList.add('show');
        logsSecondSVG.classList.remove('show');
        document.addEventListener('click', handleOutsideClickLogs);
    } else {
        logsFirstSVG.classList.remove('show');
        logsSecondSVG.classList.add('show');
        document.removeEventListener('click', handleOutsideClickLogs);
    }
}*/

function handleOutsideClickLogs(event) {
    const useractivitiespopover = document.getElementById('userActivitiesPopOverContent');
    const uaIcon = document.querySelector('.ic-container[data-tooltip="user-activities-ic-nav"]');
    const [logsFirstSVG, logsSecondSVG] = uaIcon.querySelectorAll('svg');

    if (!useractivitiespopover.contains(event.target) && !uaIcon.contains(event.target)) {
        useractivitiespopover.classList.remove('show');
        uaIcon.classList.remove('active'); // Remove the active class
        logsFirstSVG.classList.remove('show');
        logsSecondSVG.classList.add('show');
        document.removeEventListener('click', handleOutsideClickLogs);
    }
}


/* Action Tools Popover
function toggleActionToolsPopover() {
    const actionToolsPopover = document.getElementById('actiontoolsPopoverContent');
    const actionToolsIcon = document.querySelector('.ic-container[data-tooltip="Action Tools"]');
    const [actionToolsFirstSVG, actionToolsSecondSVG] = actionToolsIcon.querySelectorAll('svg');

    // Toggle the visibility of the Quick Access Popover
    actionToolsPopover.classList.toggle('show');
    actionToolsIcon.classList.toggle('active'); // Toggle the active class

    // Toggle SVG visibility
    if (actionToolsPopover.classList.contains('show')) {
        actionToolsFirstSVG.classList.add('show');
        actionToolsSecondSVG.classList.remove('show');
        document.addEventListener('click', handleOutsideClickQuickAccess);
    } else {
        actionToolsFirstSVG.classList.remove('show');
        actionToolsSecondSVG.classList.add('show');
        document.removeEventListener('click', handleOutsideClickQuickAccess);
    }
}*/

/* Action Tools removed
function handleOutsideClickQuickAccess(event) {
    const actionToolsPopover = document.getElementById('actiontoolsPopoverContent');
    const actionToolsIcon = document.querySelector('.ic-container[data-tooltip="Action Tools"]');
    const [actionToolsFirstSVG, actionToolsSecondSVG] = actionToolsIcon.querySelectorAll('svg');

    // Close the popover if the click is outside the popover and the icon
    if (!actionToolsPopover.contains(event.target) && !actionToolsIcon.contains(event.target)) {
        actionToolsPopover.classList.remove('show');
        actionToolsIcon.classList.remove('active');
        actionToolsFirstSVG.classList.remove('show');
        actionToolsSecondSVG.classList.add('show');
        document.removeEventListener('click', handleOutsideClickQuickAccess);
    }
}*/

/*
//quick access tooltip
document.addEventListener('DOMContentLoaded', () => {
    // Select only the Quick Access icon
    const actionToolsIcon = document.querySelector('.ic-container[data-tooltip="Quick Access"]');
    
    // Create a tooltip element
    const tooltip = document.createElement('div');
    tooltip.classList.add('tooltip');
    document.body.appendChild(tooltip);

    // Add event listeners for the Quick Access icon
    actionToolsIcon.addEventListener('mouseenter', (e) => {
        const tooltipText = e.currentTarget.getAttribute('data-tooltip');
        tooltip.textContent = tooltipText;
        tooltip.style.opacity = '1';
    });

    actionToolsIcon.addEventListener('mousemove', (e) => {
        tooltip.style.left = `${e.pageX}px`;
        tooltip.style.top = `${e.pageY}px`;
    });

    actionToolsIcon.addEventListener('mouseleave', () => {
        tooltip.style.opacity = '0';
    });
}); */

// Event Listeners for Icons
// document.querySelector('.ic-container[data-tooltip="Alerts"]').addEventListener('click', toggleAlertsPopover);
document.querySelector('.ic-container[data-tooltip="Notifications"]').addEventListener('click', toggleNotificationsPopover);
// document.querySelector('.ic-container[data-tooltip="user-activities-ic-nav"]').addEventListener('click', toggleUserActivitiesPopOver);
// document.querySelector('.ic-container[data-tooltip="Action Tools"]').addEventListener('click', toggleActionToolsPopover);


// Notification demo

