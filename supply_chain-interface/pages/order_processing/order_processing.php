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
            <div class="left">Completed</div>
            <div class="right">2</div>
        </div>
        <div class="supply-chain-order-processing-item grid-item-design-op op5 metric">
            <div class="left">Cancelled</div>
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
                

                <div class="modal-supply-chain-orders modal-style">
                    <div class="modal-content">
                        <div class="delivery"></div>
                        <div class="reorder">
                            <div class="sc_order_idANDstatus">sc_order_id | status</div>
                            <div class="handled_by">Handled by<span>handled_by</span></div>
                            <div class="accepted_on">Accepted on<span>accepted_on</span></div>
                            <div class="product_id">Product ID<span>product_id</span></div>
                            <div class="product_name">Product name<span>product_name</span></div>
                            <div class="quantity">Requested quantity <span>quantity</span></div>
                            <button class="accept show">Accept</button>
                            <button class="ready">Ready for delivery</button>
                            <button class="delivered">Product received</button>
                        </div>
                    </div>
                </div>

                <!-- Confirmation modal -->
                <div id="confirmationModalforstatusupdate"  class="confirmmodal-style" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
                    <div style="background: white; padding: 20px; border-radius: 5px; max-width: 700px; width: auto;">
                        <span>Confirm status update?</span>
                        <div class="button-container">
                            <button id="confirmupdateStatus" class="md-btn-1" style="margin-right: 10px;">Confirm</button>
                            <button id="cancelupdateStatus" class="md-btn-2">Cancel</button>
                        </div>
                    </div>
                </div>
            </ul>
        </div>
    </div>op