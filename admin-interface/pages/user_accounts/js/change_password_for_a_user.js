document.addEventListener('DOMContentLoaded', () => {
    // Modal elements
    const changePasswordModal = document.querySelector('.modal-change-password-for-a-user');
    const confirmationModal = document.getElementById('confirmationModalchangepasswordforuser');
    const changePasswordBtn = document.querySelector('.mua7.button');
    const changePasswordForm = document.getElementById('changepasswordforauserForm');
    const confirmButton = document.getElementById('changepasswordforuserconfirm');
    const confirmationList = document.getElementById('changeauserpasswordconfirmationlist');
    const confirmPasswordChangeBtn = document.getElementById('confirmpasswordchangeforuser');
    const cancelPasswordChangeBtn = document.getElementById('cancelpasswordchangeforuser');

    // Form fields
    const userSelect = document.getElementById('mua-user_change_password-input');
    const passwordInput = document.getElementById('mua-change-password-input');

    // Store users fetched from the server
    let users = [];

    // Fetch users from the database to populate the select options
    async function fetchUsers() {
        try {
            const response = await fetch('db_queries/fetch_users_forpchange.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            const result = await response.json();
            if (response.ok && result.success) {
                users = result.users || [];
                populateUserOptions(users);
            } else {
                console.error('Failed to fetch users:', result.error);
            }
        } catch (error) {
            console.error('Error fetching users:', error);
        }
    }

    // Populate the select dropdown with user data
    function populateUserOptions(users) {
        userSelect.innerHTML = '<option value="" disabled selected>Choose user</option>';
        users.forEach((user) => {
            const { employee_id, user_role, first_name, last_name, middle_name } = user;

            // Skip admin roles
            if (user_role === 'admin') {
                return;
            }

            // Generate prefix and padded ID
            const prefixMapping = {
                inventory_manager: 'IVM',
                sales_manager: 'SSM',
                supply_chain_manager: 'SCM',
            };
            const prefix = prefixMapping[user_role];
            const paddedId = String(employee_id).padStart(3, '0');
            const userDisplay = `${prefix}-${paddedId} | ${first_name} ${middle_name} ${last_name}`;

            const option = document.createElement('option');
            option.value = employee_id;
            option.textContent = userDisplay;
            userSelect.appendChild(option);
        });
    }

    // Open the "Change Password" modal
    changePasswordBtn.addEventListener('click', () => {
        changePasswordModal.classList.add('show');
    });

    // Close modals when clicking outside their content areas
    document.addEventListener('click', (event) => {
        if (
            changePasswordModal.classList.contains('show') &&
            !changePasswordModal.querySelector('.modal-content').contains(event.target) &&
            !changePasswordBtn.contains(event.target)
        ) {
            changePasswordModal.classList.remove('show');
        }

        if (
            confirmationModal.style.display === 'flex' &&
            !confirmationModal.querySelector('div').contains(event.target)
        ) {
            confirmationModal.style.display = 'none';
        }
    });

    // Live password validation
    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;
        if (password.length < 8) {
            confirmButton.textContent = 'Password must be at least 8 characters';
            confirmButton.disabled = true;
        } else {
            confirmButton.textContent = 'Change Password';
            validateForm();
        }
    });

    // Validate if all fields are set
    function validateForm() {
        if (userSelect.value && passwordInput.value.length >= 8) {
            confirmButton.disabled = false;
        } else {
            confirmButton.disabled = true;
        }
    }

    // Validate fields on input change
    [userSelect, passwordInput].forEach((input) => {
        input.addEventListener('input', validateForm);
    });

    // Submit the form and show the confirmation modal
    changePasswordForm.addEventListener('submit', (event) => {
        event.preventDefault();

        // Populate the confirmation modal list
        const selectedUser = userSelect.options[userSelect.selectedIndex].text;
        confirmationList.innerHTML = `
            <li><span>${selectedUser} | Password change to: ${passwordInput.value}</span></li>
        `;

        // Show the confirmation modal
        confirmationModal.style.display = 'flex';
    });

    // Cancel password change
    cancelPasswordChangeBtn.addEventListener('click', () => {
        confirmationModal.style.display = 'none';
    });

    // Confirm password change
    confirmPasswordChangeBtn.addEventListener('click', async () => {
        const userId = userSelect.value;
        const newPassword = passwordInput.value;

        try {
            const response = await fetch('db_queries/change_password_forp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ user_id: userId, password: newPassword }),
            });

            const result = await response.json();

            if (response.ok && result.success) {
                alert('Password changed successfully!');
            } else {
                alert(`Error: ${result.error || 'Unable to change password.'}`);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        }

        // Close all modals and reset form
        confirmationModal.style.display = 'none';
        changePasswordModal.classList.remove('show');
        changePasswordForm.reset();
        confirmButton.disabled = true;
    });

    // Fetch users when the page loads
    fetchUsers();
});
