document.addEventListener("DOMContentLoaded", () => {
    const modalAddProducts = document.querySelector(".modal-add-products");
    const inventoryProductDetailsItem = document.querySelector(".inventory-product-details-item.pd2");
    const addProductForm = document.getElementById("addproductsForm");
    const addProductButton = document.getElementById("addproductButton");
    const inputs = addProductForm.querySelectorAll("input");
    const confirmationModal2 = document.getElementById("confirmationModal2");
    const addProductDetails = document.getElementById("addProductDetails"); // Fixed ID to match HTML
    const confirmCreation = document.getElementById("confirmCreation");
    const cancelCreation = document.getElementById("cancelCreation");

    // Show modal-add-products when inventory-product-details-item pd2 is clicked
    if (inventoryProductDetailsItem) {
        inventoryProductDetailsItem.addEventListener("click", () => {
            modalAddProducts.classList.add("show");
        });
    }

    // Enable the Add button only if all fields have valid inputs
    inputs.forEach((input) => {
        input.addEventListener("input", () => {
            const allFieldsFilled = Array.from(inputs).every(inp => inp.value.trim() !== "");
            addProductButton.disabled = !allFieldsFilled;
        });
    });

    // Show confirmationModal2 and list product details when Add button is clicked
    addProductButton.addEventListener("click", (event) => {
        event.preventDefault(); // Prevent form submission

        // Clear previous details in confirmation modal
        addProductDetails.innerHTML = "";

        // Populate product details in confirmation modal
        const details = [
            { label: "Product name", value: document.getElementById("ap-product_name-input").value.trim() },
            { label: "Quantity", value: document.getElementById("ap-quantity-input").value.trim() },
            { label: "Reorder point", value: document.getElementById("ap-reorder_point-input").value.trim() },
            { label: "Price", value: document.getElementById("ap-price-input").value.trim() },
            { label: "Reorder cost", value: document.getElementById("ap-reorder_cost-input").value.trim() },
            { label: "Location", value: document.getElementById("ap-location-input").value.trim() },
        ];

        details.forEach(detail => {
            const li = document.createElement("li");
            li.textContent = `${detail.label}: ${detail.value}`;
            addProductDetails.appendChild(li);
        });

        // Show the confirmation modal
        confirmationModal2.style.display = "flex";
    });

    // Handle Confirm button in confirmation modal
    confirmCreation.addEventListener("click", async () => {
        // Collect product details
        const productData = {
            product_name: document.getElementById("ap-product_name-input").value.trim(),
            quantity: document.getElementById("ap-quantity-input").value.trim(),
            reorder_point: document.getElementById("ap-reorder_point-input").value.trim(),
            price: document.getElementById("ap-price-input").value.trim(),
            reorder_cost: document.getElementById("ap-reorder_cost-input").value.trim(),
            stock_location: document.getElementById("ap-location-input").value.trim(),
        };
    
        try {
            // Send product data to the server
            const response = await fetch("db_queries/add_product.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(productData),
            });
    
            const result = await response.json();
            if (result.success) {
                alert("Product added successfully!");
                confirmationModal2.style.display = "none"; // Hide confirmation modal
                modalAddProducts.classList.remove("show"); // Remove 'show' class to hide add products modal
                addProductForm.reset(); // Clear form fields
                addProductButton.disabled = true; // Disable the Add button again
            } else {
                alert(`Error adding product: ${result.error}`);
            }
        } catch (error) {
            console.error("Error adding product:", error);
            alert("Failed to add product. Please try again.");
        }
    });
    

    // Handle Cancel button in confirmation modal
    cancelCreation.addEventListener("click", () => {
        confirmationModal2.style.display = "none"; // Hide confirmation modal
    });

    // Close modal when clicking outside of it
    window.addEventListener("click", (event) => {
        if (event.target === confirmationModal2) {
            confirmationModal2.style.display = "none"; // Hide confirmation modal
        }
        if (event.target === modalAddProducts) {
            modalAddProducts.classList.remove("show"); // Hide add product modal
        }
    });

    // Close modal when pressing the Escape key
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            confirmationModal2.style.display = "none"; // Hide confirmation modal
            modalAddProducts.classList.remove("show"); // Hide add product modal
        }
    });
});
