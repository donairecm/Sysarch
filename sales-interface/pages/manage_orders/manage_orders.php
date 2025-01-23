<div class="sales-manage-orders grid-scrollbar-design">
    <div class="sales-manage-orders-item grid-item-design-ms title">Manage Orders</div>
    <div class="sales-manage-orders-item grid-item-design-ms ms1 tabs">Create an order</div>
    <div class="sales-manage-orders-item grid-item-design-ms ms3">
        <ul class="sales-manage-orders-table container_scrollbar_design">
                <!-- #region filter products-->
                <!-- Header Row -->
                <li class="sales-manage-orders-table-header sales">
                    <span class="sales-manage-orders-table-header-hd hd-sales-order-id">
                        <span>Sales Order ID</span>
                    </span>
                    <span class="sales-manage-orders-table-header-hd hd-order-item-count">
                        <span>Order Items</span>
                    </span>
                    <span class="sales-manage-orders-table-header-hd hd-managed-by">
                        <span>Managed by</span>
                    </span>
                    <span class="sales-manage-orders-table-header-hd hd-total-amount">
                        <span>Total Amount</span>
                    </span>
                    <span class="sales-manage-orders-table-header-hd hd-payment-method">
                        <span>Payment method</span>
                    </span>
                    <span class="sales-manage-orders-table-header-hd hd-created-on">
                        <span>Created on</span>
                    </span>
                </li>

                <!-- Rows be dynamically inserted here -->
                <li class="sales-manage-orders-table-item sales">
                    <span class="sales-order-id">SID-012</span>
                    <span class="order-item-count">3</span>
                    <span class="hd-managed-by">SSM-012</span>
                    <span class="total-amount">P1021.00</span>
                    <span class="payment-method">Cash</span>
                    <span class="created-on">Jan 22 2025 | 7:00am</span>
                </li>

                  <!-- Modal -->
                  <div class="modal-order-items-attached modal-style grid-scrollbar-design ">
                    <div class="modal-content orderitematt">
                        <div>
                            <div class="left">SID-032 | status</div>
                            <div class="left">Managed by </div>
                        </div>
                        <ul class="orderItemattached container_scrollbar_design">
                            <!-- rows will be dynamically loaded here -->
                            <!-- sample row -->
                            <li class="orderItemattached-header orders">
                                <span class="orderItemattached-header-hd hd-order-id">
                                    <span>Order ID</span>
                                </span>
                                <span class="orderItemattached-header-hd hd-product-id">
                                    <span>Product ID</span>
                                </span>
                                <span class="orderItemattached-header-hd hd-product-name">
                                    <span>Product name</span>
                                </span>
                                <span class="orderItemattached-header-hd hd-quantity">
                                    <span>Quantity ID</span>
                                </span>
                                <span class="orderItemattached-header-hd hd-total-price">
                                    <span>Total price</span>
                                </span>
                            </li>
                            <li class="orderItemattached-item orders">
                                <span class="order-id">OID-001</span>
                                <span class="product-id">PRD-011</span>
                                <span class="product-name">Product GT1</span>
                                <span class="quantity">Product GT1</span>
                                <span class="total-price">₱1,172.00</span>
                            </li>
                            <li class="orderItemattached-item orders">
                                <span class="order-id">OID-001</span>
                                <span class="product-id">PRD-011</span>
                                <span class="product-name">Product GT1</span>
                                <span class="quantity">Product GT1</span>
                                <span class="total-price">₱1,172.00</span>
                            </li>
                            <li class="orderItemattached-item orders">
                                <span class="order-id">OID-001</span>
                                <span class="product-id">PRD-011</span>
                                <span class="product-name">Product GT1</span>
                                <span class="quantity">Product GT1</span>
                                <span class="total-price">₱1,172.00</span>
                            </li>
                            <li class="orderItemattached-item orders">
                                <span class="order-id">OID-001</span>
                                <span class="product-id">PRD-011</span>
                                <span class="product-name">Product GT1</span>
                                <span class="quantity">Product GT1</span>
                                <span class="total-price">₱1,172.00</span>
                            </li>
                            <li class="orderItemattached-item orders">
                                <span class="order-id">OID-001</span>
                                <span class="product-id">PRD-011</span>
                                <span class="product-name">Product GT1</span>
                                <span class="quantity">Product GT1</span>
                                <span class="total-price">₱1,172.00</span>
                            </li>

                        </ul>
                        
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
</div>