
<div class="account-information-grid grid-scrollbar-design">
    <div class="account-information-grid-item grid-item-design-aig aig1">
        <div class="aig-title">
         
        </div>
    </div>
    <div class="account-information-grid-item grid-item-design-aig aig3">
        <div class="aig-title">Account Details</div>
        <form action="POST" class="aig-content" id="updateaccountdetialsForm">
            <div class="row-item aig-ri-1">
                <div class="input-title">Username (will be used for logging in and password retrieval)</div>
                <div class="input-group-aig">
                    <input type="text" placeholder="" id="uad-employee_username" class="md-placeholder">
                    <label id="uad-username">username</label>
                    <!-- Username error container -->
                    <div class="error-container username">
                        <div class="account-details-errors ae-tooltip-style" id="aig-username-error">--error message--</div>
                    </div>
                </div>
                
            </div>
            <div class="row-item aig-ri-2 double">
                <div>
                    <div class="input-title">First name</div>
                        <div class="input-group-aig">
                        <input type="text" placeholder="" id="uad-employee_fname" class="md-placeholder">
                        <label id="uad-first-name">first_name</label>
                    </div>
                </div>
                <div>
                    <div class="input-title">Middle name</div>
                        <div class="input-group-aig">
                        <input type="text" placeholder="" id="uad-employee_mname" class="md-placeholder">
                        <label id="uad-lname">middle_name</label>
                    </div>
                </div>
            </div>

            <div class="row-item aig-ri-3">
                    <div class="input-title">Last name</div>
                        <div class="input-group-aig">
                        <input type="text" placeholder="" id="uad-employee_lname" class="md-placeholder">
                        <label id="uad-mname">last_name</label>
                    </div>
            </div>


            <div class="row-item aig-ri-4">
                <div class="input-title">Email</div>
                <div class="input-group-aig">
                    <input type="email" placeholder="" id="uad-employee_email" class="md-placeholder">
                    <label id="uad-email">email</label>
                </div>
                <!-- Email error container -->
                <div class="error-container email">
                    <div class="account-details-errors ae-tooltip-style" id="aig-email-error">--error message--</div>
                </div>
            </div>
            <div class="row-item aig-ri-5 double">
                <div>
                    <div class="input-title">Phone number 1</div>
                    <div class="input-group-aig">
                        <input type="number" placeholder="format: 09252421367" id="uad-employee_pnum1" class="md-placeholder">
                        <label id="uad-phone-num-1">phone_number_1</label>
                    </div>
                    <!-- Phone number 1 error container -->
                    <div class="error-container pnumber1">
                        <div class="account-details-errors ae-tooltip-style" id="pnum3-error">--error message--</div>
                    </div>
                </div>
                <div>
                    <div class="input-title">Phone number 2</div>
                    <div class="input-group-aig">
                        <input type="number" placeholder="format: 09252421367" id="uad-employee_pnum2" class="md-placeholder">
                        <label id="uad-phone-num-2">phone_number_2</label>
                    </div>
                    <!-- Phone number 2 error container -->
                    <div class="error-container pnumber2">
                        <div class="account-details-errors ae-tooltip-style" id="pnum2-error">--error message--</div>
                    </div>
                </div>
            </div>

            <div class="update">
                <button>Change Password</button> 
                <button type="submit" id="updateaccountdetails" disabled>Save changes</button>
            </div>
        </form>
    </div>

    <div id="confirmationModal5"  class="confirmmodal-style" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 20px; border-radius: 5px; max-width: 700px; width: auto;">
            <span>Confirm Changes</span>
            <ul id="updatedemployeedetails" style="padding-left: 20px;"></ul>
            <div class="button-container">
                <button id="confirmemployeedetailschange" class="md-btn-1" style="margin-right: 10px;">Confirm</button>
                <button id="confirmemployeedetailschange" class="md-btn-2">Cancel</button>
            </div>
        </div>
    </div>

</div>

    