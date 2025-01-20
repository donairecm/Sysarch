document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('updateaccountdetialsForm');
    const updateButton = document.getElementById('updateaccountdetails');
    const confirmationModal = document.getElementById('confirmationModal5');
    const cancelButton = document.querySelector('#confirmationModal5 .md-btn-2');

    const inputFields = form.querySelectorAll('input'); // Select all text input fields

    // Fetch user data from the server
    fetch('../php/fetch_acc_details.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const userData = data.data;

                // Populate form labels with user data
                document.getElementById('uad-username').textContent = userData.username;
                document.getElementById('uad-first-name').textContent = userData.first_name;
                document.getElementById('uad-lname').textContent = userData.middle_name || 'enter middle name';
                document.getElementById('uad-mname').textContent = userData.last_name;
                document.getElementById('uad-email').textContent = userData.email;
                document.getElementById('uad-phone-num-1').textContent = userData.phone_number_1;
                document.getElementById('uad-phone-num-2').textContent = userData.phone_number_2 || 'enter phone number 2';
            } else {
                console.error('Error fetching user data:', data.error);
            }
        })
        .catch(error => {
            console.error('Request failed:', error);
        });

    // Function to open the modal
    function openModal() {
        confirmationModal.style.display = 'flex'; // Show the modal
    }

    // Function to close the modal
    function closeModal() {
        confirmationModal.style.display = 'none'; // Hide the modal
    }

    // Event listener for the "Save changes" button
    updateButton.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent form submission
        openModal(); // Open the modal
    });

    // Event listener for the "Cancel" button inside the modal
    cancelButton.addEventListener('click', () => {
        closeModal(); // Close the modal
    });

    // Close the modal if the user clicks outside the modal content
    confirmationModal.addEventListener('click', (event) => {
        if (event.target === confirmationModal) {
            closeModal(); // Close the modal
        }
    });

    // Close the modal when pressing the Escape key
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal(); // Close the modal
        }
    });

    // Check if at least one input field has a value
    function checkInputFields() {
        const hasValue = Array.from(inputFields).some(input => input.value.trim() !== '');
        updateButton.disabled = !hasValue;
    }
    

    // Add event listeners to all input fields
    inputFields.forEach(input => {
        input.addEventListener('input', checkInputFields);
    });

    // Initial check on page load
    checkInputFields();
});
