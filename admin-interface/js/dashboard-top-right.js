// Helper function to format price with currency
function formatPrice(price) {
    return `₱${price.toLocaleString()}`;
}

// Collect all orders and summarize by product
const orders = Array.from(document.querySelectorAll('.order-item'));

// Store product counts and prices in a map
const productMap = new Map();

orders.forEach(order => {
    const productName = order.dataset.product;
    const price = parseFloat(order.dataset.price);
    const count = parseInt(order.dataset.count);

    if (productMap.has(productName)) {
        const existing = productMap.get(productName);
        existing.count += count; // Increment by the actual count, not just 1
        existing.totalPrice += price * count; // Multiply price by count for total
    } else {
        productMap.set(productName, { count: count, totalPrice: price * count });
    }
});

// Now update the DOM with the aggregated data
orders.forEach(order => {
    const productName = order.dataset.product;
    const { count, totalPrice } = productMap.get(productName);

    // Update the count
    const countElement = order.querySelector('.order-count');
    countElement.textContent = count;

    // Update the total price
    const priceElement = order.querySelector('.order-total-price');
    priceElement.textContent = formatPrice(totalPrice);

    // Update the status
    const status = order.dataset.status;
    const statusElement = order.querySelector('.order-status');
    statusElement.className = `order-status ${status}`; // Set the class based on status
    statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1); // Capitalize first letter
});
