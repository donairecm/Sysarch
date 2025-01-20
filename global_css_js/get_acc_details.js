document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('updateaccountdetialsForm');
    const updateButton = document.getElementById('updateaccountdetails');
    const usernameLabel = document.getElementById('uad-username');
    const usernameInput = document.getElementById('uad-employee_username');
    const usernameError = document.getElementById('aig-username-error');
    const emailLabel = document.getElementById('uad-email');
    const emailInput = document.getElementById('uad-employee_email');
    const fnameLabel = document.getElementById('uad-first-name');
    const fnameInput = document.getElementById('uad-employee_fname');
    const mnameLabel = document.getElementById('uad-lname');
    const mnameInput = document.getElementById('uad-employee_mname');
    const lnameLabel = document.getElementById('uad-mname');
    const lnameInput = document.getElementById('uad-employee_lname');
    const phone1Label = document.getElementById('uad-phone-num-1');
    const phone1Input = document.getElementById('uad-employee_pnum1');
    const phone2Label = document.getElementById('uad-phone-num-2');
    const phone2Input = document.getElementById('uad-employee_pnum2');
    const inputFields = document.querySelectorAll('.input-group-aig input');
    const emailError = document.getElementById('aig-email-error'); // Added to match email validation

    let userId = null; // To store the user's ID globally

    // Fetch and populate employee account details
    async function fetchAccountDetails() {
        try {
            console.log('Fetching account details...');
            const response = await fetch('../php/fetch_acc_details.php');
            const result = await response.json();

            if (!result.success) {
                console.error('Failed to fetch account details:', result.error);
                return;
            }

            console.log('Account details fetched:', result.data);
            const data = result.data;

            // Populate labels only
            usernameLabel.textContent = data.username || 'N/A';
            emailLabel.textContent = data.email || 'N/A';
            fnameLabel.textContent = data.first_name || 'N/A';
            mnameLabel.textContent = data.middle_name || 'N/A';
            lnameLabel.textContent = data.last_name || 'N/A';
            phone1Label.textContent = data.phone_number_1 || 'N/A';
            phone2Label.textContent = data.phone_number_2 || 'N/A';
        } catch (error) {
            console.error('Error fetching account details:', error);
        }
    }

    // Fetch the user's ID
    async function fetchUserId() {
        try {
            console.log('Fetching employee_id...');
            const response = await fetch('../php/get_logged_in_user.php');
            const result = await response.json();

            if (!result.success) {
                console.error('Failed to fetch employee_id:', result.error);
                return;
            }

            userId = result.employee_id; // Store the user's ID
            console.log('Got employee_id:', userId);
        } catch (error) {
            console.error('Error fetching user ID:', error);
        }
    }

    // Function to validate username
    async function validateUsername() {
        const username = usernameInput.value.trim();
        console.log('Inputted username:', username);
        hideTooltip(usernameError);

        if (!username) {
            return; // Skip validation for empty input
        }

        try {
            console.log('Validating username on the server...');
            const checkResponse = await fetch('../php/val_accdetails_fields.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, exclude_id: userId }),
            });

            const checkResult = await checkResponse.json();
            console.log('Username validation result:', checkResult);

            if (checkResult.exists) {
                if (checkResult.message) {
                    showTooltip(usernameError, checkResult.message);
                } else {
                    showTooltip(usernameError, 'Username already taken');
                }
                usernameInput.classList.add('has-error');
            } else {
                usernameInput.classList.remove('has-error');
            }
        } catch (error) {
            console.error('Error validating username:', error);
        }
    }

    // Function to validate email format
    async function validateEmail() {
        const email = emailInput.value.trim();
        console.log('Inputted email:', email);
        hideTooltip(emailError);

        if (!email) {
            return; // Skip validation for empty input
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            showTooltip(emailError, 'Please enter a legitimate email');
            emailInput.classList.add('has-error');
            return;
        }

        try {
            console.log('Validating email on the server...');
            const response = await fetch('../php/val_accdetails_fields.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, exclude_id: userId }),
            });

            const result = await response.json();
            console.log('Email validation result:', result);

            if (result.email_exists) {
                showTooltip(emailError, result.email_message || 'Email already in use');
                emailInput.classList.add('has-error');
            } else {
                emailInput.classList.remove('has-error');
            }
        } catch (error) {
            console.error('Error validating email:', error);
            showTooltip(emailError, 'An error occurred while validating the email');
            emailInput.classList.add('has-error');
        }
    }

    // Show tooltip with animation and input highlight
    function showTooltip(element, message) {
        element.textContent = message;
        element.style.display = 'block';
        element.classList.add('pop-animation');

        setTimeout(() => {
            element.classList.remove('pop-animation');
        }, 300);
    }

    // Hide tooltip and clear input highlight
    function hideTooltip(element) {
        element.style.display = 'none';
        const inputField = element.closest('.input-group-aig').querySelector('input');
        inputField.classList.remove('has-error');
    }

    // Remove error class on focus
    inputFields.forEach(input => {
        input.addEventListener('focus', () => {
            const errorTooltip = input.closest('.input-group-aig').querySelector('.account-details-errors');
            if (errorTooltip) hideTooltip(errorTooltip); // Hide the tooltip
            input.classList.remove('has-error'); // Remove the error highlight
        });
    });

    // Add `has-value` class based on input content
    inputFields.forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim() !== '') {
                input.classList.add('has-value');
            } else {
                input.classList.remove('has-value');
            }
        });
    });

    // Enable/Disable "Save Changes" button
    function checkInputFields() {
        const hasValue = Array.from(inputFields).some(input => input.value.trim() !== '');
        updateButton.disabled = !hasValue;
    }
    inputFields.forEach(input => input.addEventListener('input', checkInputFields));

    // Initial check on page load
    checkInputFields();

    // Fetch account details when the page loads
    fetchAccountDetails();

    // Fetch the user ID when the page loads
    fetchUserId();

    // Validate username and email on form submission
    updateButton.addEventListener('click', async (e) => {
        e.preventDefault(); // Prevent form submission

        console.log('Submit button clicked.');

        await validateUsername();
        await validateEmail();

        if (usernameInput.classList.contains('has-error') || emailInput.classList.contains('has-error')) {
            console.log('Form submission blocked due to errors.');
            return;
        }

        console.log('Form is ready to be submitted.');
    });
});
