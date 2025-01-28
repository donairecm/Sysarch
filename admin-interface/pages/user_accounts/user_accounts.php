
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
                id="searchusertable"
                placeholder="Search users..." 
                aria-label="Search users">
        </div>
        </div>
        <div class="admin-user-accounts-grid-item grid-item-design-mua mua7 button">Change password</div>
        <!-- #region modal for Changing passwords -->
        <div class="modal-style modal-change-password-for-a-user">
            <div class="modal-content">
                <form action="POST" id="changepasswordforauserForm">
                    <div class="prod-m">
                        <span>Change password of a user</span>
                    </div>
                    <div class="center-wrapper">
                        <div class="select-container">
                            <div class="input-group select">
                                <select id="mua-user_change_password-input" class="custom-select">
                                    <option value="" disabled selected>Choose user</option>
                                    <!-- Users will be dynamically added here-->

                                    <!-- sample format-->
                                    <option value="1">ADM-001 | Carlo Joshua B. Abellera</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <span>Change Password</span>
                        <div class="input-group">
                        <input type="text" placeholder="must be atleast 8 characters long" id="mua-change-password-input" class="md-placeholder">
                        <label id="mua-change-password">enter new password</label>
                        </div>
                    </div>
                    <div class="save">
                        <button type="submit" id="changepasswordforuserconfirm" disabled>Change password</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="confirmationModalchangepasswordforuser" class="confirmmodal-style" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
            <div style="background: white; padding: 20px; border-radius: 5px; max-width: 700px; width: auto;">
                <span>Confirm password change for?</span>
                <ul id="changeauserpasswordconfirmationlist" style="padding-left: 20px;"></ul>
                <div class="button-container">
                    <button id="confirmpasswordchangeforuser" class="md-btn-1" style="margin-right: 10px;">Confirm</button>
                    <button id="cancelpasswordchangeforuser" class="md-btn-2">Cancel</button>
                </div>
            </div>
        </div>

        <!-- #endregion -->

        <div class="admin-user-accounts-grid-item grid-item-design-mua mua2 button">Add new user</div>
        <!-- #region modal for Adding users -->
        <div class="modal-style modal-add-users">
            <div class="modal-content">
                <form action="POST" id="addauserForm">
                    <div class="prod-m">
                        <span>Add a user</span>
                    </div>
                    <div class="">
                        <span>Username</span>
                        <div class="input-group">
                        <input type="text" placeholder="ex. default_username" id="mua-username-input" class="md-placeholder">
                        <label id="mua-username">enter username</label>
                        </div>
                    </div>
                    <div class="">
                        <span>Password</span>
                        <div class="input-group">
                        <input type="text" placeholder="must be atleast 8 characters long" id="mua-password-input" class="md-placeholder">
                        <label id="mua-password">enter password</label>
                        </div>
                    </div>
                    <div class="center-wrapper">
                        <div class="select-container">
                            <div class="input-group select">
                                <select id="mua-user_role-input" class="custom-select">
                                    <option value="" disabled selected>Choose role</option>
                                    <option value="1">Inventory Manager</option>
                                    <option value="2">Sales Manager</option>
                                    <option value="3">Supply Chain Manager</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="save">
                        <button type="submit" id="addauserconfirmbtn" disabled>Add user</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="confirmationModaladdusers" class="confirmmodal-style" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
            <div style="background: white; padding: 20px; border-radius: 5px; max-width: 700px; width: auto;">
                <span>Confirm user creation?</span>
                <ul id="addauserconfirmationlist" style="padding-left: 20px;"></ul>
                <div class="button-container">
                    <button id="confirmusercreation" class="md-btn-1" style="margin-right: 10px;">Confirm</button>
                    <button id="cancelusercreation" class="md-btn-2">Cancel</button>
                </div>
            </div>
        </div>
        <!-- #endregion -->
        <div class="admin-user-accounts-grid-item grid-item-design-mua mua3 filter active">Details</div>
        <div class="admin-user-accounts-grid-item grid-item-design-mua mua4 filter">Contact</div>
        <div class="admin-user-accounts-grid-item grid-item-design-mua mua5 filter">Logs</div>
        <div class="admin-user-accounts-grid-item grid-item-design-mua mua6">
            <ul class="user-account-list-container container_scrollbar_design">

                <!-- #region filter Details-->
                <!-- Header Row -->
                <li class="user-account-information-header details-filter active">
                    <span class="user-account-information-header-hd hd-employee-id">
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
                <!-- #endregion--> 
       
            </ul>
        </div>
    </div>

    