
<div class="inventory-grid grid-scrollbar-design">
        <div class="inventory-item grid-item-design title">Dashboard</div>
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

        <div class="inventory-item grid-item-design inventory-activities im3">
            <div class="item-name">Reorder Requests</div>
            <div class="inventory-activities-container container_scrollbar_design">
                <ul class="inventory-activities-list">
                    <!-- Header Row -->
                    <li class="inventory-activities-header">
                        <span class="inventory-activities-header-product-id">Request ID</span>
                        <span class="inventory-activities-header-product-id">Product ID</span>
                        <span class="inventory-activities-header-quantity">Quantity</span>
                        <span class="inventory-activities-header-product-id">Status</span>
                        <span class="inventory-activities-header-activity">Date of request</span>
                    </li>
                    <!-- Rows will be dynamically inserted here -->
                </ul>
            </div>
        </div>

        <!-- #region modal for add product -->
        <div class="modal-style modal-view-delete-reorder-detail">
    <div class="modal-content">
        <form action="POST" id="viewOrdeletereorderrequestForm">
            <div class="prod-m" id="requestID">
                <span data-field="request_id"></span>
            </div>
            <div>
                <span>Requested by</span>
                <span data-field="requested_by"></span>
            </div>
            <div>
                <span>Product ID</span>
                <span data-field="product_id"></span>
            </div>
            <div>
                <span>Product name</span>
                <span data-field="product_name"></span>
            </div>
            <div>
                <span>Requested quantity</span>
                <span data-field="quantity"></span>
            </div>
            <div>
                <span>Handled by</span>
                <span data-field="handled_by"></span>
            </div>
            <div>
                <span>Status</span>
                <span data-field="status"></span>
            </div>
            <div>
                <span>Date of Request</span>
                <span data-field="date_of_request"></span>
            </div>
            <div class="save">
            <button type="submit" id="deleterequestButton">Delete Request</button>
            </div>
        </form>
    </div>
        </div>


                <div id="confirmationModal2" class="confirmmodal-style" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 20px; border-radius: 5px; max-width: 700px; width: auto;">
        <span>Confirm product addition</span>
        <ul id="addProductDetails" style="padding-left: 20px;"></ul>
        <div class="button-container">
            <button id="confirmCreation" class="md-btn-1" style="margin-right: 10px;">Confirm</button>
            <button id="cancelCreation" class="md-btn-2">Cancel</button>
        </div>
    </div>
</div>

                <!-- #endregion -->


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