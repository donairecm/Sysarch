
<div class="inventory-grid grid-scrollbar-design">
        <div class="inventory-item grid-item-design title">Inventory Management</div>
        <div class="inventory-item grid-item-design top-row im1">
            <div class="top">
                <div class="text">
                    <div class="name">Inventory Value</div>
                    <div class="value">...</div>
                </div>
                <div class="svg-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                       <path d="M160-160v-440h160v440H160Zm0-480v-160h160v160H160Zm240 480v-320h160v320H400Zm0-360v-160h160v160H400Zm240 360v-200h160v200H640Zm0-240v-160h160v160H640Z"/>
                    </svg>
                </div>
            </div>
            <div class="dashboard-analytics">
                <span></span>loading values...
            </div>
        </div>
        
        <div class="inventory-item grid-item-design top-row im2">
            <div class="top">
                <div class="text">
                    <div class="name">Inventory Turnover</div>
                    <div class="value">...</div>
                </div>
                <div class="svg-container">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="m280-120-56-56 63-66q-106-12-176.5-91.5T40-520q0-117 81.5-198.5T320-800h120v80H320q-83 0-141.5 58.5T120-520q0 72 46 127t117 69l-59-59 56-57 160 160-160 160Zm240-40v-280h360v280H520Zm0-360v-280h360v280H520Zm80-80h200v-120H600v120Z"/>
                    </svg>
                </div>
            </div>
            <div class="dashboard-analytics">
                <span></span>loading values...
            </div>
        </div>

        <div class="inventory-item grid-item-design top-row im8">
            <div class="top">
                <div class="text">
                    <div class="name">Total Units Sold</div>
                    <div class="value">...</div>
                </div>
                <div class="svg-container">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M440-183v-274L200-596v274l240 139Zm80 0 240-139v-274L520-457v274Zm-80 92L160-252q-19-11-29.5-29T120-321v-318q0-22 10.5-40t29.5-29l280-161q19-11 40-11t40 11l280 161q19 11 29.5 29t10.5 40v318q0 22-10.5 40T800-252L520-91q-19 11-40 11t-40-11Zm200-528 77-44-237-137-78 45 238 136Zm-160 93 78-45-237-137-78 45 237 137Z"/>
                    </svg>
                </div>
            </div>
            <div class="dashboard-analytics">
                <span></span>loading values...
            </div>
        </div>

        <div class="inventory-item grid-item-design top-row im9">
            <div class="top">
                <div class="text">
                    <div class="name">Total Units Restocked</div>
                    <div class="value">...</div>
                </div>
                <div class="svg-container">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M280-80 120-240l160-160 56 58-62 62h406v-160h80v240H274l62 62-56 58Zm-80-440v-240h486l-62-62 56-58 160 160-160 160-56-58 62-62H280v160h-80Z"/>
                    </svg>
                </div>
            </div>
            <div class="dashboard-analytics">
                <span></span>loading values...
            </div>
        </div>
        <div class="inventory-item grid-item-design inventory-activities im3">
            <div class="item-name">Inventory Movements</div>

            <div class="inventory-activities-container container_scrollbar_design">
                <ul class="inventory-activities-list">
                    <!-- Header Row -->
                    <li class="inventory-activities-header">
                    <span class="inventory-activities-header-movement-id">Movement ID</span>
                        <span class="inventory-activities-header-product-id">Product ID</span>
                        <span class="inventory-activities-header-quantity">Quantity</span>
                        <span class="inventory-activities-header-activity">Activity</span>
                        <span class="inventory-activities-header-date">Time</span>
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