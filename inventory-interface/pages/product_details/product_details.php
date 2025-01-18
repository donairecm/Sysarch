
<div class="inventory-product-details grid-scrollbar-design">
        <div class="inventory-product-details-item grid-item-design-pd title">Manage Products</div>
        <div class="inventory-product-details-item grid-item-design-pd pd1">
            <div class="icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/>
                </svg>
            </div>
            <div class="input-container">
            <input 
                type="text" 
                class="search-input" 
                placeholder="Search products..." 
                aria-label="Search products">
        </div>
        </div>
        <div class="inventory-product-details-item grid-item-design-pd pd2 button">Add new product</div>

        <!-- #region modal for add product -->
        <div class="modal-style modal-add-products ">
                    <div class="modal-content">
                        <form action="POST" id="addproductsForm">
                                <div class="prod-m">
                                    <span>Add a product</span>
                                </div>
                                <div class="">
                                    <span>Product Name</span>
                                    <div class="input-group">
                                    <input type="text" placeholder="ex. stained glass frame" id="md-product_name-input" class="md-placeholder">
                                    <label id="ap-product_name">enter product name</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Quantity</span>
                                    <div class="input-group">
                                    <input type="number" placeholder="note: no decimals" id="ap-quantity-input" class="md-placeholder">
                                    <label id="ap-quantity">enter quantity</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Reorder point</span>
                                    <div class="input-group">
                                    <input type="number" placeholder="ex. 58" id="ap-reorder_point-input" class="md-placeholder">
                                    <label id="ap-reorder_point">enter reorder point</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Price</span>
                                    <div class="input-group">
                                    <input type="number" placeholder="note: no decimals" id="md-price-input" class="md-placeholder">
                                    <label id="ap-price">enter price</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Reorder Cost</span>
                                    <div class="input-group">
                                    <input type="number" placeholder=" note: no decimals" id="ap-reorder_cost-input" class="md-placeholder">
                                    <label id="ap-reorder_cost">enter reorder cost</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Location</span>
                                    <div class="input-group">
                                    <input type="text" placeholder=" ex. room 2, aisle 3" id="ap-location-input" class="md-placeholder">
                                    <label id="ap-location">enter stock location</label>
                                    </div>
                                </div>
                                <div class="save">
                                    <button type="submit" id="addproductButton" disabled>Add product</button>
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


        <div class="inventory-product-details-item grid-item-design-pd pd7 button">Request reorder</div>
        
        <!-- #region modal for request reorder -->
        <div class="modal-style modal-request-reorder">
                    <div class="modal-content">
                        <form action="POST" id="requestreorderForm">
                                <div class="prod-m">
                                    <span>Request reorder</span>
                                </div>
                                <div class="">
                                    <span>Product Name</span>
                                    <div class="input-group">
                                    <input type="text" placeholder="ex. stained glass frame" id="md-product_name-input" class="md-placeholder">
                                    <label id="ap-product_name">enter product name</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Quantity</span>
                                    <div class="input-group">
                                    <input type="number" placeholder="note: no decimals" id="ap-quantity-input" class="md-placeholder">
                                    <label id="ap-quantity">enter quantity</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Reorder point</span>
                                    <div class="input-group">
                                    <input type="number" placeholder="ex. 58" id="ap-reorder_point-input" class="md-placeholder">
                                    <label id="ap-reorder_point">enter reorder point</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Price</span>
                                    <div class="input-group">
                                    <input type="number" placeholder="note: no decimals" id="md-price-input" class="md-placeholder">
                                    <label id="ap-price">enter price</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Reorder Cost</span>
                                    <div class="input-group">
                                    <input type="number" placeholder=" note: no decimals" id="ap-reorder_cost-input" class="md-placeholder">
                                    <label id="ap-reorder_cost">enter reorder cost</label>
                                    </div>
                                </div>
                                <div class="">
                                    <span>Location</span>
                                    <div class="input-group">
                                    <input type="text" placeholder=" ex. room 2, aisle 3" id="ap-location-input" class="md-placeholder">
                                    <label id="ap-location">enter stock location</label>
                                    </div>
                                </div>
                                <div class="save">
                                    <button type="submit" id="addproductButton" disabled>Add product</button>
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

        <div class="inventory-product-details-item grid-item-design-pd pd3 filter active">Product</div>
        <div class="inventory-product-details-item grid-item-design-pd pd4 filter">Reorder</div>
        <div class="inventory-product-details-item grid-item-design-pd pd5 filter">Others</div>
        <div class="inventory-product-details-item grid-item-design-pd pd6">
            <ul class="product-details-container container_scrollbar_design">
                <!-- #region filter products-->
                <!-- Header Row -->
                <li class="product-details-header products-filter active">
                    <span class="product-details-header-hd hd-product-id active">
                        <span>Product ID</span>
                    </span>
                    <span class="product-details-header-hd hd-product-name">
                        <span>Product name</span>
                    </span>
                    <span class="product-details-header-hd hd-quantity">
                        <span>Quantity</span>
                    </span>
                    <span class="product-details-header-hd hd-price">
                        <span>Price</span>
                    </span>
                    <span class="product-details-header-hd hd-product-status">
                        <span>Status</span>
                    </span>
                    <span class="product-details-header-hd hd-created-on">
                        <span>Created on</span>
                    </span>
                </li>

                <!-- Rows be dynamically inserted here -->
                 <!-- sample of row -->
                
                <!-- #endregion-->

                <!-- #region filter reorder-->
                <!-- Header Row -->
                <li class="product-details-header reorder-filter">
                    <span class="product-details-header-hd hd-product-id">
                        <span>Product ID</span>
                    </span>
                    <span class="product-details-header-hd hd-product-name">
                        <span>Product name</span>
                    </span>
                    <span class="product-details-header-hd hd-quantity">
                        <span>Quantity</span>
                    </span>
                    <span class="product-details-header-hd hd-reorder-point">
                        <span>Reorder point</span>
                    </span>
                    <span class="product-details-header-hd hd-reorder-cost">
                        <span>Reorder Cost</span>
                    </span>
                    <span class="product-details-header-hd hd-last-restocked">
                        <span>Last Restocked</span>
                    </span>
                </li>

                <!-- Rows will be dynamically inserted here -->
                <!-- #endregion-->

                <!-- #region filter location-->
                <!-- Header Row-->
                <li class="product-details-header location-filter">
                    <span class="product-details-header-hd hd-product-id">
                        <span>Product ID</span>
                    </span>
                    <span class="product-details-header-hd hd-product-name">
                        <span>Product name</span>
                    </span>
                    <span class="product-details-header-hd hd-units-sold">
                        <span>Units Sold</span>
                    </span>
                    <span class="product-details-header-hd hd-supplier">
                        <span>Supplier</span>
                    </span>
                    <span class="product-details-header-hd hd-location">
                        <span>Location</span>
                    </span>
                </li>

                <!-- Rows for filter reorder will be dynamically inserted here -->
                 <!-- sample of row -->
                <!-- #endregion-->

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
        
        
    </div>

    