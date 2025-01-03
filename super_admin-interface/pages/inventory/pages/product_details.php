<?php
// Ensure the global $conn variable is accessible
global $conn;




$query = "SELECT product_id, product_name, price, quantity, last_restocked FROM products";
$result = $conn->query($query);

// Check if the query was successful
if (!$result) {
    die('Error: Failed to execute the query. ' . $conn->error);
}
?>

<div class="product-details-grid grid-scrollbar-design">
    <div class="product-details-item grid-item-design title">Product Details</div>
    <div class="product-details-item grid-item-design pd1">
        <table class="product-table">
            <thead>
                 <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Last Restocked</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['last_restocked']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No products found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="product-details-item grid-item-design pd2">
    </div>
</div>

<?php
// Close the database connection
$conn->close();
?>
