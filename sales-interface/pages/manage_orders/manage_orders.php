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
                <div class="modal-order-items-attached modal-style grid-scrollbar-design show">
                    <div class="modal-content orderitematt">
                        <form action="POST" id="" class="salesadditem">
                                <div class="prod-m sales">
                                    <span>Add items here</span>
                                </div>
                                <div class="">
                                    <span>Product ID</span>
                                    <div class="input-group">
                                    <input type="text" placeholder="ex. PRD-001" id="rr-product_id-input" class="md-placeholder">
                                    <label id="rr-product_id">enter product id</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Quantity</span>
                                    <div class="input-group">
                                    <input type="number" placeholder="note: no decimals" id="rr-quantity-input" class="md-placeholder">
                                    <label id="rr-quantity">enter quantity</label>
                                    </div>
                                </div>
                                <div class="save sales">
                                    <button type="submit" id="" disabled>Add item</button>
                                </div>
                        </form>
                        <form action="POST" class="orderitemlist">
                            <div class="prod-m sales">Order List</div>
                            <ul class="orderlist-container container_scrollbar_design">
                                <li class="header">
                                    <span>Product ID</span>
                                    <span>Product name</span>
                                    <span>Quantity</span>
                                    <span></span>
                                </li>
                                <li class="item">
                                    <span>PRD-112</span>
                                    <span>Product GT1</span>
                                    <span>32</span>
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm80-160h80v-360h-80v360Zm160 0h80v-360h-80v360Z"/>
                                        </svg>
                                    </span>
                                </li>
                                <li class="item">
                                    <span>PRD-112</span>
                                    <span>Product GT1</span>
                                    <span>32</span>
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm80-160h80v-360h-80v360Zm160 0h80v-360h-80v360Z"/>
                                        </svg>
                                    </span>
                                </li>
                                <li class="item">
                                    <span>PRD-112</span>
                                    <span>Product GT1</span>
                                    <span>32</span>
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm80-160h80v-360h-80v360Zm160 0h80v-360h-80v360Z"/>
                                        </svg>
                                    </span>
                                </li>
                                <li class="item">
                                    <span>PRD-112</span>
                                    <span>Product GT1</span>
                                    <span>32</span>
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm80-160h80v-360h-80v360Zm160 0h80v-360h-80v360Z"/>
                                        </svg>
                                    </span>
                                </li>
                            </ul>
                            <div class="save sales">
                                <button type="submit" id="" disabled>Create an order</button>
                            </div>
                        </form>
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