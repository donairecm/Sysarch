// File: /js/userAccounts.js

// Fetch user account details and render them dynamically
async function fetchAndRenderUserAccounts() {
    try {
        const response = await fetch('db_queries/fetch_users2.php');
        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        // Populate data for each filter
        populateDetailsFilter(data.users);
        populateContactsFilter(data.users);
        populateLogsFilter(data.users);
    } catch (error) {
        console.error("Error fetching user accounts:", error);
    }
}

// Populate the "Details" filter
function populateDetailsFilter(users) {
    const container = document.querySelector(".user-account-list-container");

    users.forEach(user => {
        const listItem = document.createElement("li");
        listItem.classList.add("user-account-information-item", "details-filter", "active");

        listItem.innerHTML = `
            <span class="employee-id">${user.employee_id}</span>
            <span class="employee-name">${user.first_name} ${user.last_name}</span>
            <span class="user-role">${user.user_role}</span>
            <span class="user-status">${user.user_status}</span>
            <span class="created-on">${formatDate(user.created_on)}</span>
        `;

        container.appendChild(listItem);
    });
}

// Populate the "Contacts" filter
function populateContactsFilter(users) {
    const container = document.querySelector(".user-account-list-container");

    users.forEach(user => {
        const listItem = document.createElement("li");
        listItem.classList.add("user-account-information-item", "contacts-filter", "active");

        listItem.innerHTML = `
            <span class="employee-id">${user.employee_id}</span>
            <span class="email">${user.email}</span>
            <span class="phone-number-1">${user.phone_number_1 || "N/A"}</span>
            <span class="phone-number-2">${user.phone_number_2 || "N/A"}</span>
        `;

        container.appendChild(listItem);
    });
}

// Populate the "Logs" filter
function populateLogsFilter(users) {
    const container = document.querySelector(".user-account-list-container");

    users.forEach(user => {
        const listItem = document.createElement("li");
        listItem.classList.add("user-account-information-item", "logs-filter", "active");

        listItem.innerHTML = `
            <span class="employee-id">${user.employee_id}</span>
            <span class="last-login">${formatDate(user.last_login)}</span>
            <span class="last-logout">${formatDate(user.last_logout)}</span>
            <span class="updated-on">${formatDate(user.updated_on)}</span>
            <span class="updated-by">${user.updated_by || "N/A"}</span>
        `;

        container.appendChild(listItem);
    });
}


// Utility function to format dates
function formatDate(dateString) {
    if (!dateString) return "N/A";
    const options = { year: "numeric", month: "short", day: "numeric", hour: "2-digit", minute: "2-digit" };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

// Initialize fetch and rendering process
fetchAndRenderUserAccounts();
