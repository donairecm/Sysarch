document.addEventListener('DOMContentLoaded', () => {
    // Fetch the inventory value from the backend
    fetch('db_queries/calc_in_val.php')
        .then(response => response.json())
        .then(data => {
            if (data.inventoryValue) {
                // Format the value with commas and no cents
                const formattedValue = Math.floor(data.inventoryValue).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                // Update the inventory value in the DOM
                const inventoryValueElement = document.querySelector('.im1 .value');
                inventoryValueElement.innerHTML = `<span>₱</span>${formattedValue}`; // Manually add the peso sign
            } else {
                console.error('Error fetching inventory value:', data.error || 'Unknown error');
            }
        })
        .catch(error => console.error('Error:', error));
});
