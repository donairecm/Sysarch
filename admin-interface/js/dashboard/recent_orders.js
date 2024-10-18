document.addEventListener("DOMContentLoaded", function () {
  const products = [
    'Aluminum Sheets', 'Aluminum Pipes', 'Aluminum Foil Rolls',
    'Aluminum Coils', 'Aluminum Bars', 'Aluminum Extrusions',
    'Aluminum Rods', 'Aluminum Wire', 'Aluminum Angles',
    'Aluminum Tubes', 'Aluminum Panels', 'Aluminum Frames'
  ];

  const statuses = ['pending', 'on-process', 'completed', 'refunded']; // Includes 'refunded'

  // Function to generate random integer between min and max
  function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }

  // Function to generate random status
  function getRandomStatus() {
    return statuses[getRandomInt(0, statuses.length - 1)];
  }

  // Function to generate random orders
  function generateOrders() {
    const orderList = document.querySelector('.recent-orders-list');
    orderList.innerHTML = ''; // Clear existing orders

    // Header row without Product ID
    const headerHTML = `
      <li class="order-header">
        <span class="header-product-name">Product</span>
        <span class="header-order-count">Count</span>
        <span class="header-order-price">Total Price</span>
        <span class="header-order-status">Status</span>
      </li>`;
    orderList.insertAdjacentHTML('beforeend', headerHTML);

    // Shuffle products to make the list different each time
    const shuffledProducts = products.sort(() => 0.5 - Math.random());

    // Generate random orders
    shuffledProducts.forEach(product => {
      const count = getRandomInt(1, 5); // Random count between 1 and 5
      const price = getRandomInt(2000, 15000); // Random price between 2000 and 15000
      const status = getRandomStatus();
      const totalPrice = count * price;

      const orderHTML = `
        <li class="order-item" data-product="${product}" data-price="${price}" data-count="${count}" data-status="${status}">
          <span class="product-name">${product}</span>
          <span class="order-count">${count}</span>
          <span class="order-total-price">₱${totalPrice.toLocaleString()}</span>
          <span class="order-status ${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>
        </li>`;

      orderList.insertAdjacentHTML('beforeend', orderHTML);
    });
  }

  // Generate orders on page load
  generateOrders();

  // Optional: Regenerate orders every minute (for demonstration purposes)
  setInterval(generateOrders, 60000); // Refresh orders every 60 seconds
});
