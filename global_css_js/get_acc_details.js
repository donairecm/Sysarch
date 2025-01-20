document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('updateaccountdetialsForm');
    const updateButton = document.getElementById('updateaccountdetails');
    const usernameInput = document.getElementById('uad-employee_username');
    const usernameError = document.getElementById('aig-username-error');
    const inputFields = document.querySelectorAll('.input-group-aig input');

    // Function to validate username
    async function validateUsername() {
        const username = usernameInput.value.trim();
        hideTooltip(usernameError);

        if (!username) {
            return; // Skip validation for empty input
        }

        try {
            // Fetch current user's employee_id
            const response = await fetch('../php/get_logged_in_user.php');
            const result = await response.json();

            if (!result.success) {
                console.error('Failed to fetch employee_id:', result.error);
                return;
            }

            const userId = result.employee_id;

            // Check username availability
            const checkResponse = await fetch('../php/val_accdetails_fields.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, exclude_id: userId }),
            });

            const checkResult = await checkResponse.json();

            if (checkResult.exists) {
                showTooltip(usernameError, 'Username already taken');
                usernameInput.classList.add('has-error');
            } else {
                usernameInput.classList.remove('has-error');
            }
        } catch (error) {
            console.error('Error validating username:', error);
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

    // Validate username on form submission
    updateButton.addEventListener('click', async (e) => {
        e.preventDefault(); // Prevent form submission
        await validateUsername();

        // If there are errors, do not proceed
        if (usernameInput.classList.contains('has-error')) return;

        console.log('Form is ready to be submitted.');
    });
});
