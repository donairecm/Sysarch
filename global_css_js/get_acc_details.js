document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('updateaccountdetialsForm');
    const updateButton = document.getElementById('updateaccountdetails');
    const usernameInput = document.getElementById('uad-employee_username');
    const usernameError = document.getElementById('aig-username-error');
    const emailInput = document.getElementById('uad-employee_email');
    const emailError = document.getElementById('aig-email-error');
    const inputFields = document.querySelectorAll('.input-group-aig input');

    let userId = null; // To store the user's ID globally

    // Function to fetch the user's ID
    async function fetchUserId() {
        try {
            const response = await fetch('../php/get_logged_in_user.php');
            const result = await response.json();

            if (!result.success) {
                console.error('Failed to fetch employee_id:', result.error);
                return;
            }

            userId = result.employee_id; // Store the user's ID
        } catch (error) {
            console.error('Error fetching user ID:', error);
        }
    }

    // Function to validate username
    async function validateUsername() {
        const username = usernameInput.value.trim();
        hideTooltip(usernameError);

        if (!username) {
            return; // Skip validation for empty input
        }

        try {
            // Check username availability
            const checkResponse = await fetch('../php/val_accdetails_fields.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, exclude_id: userId }),
            });

            const checkResult = await checkResponse.json();

            if (checkResult.exists) {
                if (checkResult.message) {
                    showTooltip(usernameError, checkResult.message); // Display "You're already using this username :)"
                } else {
                    showTooltip(usernameError, 'Username already taken'); // Display default error message
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
        hideTooltip(emailError);

        if (!email) {
            return; // Skip validation for empty input
        }

        // Regex for validating email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Client-side validation for email format
        if (!emailRegex.test(email)) {
            showTooltip(emailError, 'Please enter a legitimate email');
            emailInput.classList.add('has-error');
            return;
        }

        try {
            // Fetch server validation for the email
            const response = await fetch('../php/val_accdetails_fields.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    email: email,
                    exclude_id: userId,
                }),
            });

            const result = await response.json();

            if (result.email_exists) {
                // Server-side email validation error
                showTooltip(emailError, result.email_message || 'Email already in use');
                emailInput.classList.add('has-error');
            } else {
                emailInput.classList.remove('has-error'); // No server-side error
            }
        } catch (error) {
            console.error('Error validating email:', error);
            showTooltip(emailError, 'An error occurred while validating the email');
            emailInput.classList.add('has-error');
        }
    }

    // Show tooltip with animation and input highlight
    function showTooltip(element, message) {
        const inputField = element.closest('.input-group-aig').querySelector('input');
        element.textContent = message;
        element.style.display = 'block';
        element.classList.add('pop-animation');
        inputField.classList.add('has-error');

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

    // Fetch the user ID when the page loads
    fetchUserId();

    // Validate username and email on form submission
    updateButton.addEventListener('click', async (e) => {
        e.preventDefault(); // Prevent form submission

        // Validate inputs
        await validateUsername();
        await validateEmail();

        // If there are errors, do not proceed
        if (usernameInput.classList.contains('has-error') || emailInput.classList.contains('has-error')) return;

        console.log('Form is ready to be submitted.');
    });
});
