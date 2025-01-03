
<div class="dashboard-grid grid-scrollbar-design">
        <div class="dashboard-item title">Users</div>

        <!-- Revenue -->
        <div class="dashboard-item top-row revenue">
            <div class="top">
                <div class="text">
                    <div class="name">Revenue</div>
                    <div class="value"><span>₱</span>386,251</div>
                </div>
                <div class="svg-container">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M120-120v-80l80-80v160h-80Zm160 0v-240l80-80v320h-80Zm160 0v-320l80 81v239h-80Zm160 0v-239l80-80v319h-80Zm160 0v-400l80-80v480h-80ZM120-327v-113l280-280 160 160 280-280v113L560-447 400-607 120-327Z"/>
                    </svg>
                </div>
            </div>
            <div class="dashboard-analytics">
                <span>+51%</span> more than last month
            </div>
        </div>

        <!-- Inventory Value -->
        <div class="dashboard-item top-row inventory-value">
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

        

        <!-- Inventory Turnover Rate -->
        <div class="dashboard-item top-row inventory-turnover-rate">
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

        <!-- Orders in Transit -->
        <div class="dashboard-item top-row orders-in-transit">
            <div class="top">
                <div class="text">
                    <div class="name">Orders in Transit</div>
                    <div class="value">23</div>
                </div>
                <div class="svg-container">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M240-160q-50 0-85-35t-35-85H40v-440q0-33 23.5-56.5T120-800h560v160h120l120 160v200h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85H360q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T280-280q0-17-11.5-28.5T240-320q-17 0-28.5 11.5T200-280q0 17 11.5 28.5T240-240ZM120-360h32q17-18 39-29t49-11q27 0 49 11t39 29h272v-360H120v360Zm600 120q17 0 28.5-11.5T760-280q0-17-11.5-28.5T720-320q-17 0-28.5 11.5T680-280q0 17 11.5 28.5T720-240Zm-40-200h170l-90-120h-80v120ZM360-540Z"/>
                    </svg>
                </div>
            </div>
            <div class="dashboard-analytics">
                <span>+1%</span> more than yesterday
            </div>
        </div>

        <!-- Stock Levels -->
        <div class="dashboard-item ex1">
            <div class="item-name">Stock Levels</div>
            <div class="frame">
                <div class="text">*There are<div class="needs-restocking"></div>products that needs restocking</div>
                <div class="container">
                    <canvas id="dashboardStockLevelChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Units Sold per Product -->
        <div class="dashboard-item ex2">
            <div class="item-name">Units Sold per Product</div>
            <div class="frame">
                <div class="text">*Almost 3% of the products underperformed last month</div>
                <div class="container">
                    <canvas id="dashboardUnitsSoldperProduct"></canvas>
                </div>
            </div>
        </div>

    </div>