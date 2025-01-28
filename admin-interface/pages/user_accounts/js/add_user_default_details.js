document.addEventListener('DOMContentLoaded', () => {
    // Modal elements
    const addUserModal = document.querySelector('.modal-add-users');
    const confirmationModal = document.getElementById('confirmationModaladdusers');
    const addUserBtn = document.querySelector('.mua2.button');
    const addUserForm = document.getElementById('addauserForm');
    const submitButton = document.getElementById('addauserconfirmbtn');
    const confirmationList = document.getElementById('addauserconfirmationlist');
    const confirmUserCreationBtn = document.getElementById('confirmusercreation');
    const cancelUserCreationBtn = document.getElementById('cancelusercreation');

    // Form fields
    const usernameInput = document.getElementById('mua-username-input');
    const passwordInput = document.getElementById('mua-password-input');
    const roleInput = document.getElementById('mua-user_role-input');

    // Store usernames fetched from the server
    let existingUsernames = [];

    // Fetch existing usernames from the server
    async function fetchUsernames() {
        try {
            const response = await fetch('db_queries/add_new_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'get_usernames' }),
            });

            const result = await response.json();
            if (response.ok && result.success) {
                existingUsernames = result.usernames || [];
            } else {
                console.error('Failed to fetch usernames:', result.error);
            }
        } catch (error) {
            console.error('Error fetching usernames:', error);
        }
    }

    // Open the "Add User" modal
    addUserBtn.addEventListener('click', () => {
        addUserModal.classList.add('show');
    });

    // Close modals when clicking outside their content areas
    document.addEventListener('click', (event) => {
        // Close "Add User" modal
        if (
            addUserModal.classList.contains('show') &&
            !addUserModal.querySelector('.modal-content').contains(event.target) &&
            !addUserBtn.contains(event.target)
        ) {
            addUserModal.classList.remove('show');
        }

        // Close "Confirmation" modal
        if (
            confirmationModal.style.display === 'flex' &&
            !confirmationModal.querySelector('div').contains(event.target)
        ) {
            confirmationModal.style.display = 'none';
        }
    });

    // Live username validation
    usernameInput.addEventListener('input', () => {
        const username = usernameInput.value.trim();

        if (existingUsernames.includes(username)) {
            submitButton.textContent = 'Username already exists';
            submitButton.disabled = true;
        } else {
            submitButton.textContent = 'Add user';
            validateForm();
        }
    });

    // Live password validation
    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;
        if (password.length < 8) {
            submitButton.textContent = 'Password must be 8 characters long';
            submitButton.disabled = true;
        } else {
            submitButton.textContent = 'Add user';
            validateForm();
        }
    });

    // Check if all fields have values and update button state
    function validateForm() {
        const username = usernameInput.value.trim();
        const password = passwordInput.value;

        if (
            username &&
            password.length >= 8 &&
            roleInput.value &&
            !existingUsernames.includes(username)
        ) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    // Validate fields on input change
    [usernameInput, passwordInput, roleInput].forEach((input) => {
        input.addEventListener('input', validateForm);
    });

    // Submit the form and show confirmation modal
    addUserForm.addEventListener('submit', (event) => {
        event.preventDefault();

        // Populate the confirmation modal list
        confirmationList.innerHTML = `
            <li>Username: ${usernameInput.value}</li>
            <li>Password: ${passwordInput.value}</li>
            <li>Role: ${roleInput.options[roleInput.selectedIndex].text}</li>
        `;

        // Show the confirmation modal
        confirmationModal.style.display = 'flex';
    });

    // Cancel user creation
    cancelUserCreationBtn.addEventListener('click', () => {
        confirmationModal.style.display = 'none';
    });

    // Confirm user creation
    confirmUserCreationBtn.addEventListener('click', async () => {
        const userData = {
            username: usernameInput.value,
            password: passwordInput.value,
            user_role: roleInput.value,
        };

        try {
            // Send JSON data to the server
            const response = await fetch('db_queries/add_new_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(userData),
            });

            const result = await response.json();

            if (response.ok && result.success) {
                alert('User added successfully!');
                // Refresh the username list after adding a user
                await fetchUsernames();
            } else {
                alert(`Error: ${result.error || 'Unable to add user.'}`);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        }

        // Close all modals and reset form
        confirmationModal.style.display = 'none';
        addUserModal.classList.remove('show');
        addUserForm.reset();
        submitButton.disabled = true;
    });

    // Fetch usernames when the page loads
    fetchUsernames();
});
