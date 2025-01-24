
    <div class="dashboard-grid grid-scrollbar-design">
        <div class="dashboard-item grid-item-design title">Dashboard</div>

   

        <!-- Inventory Value -->
        <div class="dashboard-item grid-item-design top-row inventory-value im1">
            <div class="top">
                <div class="text">
                    <div class="name">Inventory Value</div>
                    <div class="value">...</div>
                </div>
                <div class="svg-container">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                       <path d="M160-160v-440h160v440H160Zm0-480v-160h160v160H160Zm240 480v-320h160v320H400Zm0-360v-160h160v160H400Zm240 360v-200h160v200H640Zm0-240v-160h160v160H640Z"/>
                    </svg>
                </div>
            </div>
            <div class="dashboard-analytics">
                <span></span> loading values...
            </div>
        </div>

        

        <!-- Inventory Turnover Rate -->
        <div class="dashboard-item grid-item-design top-row inventory-turnover-rate">
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



        <!-- Stock Levels -->
        <div class="dashboard-item grid-item-design ex1">
            <div class="item-name">Stock Levels</div>
            <div class="frame">
                <div class="text">*There are<div class="needs-restocking"></div>products that needs restocking</div>
                <div class="container">
                    <canvas id="dashboardStockLevelChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Units Sold per Product -->
        <div class="dashboard-item grid-item-design ex2">
            <div class="item-name">Inventory Movements</div>
            <ul class="inventory-movements-container container_scrollbar_design">
                <!-- #region sales filter -->
                <!-- #region Header Row --> 
                <li class="inventory-movements-header sales-filter active">
                    <span class="inventory-movements-header-hd hd-movement-id">
                        <span>Movement ID</span>
                    </span>
                    <span class="inventory-movements-header-hd hd-product-id">
                        <span>Product ID</span>
                    </span>
                    <span class="inventory-movements-header-hd hd-product-name">
                        <span>Product name</span>
                    </span>
                    <span class="inventory-movements-header-hd hd-quantity">
                        <span>Quantity</span>
                    </span>
                    <span class="inventory-movements-header-hd hd-movement-type">
                        <span>Movement type</span>
                    </span>
                    <span class="inventory-movements-header-hd hd-date-of-movement">
                        <span>Date of Movement</span>
                    </span>
                </li>
                <!-- #endregion-->

                <!-- Rows be dynamically inserted here -->
                <li class="inventory-movements-item sales-filter active">
                    <span class="movement-id">MOV-001</span>
                    <span class="product-id">PRD-001</span>
                    <span class="product-name">Product GT2</span>
                    <span class="quantity">23</span>
                    <span class="movement-type">sale</span>
                    <span class="date-of-movement">Jan 1, 2023 | 7:00am</span>
                </li>

                <!-- #endregion-->
            </ul>
        </div>

    </div>