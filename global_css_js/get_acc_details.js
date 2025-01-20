document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('updateaccountdetialsForm');
    const updateButton = document.getElementById('updateaccountdetails');
    const usernameInput = document.getElementById('uad-employee_username');
    const usernameError = document.getElementById('aig-username-error');
    const inputFields = document.querySelectorAll('.input-group-aig input');

    // Function to validate username
    async function validateUsername() {
        const username = usernameInput.value.trim();
        usernameError.style.display = 'none'; // Clear existing error

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

            const userId = result.employee_id; // Use employee_id directly without removing prefix

            // Log the employee_id and username
            console.log(`Employee ID used for search: ${userId}`);
            console.log(`Username inputted by the user: ${username}`);

            // Check username availability
            const checkResponse = await fetch('../php/val_accdetails_fields.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username,
                    exclude_id: userId, // Exclude current user's ID
                }),
            });

            const checkResult = await checkResponse.json();

            // Log the table and the comparison results
            console.log('Table where the search happened: users');
            console.log('Results of usernames where the inputted was compared to:', checkResult);

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

    // Helper function to show tooltips
    function showTooltip(element, message) {
        element.textContent = message;
        element.style.display = 'block';
        element.classList.add('pop-animation');
        setTimeout(() => {
            element.classList.remove('pop-animation');
        }, 300);
    }

    // Other existing code for has-value class
    inputFields.forEach(input => {
        input.addEventListener('input', function () {
            if (input.value.trim() !== "") {
                input.classList.add('has-value');
            } else {
                input.classList.remove('has-value');
            }
        });
    });

    // Add event listeners to all input fields
    function checkInputFields() {
        const hasValue = Array.from(inputFields).some(input => input.value.trim() !== '');
        updateButton.disabled = !hasValue;
    }
    inputFields.forEach(input => {
        input.addEventListener('input', checkInputFields);
    });

    // Initial check on page load
    checkInputFields();

    // Event listener for the "Save changes" button
    updateButton.addEventListener('click', async (e) => {
        e.preventDefault(); // Prevent form submission

        // Perform validation only when the button is clicked
        await validateUsername();

        // If there are any errors, do not proceed
        if (usernameInput.classList.contains('has-error')) {
            return;
        }

        // Proceed with the form submission or other logic if no errors
        console.log('Form is ready to be submitted or proceed with next steps.');
    });
});
