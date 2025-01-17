
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
        <div class="inventory-product-details-item grid-item-design-pd pd7 button">Product changes</div>
        <div class="inventory-product-details-item grid-item-design-pd pd3 filter active">Product</div>
        <div class="inventory-product-details-item grid-item-design-pd pd4 filter">Reorder</div>
        <div class="inventory-product-details-item grid-item-design-pd pd5 filter">Location</div>
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
                    <span class="product-details-header-hd hd-location">
                        <span>Location</span>
                    </span>
                </li>

                <!-- Rows for filter reorder will be dynamically inserted here -->
                 <!-- sample of row -->
                <!-- #endregion-->

                
            </ul>
        </div>
        
        
    </div>

    