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
    fetch('login-interface/php/login_metrics.php', {
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
