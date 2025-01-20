document.addEventListener("DOMContentLoaded", () => {
    let productData = []; // Holds product details from the server
    const productIdInput = document.getElementById("rr-product_id-input");
    const quantityInput = document.getElementById("rr-quantity-input");
    const addProductButton = document.getElementById("addreorderrequestButton");
    const confirmationModal = document.getElementById("confirmationModal3");
    const reorderModal = document.querySelector(".modal-request-reorder");
    const requestReorderDiv = document.querySelector(".pd7.button");
    const addReorderRequest = document.getElementById("addreorderRequest");
    const confirmRequestButton = document.getElementById("confirmRequest");
    const cancelRequestButton = document.getElementById("cancelRequest");

    // Fetch product data from the server
    fetch("db_queries/fetch_pd.php")
        .then((response) => response.json())
        .then((data) => {
            productData = data; // Save full product details
        })
        .catch((error) => console.error("Error fetching product data:", error));

    // Check if a reorder request already exists for the product ID
    const checkExistingReorderRequest = async (productId) => {
        try {
            const response = await fetch("db_queries/check_existing_request.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ product_id: productId }),
            });
            const data = await response.json();
            return data.exists; // `true` if request exists, otherwise `false`
        } catch (error) {
            console.error("Error checking existing request:", error);
            return false;
        }
    };

    // Function to check if both conditions are met to enable the button
    const updateAddButtonState = async () => {
        const inputProductId = productIdInput.value.trim();
        const inputQuantity = quantityInput.value.trim();

        const product = productData.find((p) => p.product_id === inputProductId);

        if (product) {
            const requestExists = await checkExistingReorderRequest(inputProductId);

            if (requestExists) {
                addProductButton.disabled = true;
                addProductButton.textContent = `Request already made for ${inputProductId}`;
            } else if (parseInt(inputQuantity) > 0) {
                addProductButton.disabled = false;
                addProductButton.textContent = `Request a reorder for ${inputProductId}`;
            } else {
                addProductButton.disabled = true;
                addProductButton.textContent = `${inputProductId} found, but quantity is required`;
            }
        } else {
            addProductButton.disabled = true;
            addProductButton.textContent = `Product ID: "${inputProductId}" doesn't exist`;
        }
    };

    // Show modal-request-reorder when .pd7.button is clicked
    requestReorderDiv.addEventListener("click", () => {
        reorderModal.classList.add("show");
    });

    // Live search and validate product ID input
    productIdInput.addEventListener("input", updateAddButtonState);

    // Update button state when quantity input changes
    quantityInput.addEventListener("input", updateAddButtonState);

    // Handle add product button click
    addProductButton.addEventListener("click", (event) => {
        event.preventDefault(); // Prevent form submission

        const inputProductId = productIdInput.value.trim();
        const inputQuantity = quantityInput.value.trim();

        const product = productData.find((p) => p.product_id === inputProductId);

        if (product) {
            // Populate confirmation modal with detailed product information
            addReorderRequest.innerHTML = `
                <li><strong>Product ID:</strong> ${inputProductId}</li>
                <li><strong>Product Name:</strong> ${product.product_name}</li>
                <li><strong>Reorder Point:</strong> ${product.reorder_point}</li>
                <li><strong>Current Quantity:</strong> ${product.quantity}</li>
                <li><strong>Requested Quantity:</strong> ${inputQuantity}</li>
            `;
            confirmationModal.style.display = "flex"; // Show confirmation modal
        }
    });

    // Close confirmation modal on cancel
    cancelRequestButton.addEventListener("click", () => {
        confirmationModal.style.display = "none";
    });

    // Handle confirmation logic
    confirmRequestButton.addEventListener("click", () => {
        const inputProductId = productIdInput.value.trim();
        const inputQuantity = quantityInput.value.trim();
        const productIdWithoutPrefix = inputProductId.replace(/^PRD-/, "");
    
        fetch("db_queries/reorder_request.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                product_id: productIdWithoutPrefix,
                quantity: inputQuantity,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Reorder request successfully added!");
                    confirmationModal.style.display = "none";
                    reorderModal.classList.remove("show");
                } else {
                    alert("Error: " + data.error);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred while processing the request.");
            });
    });
    

    // Close the modals if the user clicks outside of them
    document.addEventListener("click", (event) => {
        if (
            !confirmationModal.contains(event.target) &&
            !reorderModal.contains(event.target) &&
            event.target !== requestReorderDiv
        ) {
            confirmationModal.style.display = "none";
            reorderModal.classList.remove("show");
        }
    });

    // Close the reorder modal when clicking outside of it
    window.addEventListener("click", (event) => {
        if (event.target === reorderModal) {
            reorderModal.classList.remove("show");
        }
    });

    // Close the reorder modal when pressing the Escape key
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            reorderModal.classList.remove("show");
        }
    });
});
