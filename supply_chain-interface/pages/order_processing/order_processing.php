<div class="supply-chain-order-processing grid-scrollbar-design">
        <div class="supply-chain-order-processing-item grid-item-design-op title">Order Processing</div>
        <div class="supply-chain-order-processing-item grid-item-design-op op1 metric">
            <div class="left">Pending</div>
            <div class="right">12</div>
        </div>
        <div class="supply-chain-order-processing-item grid-item-design-op op2 metric">
            <div class="left">On Process</div>
            <div class="right">1</div>
        </div>
        <div class="supply-chain-order-processing-item grid-item-design-op op3 metric">
            <div class="left">In transit</div>
            <div class="right">1</div>
        </div>
        <div class="supply-chain-order-processing-item grid-item-design-op op4 metric">
            <div class="left">Pending</div>
            <div class="right">1</div>
        </div>
        <div class="supply-chain-order-processing-item grid-item-design-op op5 metric">
            <div class="left">Completed</div>
            <div class="right">15</div>
        </div>
        <div class="supply-chain-order-processing-item grid-item-design-op op6 ">
            <ul class="supply-chain-orders container_scrollbar_design">
                <!-- #region filter products-->
                <!-- Header Row -->
                <li class="supply-chain-orders-header all-filter active">
                    <span class="supply-chain-orders-header-hd hd-order-id active">
                        <span>Order ID</span>
                    </span>
                    <span class="supply-chain-orders-header-hd hd-type">
                        <span>Type</span>
                    </span>
                    <span class="supply-chain-orders-header-hd hd-status">
                        <span>Status</span>
                    </span>
                    <span class="supply-chain-orders-header-hd hd-handled-by">
                        <span>Handled by</span>
                    </span>
                </li>

                <!-- Rows be dynamically inserted here -->
                <li class="supply-chain-orders-item products-filter 2 active">
                    <span class="order-id">SCO-001</span>
                    <span class="type">Delivery</span>
                    <span class="status">Pending</span>
                    <span class="handled-by">....</span>
                </li>

                <div class="modal-product-details modal-style">
                    <div class="modal-content">
                        <form action="POST" id="productForm">
                                <div class="prod-m">
                                    <span id="modal-product-id">product_id</span>
                                </div>
                                <div class="">
                                    <span>Product Name</span>
                                    <div class="input-group">
                                    <input type="text" placeholder="ex. stained glass frame" id="md-product_name-input" class="md-placeholder">
                                    <label id="md-product_name">product_name</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Quantity</span>
                                    <div class="input-group">
                                    <input type="number" placeholder="ex. 321" id="md-quantity-input" class="md-placeholder">
                                    <label id="md-quantity">quantity</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Reorder point</span>
                                    <div class="input-group">
                                    <input type="number" placeholder="ex. 32" id="md-reorder_point-input" class="md-placeholder">
                                    <label id="md-reorder_point">reorder_point</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Price</span>
                                    <div class="input-group">
                                    <input type="number" placeholder="ex. 55" id="md-price-input" class="md-placeholder">
                                    <label id="md-price">price</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Reorder Cost</span>
                                    <div class="input-group">
                                    <input type="number" placeholder=" ex. 99" id="md-reorder_cost-input" class="md-placeholder">
                                    <label id="md-reorder_cost">reorder_cost</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Location</span>
                                    <div class="input-group">
                                    <input type="text" placeholder=" ex. shelf 2" id="md-location-input" class="md-placeholder">
                                    <label id="md-location">stock_location</label>
                                    </div>
                                </div>
                                <div class="save">
                                    <button type="submit" id="saveButton" disabled>Save changes made</button>
                                </div>
                        </form>
                    </div>
                </div>

                <!-- Confirmation modal -->
                <div id="confirmationModal"  class="confirmmodal-style" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
                    <div style="background: white; padding: 20px; border-radius: 5px; max-width: 700px; width: auto;">
                        <span>Confirm Changes</span>
                        <ul id="changeList" style="padding-left: 20px;"></ul>
                        <div class="button-container">
                            <button id="confirmChanges" class="md-btn-1" style="margin-right: 10px;">Confirm</button>
                            <button id="cancelChanges" class="md-btn-2">Cancel</button>
                        </div>
                    </div>
                </div>
            </ul>
        </div>
    </div>op