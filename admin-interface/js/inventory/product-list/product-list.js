// Generate Random Data
const statuses = ["active", "discontinued", "out-of-stock"];
const suppliers = ["Supplier A", "Supplier B", "Supplier C", "Supplier D"];

function generateRandomProduct(index) {
    const quantity = Math.floor(Math.random() * 200);
    const sold = Math.floor(Math.random() * 500);
    const price = (Math.random() * 100).toFixed(2);
    const revenue = (sold * price).toFixed(2);

    return {
        id: index + 1,
        name: `Product ${index + 1}`,
        description: `Description for Product ${index + 1}`,
        location: `Aisle ${Math.floor(Math.random() * 10)}, Shelf ${Math.floor(Math.random() * 5)}`,
        quantity,
        reorderPoint: 20,
        price,
        sold,
        totalRevenue: revenue,
        createdAt: `2023-01-${Math.floor(Math.random() * 28 + 1)}`,
        lastUpdated: `2024-02-${Math.floor(Math.random() * 28 + 1)}`,
        lastPurchaseDate: `2024-03-${Math.floor(Math.random() * 28 + 1)}`,
        supplier: suppliers[Math.floor(Math.random() * suppliers.length)],
        status: statuses[Math.floor(Math.random() * statuses.length)],
    };
}

function populateTable() {
    const tbody = document.getElementById("product-data");
    for (let i = 0; i < 376; i++) {
        const product = generateRandomProduct(i);
        const row = `
            <tr>
                <td>${product.id}</td>
                <td>${product.name}</td>
                <td>${product.description}</td>
                <td>${product.location}</td>
                <td>${product.quantity}</td>
                <td>${product.reorderPoint}</td>
                <td>₱${product.price}</td>
                <td>${product.sold}</td>
                <td>₱${product.totalRevenue}</td>
                <td>${product.createdAt}</td>
                <td>${product.lastUpdated}</td>
                <td>${product.lastPurchaseDate}</td>
                <td>${product.supplier}</td>
                <td>
                    <span class="status ${product.status}">
                        ${product.status.replace("-", " ").toUpperCase()}
                    </span>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    }
}

populateTable();