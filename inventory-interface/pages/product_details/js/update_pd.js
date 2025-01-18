document.addEventListener("DOMContentLoaded", () => {
    const saveButton_pd = document.getElementById("saveButton");
    const form_pd = document.getElementById("productForm");
    const inputs_pd = form_pd.querySelectorAll("input");
    const modal = document.getElementById("confirmationModal");
    const modalProductDetails = document.querySelector(".modal-product-details"); // Reference to modal-product-details
    const changeList = document.getElementById("changeList");
    const confirmButton = document.getElementById("confirmChanges");
    const cancelButton = document.getElementById("cancelChanges");

    let changes = []; // To store detected changes
    let changesData = {}; // Store field names and their new values

    // Enable the Save button if any input has a value
    inputs_pd.forEach(input => {
        input.addEventListener("input", () => {
            saveButton_pd.disabled = !Array.from(inputs_pd).some(inp => inp.value.trim() !== "");
        });
    });

    // Handle Save button click
    saveButton_pd.addEventListener("click", (event) => {
        event.preventDefault(); // Prevent form submission

        changes = []; // Reset changes
        changesData = {}; // Reset change data
        changeList.innerHTML = ""; // Clear previous changes in the modal

        inputs_pd.forEach(input => {
            const fieldId = input.id.replace("-input", ""); // Derive field ID (e.g., "md-product_name" -> "product_name")
            const label = document.getElementById(fieldId)?.innerText.trim(); // Get old value
            const newValue = input.value.trim();

            if (newValue && newValue !== label) {
                changes.push(`${fieldId.replace("md-", "").replace("_", " ").toUpperCase()}: ${label} change to ${newValue}?`);
                changesData[fieldId.replace("md-", "")] = newValue; // Add to changes data
                const li = document.createElement("li");
                li.textContent = `${fieldId.replace("md-", "").replace("_", " ").toUpperCase()}: ${label} change to ${newValue}?`;
                changeList.appendChild(li);
            }
        });

        if (changes.length > 0) {
            modal.style.display = "flex"; // Show the confirmation modal
        } else {
            console.log("No changes detected.");
        }
    });

    // Handle Confirm button in the modal
    confirmButton.addEventListener("click", async () => {
        const productId = document.getElementById("modal-product-id").innerText.trim();
        if (!productId) {
            alert("Product ID not found.");
            return;
        }

        try {
            // Send changes to the server
            const response = await fetch("db_queries/update_product.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ product_id: productId, updates: changesData })
            });

            const result = await response.json();
            if (result.success) {
                alert("Product updated successfully!");
                
                // Close confirmation modal
                modal.style.display = "none";

                // Clear inputs and reset the form
                form_pd.reset();

                // Remove 'show' class from modal-product-details
                modalProductDetails.classList.remove("show");
            } else {
                alert(`Error updating product: ${result.error}`);
            }
        } catch (error) {
            console.error("Error updating product:", error);
            alert("Failed to update product. Please try again.");
        }
    });

    // Handle Cancel button in the modal
    cancelButton.addEventListener("click", () => {
        console.log("Changes canceled.");
        modal.style.display = "none"; // Close the confirmation modal
    });

    // Close modal when clicking outside of it
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none"; // Hide confirmation modal
        }
    });
});
