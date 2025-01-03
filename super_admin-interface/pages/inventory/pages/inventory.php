
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
                        <span class="inventory-activities-header-employee">Employee Name</span>
                        <span class="inventory-activities-header-product-id">Product ID</span>
                        <span class="inventory-activities-header-quantity">Quantity</span>
                        <span class="inventory-activities-header-activity">Activity</span>
                        <span class="inventory-activities-header-date">Date</span>
                    </li>
                    <!-- Rows will be dynamically inserted here -->
                </ul>
            </div>
        </div>

        <div class="inventory-item grid-item-design im4">
            <div class="item-name">Inventory Movements History</div>
            <div class="container">
                <canvas id="inventorydashboardInventoryMovements"></canvas>
            </div>
        </div>
        <div class="inventory-item grid-item-design im5 chart-tab">
            <div class="svg-container">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                    <path d="M204-318q-22-38-33-78t-11-82q0-134 93-228t227-94h7l-64-64 56-56 160 160-160 160-56-56 64-64h-7q-100 0-170 70.5T240-478q0 26 6 51t18 49l-60 60ZM481-40 321-200l160-160 56 56-64 64h7q100 0 170-70.5T720-482q0-26-6-51t-18-49l60-60q22 38 33 78t11 82q0 134-93 228t-227 94h-7l64 64-56 56Z"/>
                </svg>
            </div>
            <div class="text">Reorder points</div>
        </div>
        <div class="inventory-item grid-item-design im6 chart-tab">
            <div class="svg-container">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                    <path d="M160-160v-120h160v120H160Zm0-160v-160h160v160H160Zm0-200v-280h160v280H160Zm240 360v-280h160v280H400Zm0-320v-160h160v160H400Zm0-200v-120h160v120H400Zm240 520v-80h160v80H640Zm0-120v-160h160v160H640Zm0-200v-320h160v320H640Z"/>
                </svg>
            </div>
            <div class="text">Stock levels</div>
        </div>
        <div class="inventory-item grid-item-design im7 chart-tab">
            <div class="svg-container">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                    <path d="M320-280q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm0-160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 320h240v-80H440v80Zm0-160h240v-80H440v80Zm0-160h240v-80H440v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/>
                </svg>
            </div>
            <div class="text">Product details</div>
        </div>

    </div>