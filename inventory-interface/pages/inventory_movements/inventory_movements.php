
<div class="inventory-movements-grid grid-scrollbar-design">
        <div class="inventory-inventory-movements-item grid-item-design-imv title">Inventory Movements</div>
        <div class="inventory-inventory-movements-item grid-item-design-imv imv1">
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
        <div class="inventory-inventory-movements-item grid-item-design-imv imv2 button">Reorder a product</div>

        <!-- Redundant already had incoming products in dashboard
        <div class="inventory-inventory-movements-item grid-item-design-imv imv7 button">Incoming products</div>
        -->
        
        <div class="inventory-inventory-movements-item grid-item-design-imv imv3 filter active">Sales</div>
        <div class="inventory-inventory-movements-item grid-item-design-imv imv4 filter ">Restock</div>
        <div class="inventory-inventory-movements-item grid-item-design-pd pd6">
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
                    <span class="inventory-movements-header-hd hd-date-of-movement">
                        <span>Date of Movement</span>
                    </span>
                </li>
                <!-- #endregion-->

                <!-- Rows be dynamically inserted here -->
                <!-- #region sample of row -->
                <!-- #endregion-->

                <!-- #endregion-->

                <!-- #region filter products-->
                <!-- #region Header Row -->
                <li class="inventory-movements-header restock-filter">
                    <span class="inventory-movements-header-hd hd-request-id">
                        <span>Request ID</span>
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
                    <span class="inventory-movements-header-hd hd-date-of-request">
                        <span>Date of Request</span>
                    </span>
                    <span class="inventory-movements-header-hd hd-date-of-completion">
                        <span>Date of Completion</span>
                    </span>
                </li>
                <!-- #endregion-->

                <!-- Rows be dynamically inserted here -->
                <!-- #region sample of row -->
                <!-- #endregion-->

                <!-- #endregion-->
            </ul>
        </div>
        
    </div>