document.addEventListener('DOMContentLoaded', () => {
    const profilePreviewContainer = document.getElementById('profilePreviewContainer');
    const fileInput = document.getElementById('fileInput');
    const profilePicPreview = document.getElementById('profilePicPreview');
    const uploadButton = document.getElementById('uploadButton');
    const revertToOriginalButton = document.getElementById('reverttooriginal');
    const messageDiv = document.querySelector('.account-information-grid-item.grid-item-design-aig.aig1 .message');
    let originalProfilePicSrc = profilePicPreview.src;
    
    const updateprofilepicture = document.getElementById('updateprofilepicture');

    // Prevent default form submission
    updateprofilepicture.addEventListener('submit', (event) => {
        event.preventDefault();
    });

    // Make the profile preview container clickable
    profilePreviewContainer.addEventListener('click', () => {
        fileInput.click(); // Simulate file input click
    });

    // Handle file input change
    fileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];

        // Reset message state
        messageDiv.style.color = ''; // Reset to default color
        messageDiv.textContent = 'JPG or PNG no larger than 5mb'; // Default message
        uploadButton.disabled = true; // Keep the button disabled by default

        if (file) {
            // Check file type
            const validExtensions = ['image/jpeg', 'image/png'];
            if (!validExtensions.includes(file.type)) {
                messageDiv.textContent = 'Only JPG or PNG files are allowed.';
                messageDiv.style.color = 'red';
                return; // Exit if file type is invalid
            }

            // Check file size (5MB = 5242880 bytes)
            if (file.size > 5242880) {
                messageDiv.textContent = 'The file is too large. Please select a file smaller than 5MB.';
                messageDiv.style.color = 'red';
                return; // Exit if file size exceeds the limit
            }

            const reader = new FileReader();

            reader.onload = (e) => {
                profilePicPreview.src = e.target.result;

                // Enable the upload button if the new image is different
                if (profilePicPreview.src !== originalProfilePicSrc) {
                    uploadButton.disabled = false;
                    revertToOriginalButton.classList.add('show'); // Show revert button
                } else {
                    uploadButton.disabled = true;
                    revertToOriginalButton.classList.remove('show'); // Hide revert button
                }
            };

            reader.readAsDataURL(file);
        }
    });

    // Add click event listener to revert to original button
    revertToOriginalButton.addEventListener('click', () => {
        // Revert to the current profile picture (last saved profile picture)
        profilePicPreview.src = originalProfilePicSrc;

        // Reset the UI
        uploadButton.disabled = true; // Disable upload button
        revertToOriginalButton.classList.remove('show'); // Hide revert button
        messageDiv.style.color = ''; // Reset to default color
        messageDiv.textContent = 'JPG or PNG no larger than 5mb'; // Reset message to default
    });

    // Handle the form submission via AJAX
    updateprofilepicture.addEventListener('submit', (event) => {
        event.preventDefault(); // Prevent form submission

        const formData = new FormData();
        const file = fileInput.files[0];

        if (!file) {
            messageDiv.textContent = 'No file selected.';
            messageDiv.style.color = 'red';
            return;
        }

        // Append the file to the form data
        formData.append('profile_picture', file);

        // AJAX request to upload the profile picture
        fetch('../php/upload_profile_picture.php', {
            method: 'POST',
            body: formData,
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Update the original profile picture source
                originalProfilePicSrc = data.new_profile_picture;

                // Reset UI states
                uploadButton.disabled = true;
                revertToOriginalButton.classList.remove('show');
                messageDiv.style.color = 'green';
                messageDiv.textContent = 'Profile picture updated successfully!';
                
                // First alert
                alert('Profile picture updated successfully!');
                
                // Second alert to prompt for page reload
                if (confirm('Reload the page to see the changes?')) {
                    // Reload the page
                    location.reload();
                }
            } else {
                messageDiv.style.color = 'red';
                messageDiv.textContent = `Error: ${data.error}`;
                alert(`Error: ${data.error}`);
            }
        })
        .catch((error) => {
            messageDiv.style.color = 'red';
            messageDiv.textContent = 'An unexpected error occurred.';
            alert('An unexpected error occurred.');
            console.error(error);
        });
    });
});
