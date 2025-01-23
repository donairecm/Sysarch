document.addEventListener("DOMContentLoaded", function() {
    fetch('db_queries/fetch_pd.php')
        .then(response => response.json())
        .then(products => {
            const productMap = new Map(products.map(product => [product.product_id, product]));
            const productIdInput = document.getElementById("aoi-product_id-input");
            const quantityInput = document.getElementById("aoi-quantity-input");
            const addButton = document.getElementById("additemtosale");
            const orderListContainer = document.querySelector(".orderlist-container");
            const modal = document.querySelector(".modal-order-items-attached.modal-style.grid-scrollbar-design");
            const createOrderButton = document.querySelector(".sales-manage-orders-item.grid-item-design-ms.ms1.tabs");

            function resetForm() {
                productIdInput.value = '';
                quantityInput.value = '';
                addButton.innerHTML = 'Add item';
                addButton.disabled = true;
            }

            function addToOrderList(productId, productName, quantity) {
                const li = document.createElement('li');
                li.classList.add('item');

                const productIdSpan = document.createElement('span');
                productIdSpan.textContent = productId;

                const productNameSpan = document.createElement('span');
                productNameSpan.textContent = productName;

                const quantitySpan = document.createElement('span');
                quantitySpan.textContent = quantity;

                const removeSpan = document.createElement('span');
                const removeSvg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                removeSvg.setAttribute("viewBox", "0 -960 960 960");
                const removePath = document.createElementNS("http://www.w3.org/2000/svg", "path");
                removePath.setAttribute("d", "M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm80-160h80v-360h-80v360Zm160 0h80v-360h-80v360Z");
                removeSvg.appendChild(removePath);
                removeSpan.appendChild(removeSvg);

                removeSpan.addEventListener('click', () => {
                    orderListContainer.removeChild(li);
                });

                li.appendChild(productIdSpan);
                li.appendChild(productNameSpan);
                li.appendChild(quantitySpan);
                li.appendChild(removeSpan);

                orderListContainer.appendChild(li);
            }

            addButton.addEventListener("click", function(event) {
                event.preventDefault();
                const productId = productIdInput.value.trim();
                const quantity = quantityInput.value.trim();

                if (productId && quantity && productMap.has(productId)) {
                    const product = productMap.get(productId);
                    addToOrderList(productId, product.product_name, quantity);
                    resetForm();
                }
            });

            productIdInput.addEventListener("input", function() {
                const productId = productIdInput.value.trim();
                if (!productId) {
                    addButton.innerHTML = 'Add item';
                    addButton.disabled = true;
                    return;
                }

                if (!productMap.has(productId)) {
                    addButton.innerHTML = `${productId} not found.`;
                    addButton.disabled = true;
                    return;
                }

                if (!quantityInput.value.trim()) {
                    addButton.innerHTML = `${productId} found but enter a quantity.`;
                    addButton.disabled = true;
                    return;
                }

                addButton.innerHTML = 'Add item';
                addButton.disabled = false;
            });

            quantityInput.addEventListener("input", function() {
                const productId = productIdInput.value.trim();
                if (!productId || !productMap.has(productId)) {
                    addButton.innerHTML = `${productId} not found.`;
                    addButton.disabled = true;
                    return;
                }

                if (!quantityInput.value.trim()) {
                    addButton.innerHTML = `${productId} found but enter a quantity.`;
                    addButton.disabled = true;
                    return;
                }

                addButton.innerHTML = 'Add item';
                addButton.disabled = false;
            });

            // Show the modal when the "Create an order" div is clicked
            createOrderButton.addEventListener("click", () => {
                modal.classList.add("show");
            });

            // Hide the modal when clicking outside the modal content
            modal.addEventListener("click", (event) => {
                if (!event.target.closest(".modal-content")) {
                    modal.classList.remove("show");
                }
            });
        })
        .catch(error => {
            console.error('Error fetching product data:', error);
        });
});