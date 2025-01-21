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
    const phone1Error = document.getElementById('pnum1-error');
    const phone2Error = document.getElementById('pnum2-error');
    const inputFields = document.querySelectorAll('.input-group-aig input');
    const emailError = document.getElementById('aig-email-error'); 
    const fnameError = document.getElementById('fname-error');
    const mnameError = document.getElementById('mname-error');
    const lnameError = document.getElementById('lname-error');

    let userId = null; // To store the user's ID globally
    let fetchedDetails = {}; 

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
            mnameLabel.textContent = data.last_name || 'N/A'; // This was incorrectly set
            lnameLabel.textContent = data.middle_name || 'N/A'; // This was incorrectly set
            phone1Label.textContent = data.phone_number_1 || 'N/A';
            phone2Label.textContent = data.phone_number_2 || 'N/A';
    
            // Populate fetchedDetails for validation
            fetchedDetails = {
                username: data.username || '',
                email: data.email || '',
                first_name: data.first_name || '',
                middle_name: data.middle_name || '',
                last_name: data.last_name || '',
                phone_number_1: data.phone_number_1 || '',
                phone_number_2: data.phone_number_2 || '',
            };
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

    // Function to validate phone number
    async function validatePhoneNumber(phoneInput) {
        const phoneNumber = phoneInput.value.trim();
        const inputGroup = phoneInput.closest('.input-group-aig');
        const phoneError = inputGroup?.querySelector('.account-details-errors');
        
        if (phoneError) hideTooltip(phoneError); // Hide tooltip
    
        const validPattern = /^09\d{9}$/; // Matches 11 digits starting with 09
    
        if (!phoneNumber) {
            return; // Skip validation for empty input
        }
    
        if (!validPattern.test(phoneNumber)) {
            showTooltip(phoneError, 'Please input a legitimate cellphone number (e.g., 09123456789)');
            phoneInput.classList.add('has-error');
            return; // Stop further processing if invalid format
        }
    
        if (phoneNumber === '09000000000') {
            showTooltip(phoneError, 'The phone number cannot be 09000000000');
            phoneInput.classList.add('has-error');
            return; // Stop further processing if invalid number
        }
    
        try {
            console.log('Validating phone number on the server...');
            const response = await fetch('../php/val_accdetails_fields.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    phone_number_1: phoneNumber, 
                    exclude_id: userId 
                }),
            });
    
            const responseText = await response.text();
            console.log('Server response text:', responseText);
    
            try {
                const result = JSON.parse(responseText);
                console.log('Phone number validation result:', result);
    
                // Log inputted and compared phone numbers
                console.log('Inputted phone number:', phoneNumber);
                console.log('Compared phone number from DB (phone_number_1):', result.phone_number_1 || 'N/A');
                console.log('Compared phone number from DB (phone_number_2):', result.phone_number_2 || 'N/A');
                console.log('Type of inputted phone number:', isNaN(phoneNumber) ? 'varchar' : 'int');
                console.log('Type of stored phone number:', result.phone_number_1 ? (isNaN(result.phone_number_1) ? 'varchar' : 'int') : 'N/A');
    
                if (result.phone_exists) {
                    showTooltip(phoneError, result.phone_message || 'Phone number error');
                    phoneInput.classList.add('has-error');
                } else {
                    phoneInput.classList.remove('has-error');
                }
            } catch (jsonError) {
                console.error('Error parsing JSON:', jsonError);
                showTooltip(phoneError, 'An error occurred while validating the phone number');
                phoneInput.classList.add('has-error');
            }
        } catch (error) {
            console.error('Error validating phone number:', error);
            showTooltip(phoneError, 'An error occurred while validating the phone number');
            phoneInput.classList.add('has-error');
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
    console.log('Submit button clicked.');
console.log('Fetched Details:', fetchedDetails);


    function validateNameField(input, fetchedValue, errorElement, fieldName) {
        const nameValue = input.value.trim();
    
        console.log(`Validating ${fieldName}: input=${nameValue}, fetched=${fetchedValue}`);
        hideTooltip(errorElement);
    
        if (!nameValue) return;
    
        if (nameValue === fetchedValue) {
            showTooltip(errorElement, `${nameValue} is already set as your ${fieldName.toLowerCase()}`);
            input.classList.add('has-error');
        } else {
            input.classList.remove('has-error');
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
        const inputGroup = element.closest('.input-group-aig');
        if (inputGroup) { // Check if the closest input group exists
            const inputField = inputGroup.querySelector('input');
            if (inputField) {
                inputField.classList.remove('has-error');
            }
        }
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

    document.getElementById('confirmemployeedetailschange').addEventListener('click', () => {
        console.log('Changes confirmed.');
        // Perform the actual update (e.g., send the changes to the server)
        const confirmationModal = document.getElementById('confirmationModal5');
        confirmationModal.style.display = 'none'; // Hide the modal
    });
    
    document.querySelector('.md-btn-2').addEventListener('click', () => {
        console.log('Changes canceled.');
        const confirmationModal = document.getElementById('confirmationModal5');
        confirmationModal.style.display = 'none'; // Hide the modal
    });
    

    // Validate username and email on form submission
    updateButton.addEventListener('click', async (e) => {
        e.preventDefault(); // Prevent form submission
    
        console.log('Submit button clicked.');
    
        // Run all validations
        await validateUsername();
        await validateEmail();
        await validatePhoneNumber(phone1Input);
        await validatePhoneNumber(phone2Input);
    
        // Validate name fields
        validateNameField(fnameInput, fetchedDetails.first_name, fnameError, 'First name');
        validateNameField(mnameInput, fetchedDetails.middle_name, mnameError, 'Middle name');
        validateNameField(lnameInput, fetchedDetails.last_name, lnameError, 'Last name');
    
        // Check for errors
        if (
            usernameInput.classList.contains('has-error') ||
            emailInput.classList.contains('has-error') ||
            phone1Input.classList.contains('has-error') ||
            fnameInput.classList.contains('has-error') ||
            mnameInput.classList.contains('has-error') ||
            lnameInput.classList.contains('has-error') ||
            phone2Input.classList.contains('has-error')
        ) {
            console.log('Form submission blocked due to errors.');
            return;
        }
    
        // Identify changes
        const changes = [];
        const updates = {};
        const activities = [];
    
        const logChange = (fieldName, fetchedValue, inputValue, dbField, activityLabel) => {
            const newValue = inputValue.trim();
            if (newValue && newValue !== (fetchedValue || '')) {
                changes.push(`${fieldName}: ${fetchedValue || 'N/A'} → ${newValue}`);
                updates[dbField] = newValue; // Prepare the update payload
                activities.push({
                    details: `Changed user's ${activityLabel} from ${fetchedValue || 'N/A'} to ${newValue}`,
                    dbField: dbField
                });
            }
        };
    
        logChange('Username', fetchedDetails.username, usernameInput.value, 'username', 'username');
        logChange('Email', fetchedDetails.email, emailInput.value, 'email', 'email');
        logChange('First Name', fetchedDetails.first_name, fnameInput.value, 'first_name', 'first name');
        logChange('Middle Name', fetchedDetails.middle_name, mnameInput.value, 'middle_name', 'middle name');
        logChange('Last Name', fetchedDetails.last_name, lnameInput.value, 'last_name', 'last name');
        logChange('Phone Number 1', fetchedDetails.phone_number_1, phone1Input.value, 'phone_number_1', '1st phone number');
        logChange('Phone Number 2', fetchedDetails.phone_number_2, phone2Input.value, 'phone_number_2', '2nd phone number');
    
        if (changes.length === 0) {
            console.log('No changes detected.');
            alert('No changes were made.');
            return;
        }
    
        // Show changes in the modal
        const changesList = document.getElementById('updatedemployeedetails');
        changesList.innerHTML = '';
        changes.forEach(change => {
            const li = document.createElement('li');
            li.textContent = change;
            changesList.appendChild(li);
        });
    
        // Show modal
        const confirmationModal = document.getElementById('confirmationModal5');
        confirmationModal.style.display = 'flex';
    
        console.log('Changes:', changes);
    
        // Confirm and Save Changes
        document.getElementById('confirmemployeedetailschange').addEventListener('click', async () => {
            confirmationModal.style.display = 'none'; // Hide modal
    
            try {
                // Update `users` table
                const updateResponse = await fetch('../php/update_user_details.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        employee_id: userId,
                        updates
                    })
                });
    
                const updateResult = await updateResponse.json();
                if (!updateResult.success) {
                    console.error('Failed to update user:', updateResult.error);
                    alert('Error updating user information.');
                    return;
                }
    
                console.log('User updated successfully:', updateResult);
    
                // Log changes in `user_activities` table
                for (const activity of activities) {
                    const logResponse = await fetch('../php/update_user_details.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            performed_by: userId,
                            activity_type: 'account changes',
                            details: activity.details,
                            date_of_activity: new Date().toISOString() // Add current date and time
                        })
                    });
    
                    const logResult = await logResponse.json();
                    if (!logResult.success) {
                        console.error('Failed to log activity:', logResult.error);
                    } else {
                        console.log('Activity logged successfully:', logResult);
                    }
                }
    
                alert('Changes saved and logged successfully!');
            } catch (error) {
                console.error('Error saving changes:', error);
                alert('An error occurred while saving changes.');
            }
        });
    });
    
    
    
    
    
    
    
    
});
