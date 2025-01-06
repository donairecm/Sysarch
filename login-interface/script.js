// For validation if field has value
document.querySelectorAll('.input-group input').forEach(input => {
    input.addEventListener('input', function() {
        if (input.value.trim() !== "") {
            input.classList.add('has-value');
        } else {
            input.classList.remove('has-value');
        }
    });
});


// Login error handler
document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the page from reloading

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();

    const usernameError = document.getElementById('username-error');
    const passwordError = document.getElementById('password-error');

    // Clear existing tooltips
    usernameError.style.display = 'none';
    passwordError.style.display = 'none';

    // Check if both fields are empty
    if (!username && !password) {
        showTooltip(usernameError, 'Please enter your username'); // Show only username error
        return;
    }

    // Send AJAX request to `login_metrics.php` if fields are not both empty
    fetch('php/login_metrics.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            username: username,
            password: password,
            login: true
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // If login is successful, redirect
            window.location.href = data.redirect_url;
        } else {
            // If errors exist, display them in tooltips
            if (data.errors.username) {
                showTooltip(usernameError, data.errors.username);
            }
            if (data.errors.password) {
                showTooltip(passwordError, data.errors.password);
            }
        }
    })
    .catch(error => console.error('Error:', error));
});


// Hide tooltips when clicking outside the form
document.addEventListener('click', function(event) {
    const form = document.getElementById('login-form');
    if (!form.contains(event.target)) {
        hideTooltip('username-error');
        hideTooltip('password-error');
    }
});

// Function to show a tooltip with animation
function showTooltip(element, message) {
    element.textContent = message;
    element.style.display = 'block';
    element.classList.add('pop-animation');
    
    // Add the .has-error class to the corresponding input field
    const inputField = element.id.includes('username') ? document.getElementById('username') : document.getElementById('password');
    inputField.classList.add('has-error');

    setTimeout(() => {
        element.classList.remove('pop-animation');
    }, 300); // Remove animation class after it plays
}

// Function to hide a tooltip
function hideTooltip(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.display = 'none';

        // Remove the .has-error class from the corresponding input field
        const inputField = elementId.includes('username') ? document.getElementById('username') : document.getElementById('password');
        inputField.classList.remove('has-error');
    }
}

// Funtion to toggle password visibility
// Handle both tooltip hiding and icon visibility on focus and blur
const passwordField = document.getElementById('password');
const openEyeIcon = document.getElementById('open-eye');
const closedEyeIcon = document.getElementById('closed-eye');
const togglePasswordIcon = document.querySelector('.toggle-password');

// Prevent input blur when clicking on the toggle icon
togglePasswordIcon.addEventListener('mousedown', function(event) {
    event.preventDefault();  // Prevents the blur event on input
});

passwordField.addEventListener('focus', function() {
    hideTooltip('password-error');  // Hide the tooltip
    showPasswordIcons();            // Show the appropriate icon
});

passwordField.addEventListener('blur', function() {
    hidePasswordIcons();            // Hide both icons
});

// Functions to show and hide icons based on password field state
function showPasswordIcons() {
    // Toggling password visibility
    if (passwordField.type === 'password') {
        openEyeIcon.style.display = 'block';
        closedEyeIcon.style.display = 'none';
    } else {
        openEyeIcon.style.display = 'none';
        closedEyeIcon.style.display = 'block';
    }

    // Adjust padding of input field
    const inputFields = document.querySelectorAll('.input-group input');
    inputFields.forEach(input => {
        input.style.padding = '13px 40px 13px 15px';
    });
}


function hidePasswordIcons() {
    // Hide both icons
    openEyeIcon.style.display = 'none';
    closedEyeIcon.style.display = 'none';

    // Reset padding of input fields
    const inputFields = document.querySelectorAll('.input-group input');
    inputFields.forEach(input => {
        input.style.padding = '13px 15px';
    });
}


// Toggle password visibility and icons
function togglePasswordVisibility() {
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        openEyeIcon.style.display = 'none';
        closedEyeIcon.style.display = 'block';
    } else {
        passwordField.type = 'password';
        openEyeIcon.style.display = 'block';
        closedEyeIcon.style.display = 'none';
    }
}
