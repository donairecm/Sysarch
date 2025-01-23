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
                    <span class="sales-manage-orders-table-header-hd hd-sales-item-status">
                        <span>Status</span>
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
                    <span class="sales-item-status">Completed</span>
                    <span class="created-on">Jan 22 2025 | 7:00am</span>
                </li>

                  <!-- Modal -->
                <div class="modal-order-items-attached modal-style grid-scrollbar-design">
                    <div class="modal-content orderitematt">
                        <form action="POST" id="" class="salesadditem">
                                <div class="prod-m sales">
                                    <span>Add items here</span>
                                </div>
                                <div class="">
                                    <span>Product ID</span>
                                    <div class="input-group">
                                    <input type="text" placeholder="ex. PRD-001" id="aoi-product_id-input" class="md-placeholder">
                                    <label id="aoi-product_id">enter product id</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Quantity</span>
                                    <div class="input-group">
                                    <input type="number" placeholder="note: no decimals" id="aoi-quantity-input" class="md-placeholder">
                                    <label id="aoi-quantity">enter quantity</label>
                                    </div>
                                </div>
                                <div class="save sales">
                                    <button type="submit" id="additemtosale" disabled>Add item</button>
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

                                <!-- Content will be dynamically added here -->
                                <!-- sample row -->
                                
                            </ul>
                            <div class="save sales">
                                <button type="submit" id="createanorder" disabled>Create an order</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Confirmation modal -->
                <div id="confirmmodalfororderitem"  class="confirmmodal-style" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
                    <div style="background: white; padding: 20px; border-radius: 5px; max-width: 700px; width: auto;">
                        <span>Confirm order creation?</span>
                        <ul></ul>
                        <div class="button-container">
                            <button id="confirmcreateorder" class="md-btn-1" style="margin-right: 10px;">Confirm</button>
                            <button id="cancelcreateorder" class="md-btn-2">Cancel</button>
                        </div>
                    </div>
                </div>

                  <!-- Modal 2-->
                  <div class="modal-order-items-attached2 modal-style grid-scrollbar-design">
                    <div class="modal-content orderitematt">
                        <form action="POST" id="" class="salesadditem2">
                                <div class="prod-m sales2">
                                    <span>sc_order_id</span>
                                </div>
                                <div class="">
                                    <span>Managed by</span>
                                    <span>SSM-010</span>
                                </div>
                                <div class="">
                                    <span>Total Amount</span>
                                    <span>P1,000.00</span>
                                </div>
                                <div class="">
                                    <span>Status</span>
                                    <span>On process</span>
                                </div>
                                <div class="">
                                    <span class="last">Jan 24, 2025 | 1:31am</span>
                                </div>
                        </form>
                        <form action="POST" class="orderitemlist">
                            <div class="prod-m sales2">Order List</div>
                            <ul class="orderlist-container container_scrollbar_design">
                                <li class="header">
                                    <span>Product ID</span>
                                    <span>Product name</span>
                                    <span>Quantity</span>
                                    <span>Price</span>
                                </li>

                                <!-- Content will be dynamically added here -->
                                <li class="item">
                                    <span>PRD-001</span>
                                    <span>Product GT1</span>
                                    <span>12</span>
                                    <span>P12,200.00</span>
                                </li>
                                <!-- sample row -->
                                
                            </ul>
                            <div class="save sales ss2">
                            <button type="submit" id="cancelsaleorder">Cancel order</button>
                                <button type="submit" id="marksaleordercomplete">Mark as complete</button>
                            </div>
                        </form>
                    </div>
                </div>


            </ul>
    </div>
</div>