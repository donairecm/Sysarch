
<div class="account-information-grid grid-scrollbar-design supply_chain admin">
    <div class="account-information-grid-item grid-item-design-aig aig1">
        <div class="aig-title">Profile Picture</div>
        <form action="POST" id="updateprofilepicture">
            <div class="profilepreviewcontainer" id="profilePreviewContainer">
                <img id="profilePicPreview" src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture">
            </div>
            <div class="message">JPG or PNG no larger than 5mb</div>
            <div class="profilepicbuttoncontainer">
                <button type="submit" id="uploadButton" disabled>Upload new image</button>
                <button type="button" id="reverttooriginal" class="">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M480-120q-138 0-240.5-91.5T122-440h82q14 104 92.5 172T480-200q117 0 198.5-81.5T760-480q0-117-81.5-198.5T480-760q-69 0-129 32t-101 88h110v80H120v-240h80v94q51-64 124.5-99T480-840q75 0 140.5 28.5t114 77q48.5 48.5 77 114T840-480q0 75-28.5 140.5t-77 114q-48.5 48.5-114 77T480-120Zm112-192L440-464v-216h80v184l128 128-56 56Z"/>
                    </svg>
                </button>
            </div>
            <!-- Hidden file input -->
            <input type="file" id="fileInput" style="display: none;" accept="image/*">
        </form>
    </div>

    

    <div class="account-information-grid-item grid-item-design-aig aig3">
        <div class="aig-title">Account Details</div>
        <form action="POST" class="aig-content" id="updateaccountdetialsForm">
            <div class="row-item aig-ri-1">
                <div class="input-title">Username (will be used for logging in)</div>
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
                        <!-- First name error container -->
                        <div class="error-container fname">
                            <div class="account-details-errors ae-tooltip-style" id="fname-error">--error message--</div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="input-title">Middle name</div>
                    <div class="input-group-aig">
                        <input type="text" placeholder="" id="uad-employee_mname" class="md-placeholder">
                        <label id="uad-mname">middle_name</label>
                        <!-- First name error container -->
                        <div class="error-container mname">
                            <div class="account-details-errors ae-tooltip-style" id="mname-error">--error message--</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row-item aig-ri-3">
                    <div class="input-title">Last name</div>
                        <div class="input-group-aig">
                        <input type="text" placeholder="" id="uad-employee_lname" class="md-placeholder">
                        <label id="uad-lname">last_name</label>
                        <!-- First name error container -->
                        <div class="error-container lname">
                            <div class="account-details-errors ae-tooltip-style" id="lname-error">--error message--</div>
                        </div>
                    </div>
            </div>

            <div class="row-item aig-ri-5 double">
                <div>
                    <div class="input-title">Phone number 1</div>
                    <div class="input-group-aig">
                        <input type="number" placeholder="format: 09252421367" id="uad-employee_pnum1" class="md-placeholder">
                        <label id="uad-phone-num-1">phone_number_1</label>
                        <!-- Phone number 1 error container -->
                        <div class="error-container pnumber1">
                            <div class="account-details-errors ae-tooltip-style" id="pnum1-error">--error message--</div>
                        </div>
                    </div>
                    
                </div>
                <div>
                    <div class="input-title">Phone number 2</div>
                    <div class="input-group-aig">
                        <input type="number" placeholder="format: 09252421367" id="uad-employee_pnum2" class="md-placeholder">
                        <label id="uad-phone-num-2">phone_number_2</label>
                        <!-- Phone number 2 error container -->
                        <div class="error-container pnumber2">
                            <div class="account-details-errors ae-tooltip-style" id="pnum2-error">--error message--</div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="row-item aig-ri-4">
                <div class="input-title">Email</div>
                <div class="input-group-aig">
                    <input type="email" placeholder="" id="uad-employee_email" class="md-placeholder">
                    <label id="uad-email">email</label>
                    <!-- Email error container -->
                    <div class="error-container email">
                        <div class="account-details-errors ae-tooltip-style" id="aig-email-error">--error message--</div>
                    </div>
                </div>
            </div>

            <div class="row-item aig-ri-6 double">
                <div>
                    <div class="input-title">Enter your old password</div>
                        <div class="input-group-aig">
                            <input type="text" placeholder="" id="uad-employee_old_password" class="md-placeholder">
                            <label id="uad-old-password">password</label>
                            <!-- Old password container -->
                            <div class="error-container old_password">
                                <div class="account-details-errors ae-tooltip-style" id="old-password-error">--error message--</div>
                            </div>
                     </div>
                </div>
                <div>
                    <div class="input-title">Enter you new password</div>
                        <div class="input-group-aig">
                            <input type="text" placeholder="Must be at least 8 characters long" id="uad-employee_new_password" class="md-placeholder">
                            <label id="uad-new-password">password</label>
                            <!-- Old password container -->
                            <div class="error-container new_password">
                                <div class="account-details-errors ae-tooltip-style" id="new-password-error">--error message--</div>
                            </div>
                     </div>
                </div>
            </div>

            <div class="update">
                <button type="button" id="changepasswordmodal">Change Password</button> 
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
                <button id="cancelemployeedetailschange" class="md-btn-2">Cancel</button>
            </div>
        </div>
    </div>

</div>
