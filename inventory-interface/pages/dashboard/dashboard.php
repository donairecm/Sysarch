
<div class="inventory-grid grid-scrollbar-design">
        <div class="inventory-item grid-item-design title">Inventory Management</div>
        <div class="inventory-item grid-item-design top-row im1">
            <div class="top">
                <div class="text">
                    <div class="name">Inventory Value</div>
                    <div class="value"><span>₱</span>24,051</div>
                </div>
                <div class="svg-container">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                       <path d="M160-160v-440h160v440H160Zm0-480v-160h160v160H160Zm240 480v-320h160v320H400Zm0-360v-160h160v160H400Zm240 360v-200h160v200H640Zm0-240v-160h160v160H640Z"/>
                    </svg>
                </div>
            </div>
            <div class="dashboard-analytics">
                <span>+3%</span> more than last month
            </div>
        </div>
        <div class="inventory-item grid-item-design top-row im2">
            <div class="top">
                <div class="text">
                    <div class="name">Inventory Turnover</div>
                    <div class="value">4.7</div>
                </div>
                <div class="svg-container">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="m280-120-56-56 63-66q-106-12-176.5-91.5T40-520q0-117 81.5-198.5T320-800h120v80H320q-83 0-141.5 58.5T120-520q0 72 46 127t117 69l-59-59 56-57 160 160-160 160Zm240-40v-280h360v280H520Zm0-360v-280h360v280H520Zm80-80h200v-120H600v120Z"/>
                    </svg>
                </div>
            </div>
            <div class="dashboard-analytics">
                <span>+1.2</span> more than last week
            </div>
        </div>
        <div class="inventory-item grid-item-design inventory-activities im3">
            <span class="item-name">Inventory Movements</span>

            <div class="inventory-activities-container container_scrollbar_design">
                <ul class="inventory-activities-list">
                    <!-- Header Row -->
                    <li class="inventory-activities-header">
                        <span class="inventory-activities-header-product-id">Product ID</span>
                        <span class="inventory-activities-header-quantity">Quantity</span>
                        <span class="inventory-activities-header-activity">Activity</span>
                        <span class="inventory-activities-header-date">Date</span>
                    </li>
                    <!-- Rows will be dynamically inserted here -->
                </ul>
            </div>
        </div>

        <!-- Stock Levels -->
        <div class="inventory-item grid-item-design im4">
            <div class="item-name">Stock Levels</div>
            <div class="frame">
                <div class="text">*There are<div class="needs-restocking"></div>products that needs restocking</div>
                <div class="container">
                    <canvas id="dashboardStockLevelChart"></canvas>
                </div>
            </div>
        </div>

    </div>