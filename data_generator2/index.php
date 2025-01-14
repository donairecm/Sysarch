<?php
// Include the database connection at the top of mainpage.php
require_once 'db/db_connection.php';

// include 'something.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tables</title>
        <link rel="stylesheet" href="css/style.css">
        <script type="text/javascript" src="js/script.js" defer></script>
    </head>
<body>

<div class="options-grid">
    <!-- Sales Orders -->
    <div class="option-item oi1" data-table="sales_orders">
        <div class="item-name">Sales Orders</div>
    </div>

    <!-- Order Items -->
    <div class="option-item oi2" data-table="order_items">
        <div class="item-name">Order Items</div>
    </div>

    <!-- Supply-chain Orders -->
    <div class="option-item oi3" data-table="supply_chain_orders">
        <div class="item-name">Supply-chain Orders</div>
    </div>

    <!-- Reorder Requests -->
    <div class="option-item oi4" data-table="reorder_requests">
        <div class="item-name">Reorder Requests</div>
    </div>

    <!-- Inventory Movements -->
    <div class="option-item oi5" data-table="inventory_movements">
        <div class="item-name">Inventory Movements</div>
    </div>

    <!-- User Activities -->
    <div class="option-item oi6" data-table="user_activities">
        <div class="item-name">User Activities</div>
    </div>

    <!-- Products -->
    <div class="option-item oi7" data-table="products">
        <div class="item-name">Products</div>
    </div>

    <!-- Customers -->
    <div class="option-item oi8" data-table="customers">
        <div class="item-name">Customers</div>
    </div>

    <!-- Customers -->
    <div class="option-item oi9" data-table="users">
        <div class="item-name">Users</div>
    </div>
</div>
<div class="overlay" id="overlay">
    <div class="table-container" id="table-container">
        <div class="top-contents">
            <div class="table-name-container" id="table-name"></div>
            <div class="left-contents">
                <div class="sort-container" id="sort-container">Sort</div>
                <div class="search-bar">
                    <input type="text" id="search-input" placeholder="Search...">
                    <button id="search-button">Search</button>
                </div>
            </div>
        </div>
        <div id="table-content"></div>
    </div>
</div>

<div class="button-container">
    <div class="reset-quantity">
        <div class="item-name">Reset Quantity</div>
    </div>

    <div class="reset-tables">
        <div class="item-name">Reset the tables</div>
    </div>

    <div class="generate-data">
        <div class="item-name">Generate data</div>
    </div>
</div>

</body>
</html>
