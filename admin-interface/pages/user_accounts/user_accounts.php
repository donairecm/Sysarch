
<div class="admin-user-accounts-grid grid-scrollbar-design">
        <div class="admin-user-accounts-grid-item grid-item-design-pd title">Manage Users</div>
        <div class="admin-user-accounts-grid-item grid-item-design-mua mua1">
            <div class="icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/>
                </svg>
            </div>
            <div class="input-container">
            <input 
                type="text" 
                class="search-input" 
                placeholder="Search users..." 
                aria-label="Search users">
        </div>
        </div>
        <div class="admin-user-accounts-grid-item grid-item-design-mua mua2 button">Add new user</div>
        <div class="admin-user-accounts-grid-item grid-item-design-mua mua3 filter active">Details</div>
        <div class="admin-user-accounts-grid-item grid-item-design-mua mua4 filter">Contact</div>
        <div class="admin-user-accounts-grid-item grid-item-design-mua mua5 filter">Logs</div>
        <div class="admin-user-accounts-grid-item grid-item-design-mua mua6">
            <ul class="user-account-list-container container_scrollbar_design">

                <!-- #region filter Details-->
                <!-- Header Row -->
                <li class="user-account-information-header details-filter active">
                    <span class="user-account-information-header-hd hd-employee-id active">
                        <span>Employee ID</span>
                    </span>
                    <span class="user-account-information-header-hd hd-employee-name">
                        <span>Employee Name</span>
                    </span>
                    <span class="user-account-information-header-hd hd-user-role">
                        <span>User Role</span>
                    </span>
                    <span class="user-account-information-header-hd hd-user-status">
                        <span>status</span>
                    </span>
                    <span class="user-account-information-header-hd hd-created-on">
                        <span>Created on</span>
                    </span>
                </li>

                <!-- Rows be dynamically inserted here -->
                 <li class="user-account-information-item details-filter 2 active">
                    <span class="employee-id">ADM-002</span>
                    <span class="employee-name">John Doe Demo</span>
                    <span class="user-role">Inventory Manager</span>
                    <span class="user-status">Online</span>
                    <span class="created-on">Jan 1, 2025 | 7:30am</span>
                 </li>
                 <!-- sample of row -->
                
                <!-- #endregion-->

                <!-- #region filter reorder-->
                <!-- Header Row -->
                <li class="user-account-information-header contacts-filter">
                    <span class="user-account-information-header-hd hd-employee-id">
                        <span>Employee ID</span>
                    </span>
                    <span class="user-account-information-header-hd hd-email">
                        <span>Email</span>
                    </span>
                    <span class="user-account-information-header-hd hd-phone-number-1">
                        <span>Phone number 1</span>
                    </span>
                    <span class="user-account-information-header-hd hd-phone-number-2">
                        <span>Phone number 2</span>
                    </span>
                </li>

                <!-- Rows will be dynamically inserted here -->
                <li class="user-account-information-item contacts-filter">
                    <span class="employee-id">ADM-001</span>
                    <span class="email">jhondoe.demo@sample.com</span>
                    <span class="phone-number-1">0925-275-1532</span>
                    <span class="phone-number-2">0916-824-1078</span>
                 </li>
                <!-- #endregion-->

                <!-- #region filter location-->
                <!-- Header Row-->
                <li class="user-account-information-header logs-filter">
                    <span class="user-account-information-header-hd hd-employee-id">
                        <span>Employee ID</span>
                    </span>
                    <span class="user-account-information-header-hd hd-last-login">
                        <span>Last login</span>
                    </span>
                    <span class="user-account-information-header-hd hd-last-logout">
                        <span>Last logout</span>
                    </span>
                    <span class="user-account-information-header-hd hd-updated-on">
                        <span>Account updated on</span>
                    </span>
                    <span class="user-account-information-header-hd updated-by">
                        <span>Account updated by</span>
                    </span>
                </li>

                <!-- Rows for filter reorder will be dynamically inserted here -->
                <!-- sample of row -->
                <li class="user-account-information-item logs-filter">
                    <span class="employee-id">ADM-001</span>
                    <span class="last-login">Jan 1, 2025 | 7:30am</span>
                    <span class="last-logout">Jan 1, 2025 | 9:30pm</span>
                    <span class="updated-on">Jan 1, 2024 | 12:00am</span>
                    <span class="updated-by">Jan 1, 2024 | 12:00am</span>
                 </li>
                <!-- #endregion--> 
       
            </ul>
        </div>
    </div>

    