// File: /js/userAccounts.js

// Fetch user account details and render them dynamically
async function fetchAndRenderUserAccounts() {
    try {
        const response = await fetch('db_queries/fetch_users2.php');
        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        // Populate data for each filter dynamically
        populateDetailsFilter(data.users);
        populateContactsFilter(data.users);
        populateLogsFilter(data.users);

        // Attach filter event listeners
        setupFilterLogic();

        // Attach search event listener
        setupLiveSearch();

        // Add click event listeners for list items
        setupUserActionListeners();
    } catch (error) {
        console.error("Error fetching user accounts:", error);
    }
}

// Utility function to get role prefix
function getRolePrefix(role) {
    const prefixes = {
        inventory_manager: "IVM",
        sales_manager: "SSM",
        supply_chain_manager: "SCM",
        admin: "ADM",
    };
    return prefixes[role] || "UNK"; // Default to "UNK" if role is unknown
}

// Utility function to get role display name
function getRoleDisplayName(role) {
    const displayNames = {
        inventory_manager: "Inventory Manager",
        sales_manager: "Sales Manager",
        supply_chain_manager: "Supply Chain Manager",
        admin: "Admin",
    };
    return displayNames[role] || "Unknown Role";
}

// Utility function to format employee ID
function formatEmployeeId(id, role) {
    const prefix = getRolePrefix(role);
    const formattedId = String(id).padStart(3, "0"); // Ensures the ID is 3 digits
    return `${prefix}-${formattedId}`;
}

// Utility function to format phone numbers
function formatPhoneNumber(phone) {
    if (!phone) return "N/A";
    return phone.replace(/(\d{4})(\d{3})(\d{4})/, "$1-$2-$3");
}

// Populate the "Details" filter
function populateDetailsFilter(users) {
    const container = document.querySelector(".user-account-list-container");

    users.forEach(user => {
        const employeeId = formatEmployeeId(user.employee_id, user.user_role);
        const roleDisplay = getRoleDisplayName(user.user_role);

        const listItem = document.createElement("li");
        listItem.classList.add("user-account-information-item", "details-filter", "active");

        listItem.innerHTML = `
            <span class="employee-id">${employeeId}</span>
            <span class="employee-name">${user.first_name} ${user.last_name}</span>
            <span class="user-role">${roleDisplay}</span>
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
        const employeeId = formatEmployeeId(user.employee_id, user.user_role);
        const formattedPhone1 = formatPhoneNumber(user.phone_number_1);
        const formattedPhone2 = formatPhoneNumber(user.phone_number_2);

        const listItem = document.createElement("li");
        listItem.classList.add("user-account-information-item", "contacts-filter");

        listItem.innerHTML = `
            <span class="employee-id">${employeeId}</span>
            <span class="email">${user.email}</span>
            <span class="phone-number-1">${formattedPhone1}</span>
            <span class="phone-number-2">${formattedPhone2}</span>
        `;

        container.appendChild(listItem);
    });
}

// Populate the "Logs" filter
function populateLogsFilter(users) {
    const container = document.querySelector(".user-account-list-container");

    users.forEach(user => {
        const employeeId = formatEmployeeId(user.employee_id, user.user_role);

        const listItem = document.createElement("li");
        listItem.classList.add("user-account-information-item", "logs-filter");

        listItem.innerHTML = `
            <span class="employee-id">${employeeId}</span>
            <span class="last-login">${formatDate(user.last_login)}</span>
            <span class="last-logout">${formatDate(user.last_logout)}</span>
            <span class="updated-on">${formatDate(user.updated_on)}</span>
            <span class="updated-by">${user.updated_by || "N/A"}</span>
        `;

        container.appendChild(listItem);
    });
}

// Function to handle filter click events
function setupFilterLogic() {
    const filters = {
        mua3: "details-filter",
        mua4: "contacts-filter",
        mua5: "logs-filter",
    };

    Object.keys(filters).forEach(filterKey => {
        const filterElement = document.querySelector(`.${filterKey}`);

        filterElement.addEventListener("click", () => {
            // Reset active states for all filters
            document.querySelectorAll(".filter").forEach(filter => filter.classList.remove("active"));
            document.querySelectorAll(".user-account-information-item").forEach(item => item.classList.remove("active"));
            document.querySelectorAll(".user-account-information-header").forEach(header => header.classList.remove("active"));

            // Activate the clicked filter and corresponding rows
            filterElement.classList.add("active");
            const filterClass = filters[filterKey];

            document.querySelectorAll(`.${filterClass}`).forEach(item => item.classList.add("active"));
        });
    });
}

// Utility function to format dates
function formatDate(dateString) {
    if (!dateString) return "N/A";
    const options = { year: "numeric", month: "short", day: "numeric", hour: "2-digit", minute: "2-digit" };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

// Live search functionality
function setupLiveSearch() {
    const searchInput = document.getElementById("searchusertable");
    searchInput.addEventListener("input", () => {
        const searchValue = searchInput.value.toLowerCase();
        const items = document.querySelectorAll(".user-account-information-item");

        items.forEach(item => {
            const textContent = item.textContent.toLowerCase();
            if (textContent.includes(searchValue)) {
                item.style.display = "";
            } else {
                item.style.display = "none";
            }
        });
    });
}

// Initialize fetch and rendering process
fetchAndRenderUserAccounts();
