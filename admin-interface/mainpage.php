<?php
// Include the database connection at the top of mainpage.php
require_once '../php/db_connection.php';

// Get profile details from profile.php
function getProfileData() {
    return include '../php/profile_handling.php';
}

$profileData = getProfileData();

// Extract variables from the array
$firstName = $profileData['firstName'];
$lastName = $profileData['lastName'];
$middleInitial = $profileData['middleInitial'];
$profilePic = $profileData['profilePic'];
$profileCover = $profileData['profileCover']; 
$employeeID = $profileData['employeeID'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Interface</title>

        <script>
    const employeeID = "<?php echo $employeeID; ?>";
</script>
        <script type="text/javascript" src="get_notifications.js" defer></script>

        <link rel="stylesheet" href="../global_css_js/global.css">
        <script type="text/javascript" src="../global_css_js/page_switching.js" defer></script>
        <script type="text/javascript" src="../global_css_js/get_acc_details.js" defer></script>
        <script type="text/javascript" src="../global_css_js/change_password.js" defer></script>
        <script type="text/javascript" src="../global_css_js/choose_prof_pic.js" defer></script>

        <!-- #region Navbar-Sidebar CSS/JS-->
        <link rel="stylesheet" href="../global_css_js/navbar.css">
        <link rel="stylesheet" href="../global_css_js/sidebar.css">
        <script type="text/javascript" src="../global_css_js/popovers.js" defer></script>
        <script type="text/javascript" src="../global_css_js/sidebar.js" defer></script>
        <!-- #endregion -->

        <!-- #region Dashboard CSS/JS-->
        <link rel="stylesheet" href="pages/dashboard/css/dashboard-grid.css">
        <script type="text/javascript" src="pages/dashboard/js/stock_levels.js" defer></script>
        <script type="text/javascript" src="pages/dashboard/js/get_imv.js" defer></script>
        <script type="text/javascript" src="pages/dashboard/js/get_in_turno.js" defer></script>
        <script type="text/javascript" src="pages/dashboard/js/get_in_val.js" defer></script>
        <!-- #endregion -->

        <!-- #region Manage Products CSS/JS-->
        <!-- #region Product Details-->
        <link rel="stylesheet" href="pages/product_details/css/product_details.css">
        <script type="text/javascript" src="pages/product_details/js/get_pd.js" defer></script>
        <script type="text/javascript" src="pages/product_details/js/filters.js" defer></script>
        <script type="text/javascript" src="pages/product_details/js/update_pd.js" defer></script>
        <script type="text/javascript" src="pages/product_details/js/add_product.js" defer></script>
        <script type="text/javascript" src="pages/product_details/js/request_reorder.js" defer></script>
        <!-- #endregion -->

        <!-- #region Manage Users-->
        <link rel="stylesheet" href="pages/manage_users/css/manage_users.css">
        <script type="text/javascript" src="pages/manage_users/js/get_users.js" defer></script>
        <script type="text/javascript" src="pages/manage_users/js/add_user_default_details.js" defer></script>
        <!-- #endregion -->

        <!-- #region User accounts-->
        <link rel="stylesheet" href="pages/user_accounts/css/user_accounts.css">
        <script type="text/javascript" src="pages/user_accounts/js/get_users2.js" defer></script>
        <script type="text/javascript" src="pages/user_accounts/js/add_user_default_details.js" defer></script>
        <script type="text/javascript" src="pages/user_accounts/js/change_password_for_a_user.js" defer></script>
        <!-- #endregion -->

        <!-- #region chart.js scripts-->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.2"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix"></script>
        <!-- #endregion -->
        <script>
function goToMainPage() {
    window.location.href = 'mainpage.php';
}
</script>
    </head>
<body>
    <nav id="sidebar">
        <div class="logo-wrapper">
            <div class="logo-container "onclick="goToMainPage()">
                <div class="squares">
                    <div class="square"></div>
                    <div class="square"></div>
                </div>
                <div class="logo-text-container">
                    <div class="logo-text"><span class="b">B</span>est</div>
                    <div class="logo-subtext">Aluminum Sales Corp.</div>
                </div>
            </div>
        </div>
        <ul>
            <!-- Dashboard -->
            <li class="active">
                <a class="sidebar-item" href="#" data-page="dashboard">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z"/>
                            </svg>
                        </div>
                        <span class="sidebar-hover">Dashboard</span>
                    </div>
                </a>
            </li>
            <!-- Manage Products -->
            <li class="has-submenu">
                <a class="sidebar-item" href="#" data-page="product_details">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="M320-440h320v-80H320v80Zm0 120h320v-80H320v80Zm0 120h200v-80H320v80ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/>
                            </svg>
                        </div>
                        <span class="sidebar-hover">Manage Products</span>
                    </div>
                </a>
            </li>
            <!-- User Accounts -->
            <li class="has-submenu">
                <a class="sidebar-item" href="#" data-page="user_accounts">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q14-36 44-58t68-22q38 0 68 22t44 58h168q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm280-670q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790Zm0 350q58 0 99-41t41-99q0-58-41-99t-99-41q-58 0-99 41t-41 99q0 58 41 99t99 41ZM200-200h560v-46q-54-53-125.5-83.5T480-360q-83 0-154.5 30.5T200-246v46Z"/>
                            </svg>
                        </div>
                        <span class="sidebar-hover">Manage Users</span>
                    </div>
                </a>
            </li>
            <!-- Manage users -->
            <li class="has-submenu">
                <a class="sidebar-item" href="#" data-page="manage_users">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="M240-280h240v-80H240v80Zm120-160h240v-80H360v80Zm120-160h240v-80H480v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/>
                            </svg>
                        </div>
                        <span class="sidebar-hover">User Activities</span>
                    </div>
                </a>
            </li>
        </ul>
    </nav>

    <div id="overlay"></div>

    <nav id="navbar">
        <div class="title">
            <div class="navbarleft">
                <div class="logo-wrapper">
                    <div class="logo-container">
                        <div class="squares">
                            <div class="square"></div>
                            <div class="square"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="icon-container">
            <div class="left-container">
                <!-- Notifications -->
                <div class="ic-container ic1" data-tooltip="Notifications">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160ZM480-80q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
                    </svg>
                    <svg class="icon show" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80ZM320-280h320v-280q0-66-47-113t-113-47q-66 0-113 47t-47 113v280Z"/>
                    </svg>
                    <span>Notifications</span>
                    <div class="badge">
                    </div>
                </div>

                <!--
                <div class="ic-container ic2" data-tooltip="Alerts">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M280-280q17 0 28.5-11.5T320-320q0-17-11.5-28.5T280-360q-17 0-28.5 11.5T240-320q0 17 11.5 28.5T280-280Zm-40-160h80v-240h-80v240Zm200 160h280v-80H440v80Zm0-160h280v-80H440v80Zm0-160h280v-80H440v80ZM160-120q-33 0-56.5-23.5T80-200v-560q0-33 23.5-56.5T160-840h640q33 0 56.5 23.5T880-760v560q0 33-23.5 56.5T800-120H160Z"/>
                    </svg>
                    <svg class="icon show" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M280-280q17 0 28.5-11.5T320-320q0-17-11.5-28.5T280-360q-17 0-28.5 11.5T240-320q0 17 11.5 28.5T280-280Zm-40-160h80v-240h-80v240Zm200 160h280v-80H440v80Zm0-160h280v-80H440v80Zm0-160h280v-80H440v80ZM160-120q-33 0-56.5-23.5T80-200v-560q0-33 23.5-56.5T160-840h640q33 0 56.5 23.5T880-760v560q0 33-23.5 56.5T800-120H160Zm0-80h640v-560H160v560Zm0 0v-560 560Z"/>
                    </svg>
                    <span>Alerts</span>
                    <div class="badge">
                    </div>
                </div>-->


                
            </div>
            <!-- Profile -->
            <div class="profile-container" onclick="toggleDropdown()">
                <div class="pic">
                    <!-- Display profile picture here -->
                    <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture">
                </div>
                <div class="chevron-container" >
                    <svg id="chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z"/>
                    </svg>
                </div>
            </div>

        </div>
    </nav>

    <div class="layer">
        <!-- Profile Popover -->
        <div class="profile-pop-over" id="profilePopOverContent" >
            <div class="background-img" style="background-image: url('<?php echo htmlspecialchars($profileCover); ?>');"></div>
            <div class="background-overlay"></div>
            <div class="profile-header">
                <div class="pic">
                    <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture">
                </div>
                <div class="profile-details">
                <span class="employee-name">
                    <?php 
                        echo htmlspecialchars($lastName) . ', ' . htmlspecialchars($firstName); 
                        if (!empty($middleInitial)) {
                            echo ' ' . htmlspecialchars($middleInitial) . '.'; // Add middle initial with a period
                        }
                    ?>
                </span>
                    <span class="employee-ID"><?php echo htmlspecialchars($employeeID); ?></span>
                    <div class="view-button">Edit Profile</div>
                </div>
            </div>

            <div class="divider"></div>

            <ul class="profile-menu">
                <li>
                    <a href="#" data-page="account_settings">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                            <path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z"/>
                        </svg>
                        <span>Account Settings</span>
                    </a>
                </li>
            </ul>

            <div class="divider"></div>

            <ul class="logout">
                <li>
                    <a href="../php/logout.php">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M806-440H320v-80h486l-62-62 56-58 160 160-160 160-56-58 62-62ZM600-600v-160H200v560h400v-160h80v160q0 33-23.5 56.5T600-120H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h400q33 0 56.5 23.5T680-760v160h-80Z"/>
                        </svg>
                        <span>Log out</span>
                    </a>
                </li>
            </ul>

            <span class="corner-bottom-left"></span>
            <span class="corner-bottom-right"></span>
        </div>

        <!-- Action Tool Pop over -->
        <div class="action-tools-pop-over" id="actiontoolsPopoverContent">
            <div class="header">Action Tools</div>
            <div class="orientation">
            <ul>
                <li>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                            <path d="M560-80v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T903-300L683-80H560Zm300-263-37-37 37 37ZM620-140h38l121-122-18-19-19-18-122 121v38ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v120h-80v-80H520v-200H240v640h240v80H240Zm280-400Zm241 199-19-18 37 37-18-19Z"/>
                        </svg>
                    </div>
                    <div class="text">
                        <div class="title">Manage Products</div>
                        <div class="info">View, add, edit, or remove products <br> from your inventory</div>
                    </div>
                </li>
                <li>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                            <path d="M400-320q100 0 170-70t70-170q0-100-70-170t-170-70q-100 0-170 70t-70 170q0 100 70 170t170 70Zm-40-120v-280h80v280h-80Zm-140 0v-200h80v200h-80Zm280 0v-160h80v160h-80ZM824-80 597-307q-41 32-91 49.5T400-240q-134 0-227-93T80-560q0-134 93-227t227-93q134 0 227 93t93 227q0 56-17.5 106T653-363l227 227-56 56Z"/>
                        </svg>
                    </div>
                    <div class="text">
                        <div class="title">Track Inventory</div>
                        <div class="info">Search for any products current stock level or reorder point</div>
                    </div>
                </li>
                <li>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                            <path d="M160-501q0 71 47.5 122T326-322l-62-62 56-56 160 160-160 160-56-56 64-64q-105-6-176.5-81T80-500q0-109 75.5-184.5T340-760h140v80H340q-75 0-127.5 52T160-501Zm400 261v-80h320v80H560Zm0-220v-80h320v80H560Zm0-220v-80h320v80H560Z"/>
                        </svg>
                    </div>
                    <div class="text">
                        <div class="title">Reorder Items</div>
                        <div class="info">Automate or manually place a reorder request for restocking</div>
                    </div>
                </li>
            </ul>
            <ul>
                <li>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                            <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h360v80H200v560h560v-360h80v360q0 33-23.5 56.5T760-120H200Zm120-160v-80h320v80H320Zm0-120v-80h320v80H320Zm0-120v-80h320v80H320Zm360-80v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80Z"/>
                        </svg>
                    </div>
                    <div class="text">
                        <div class="title">Create Orders</div>
                        <div class="info">Create and manage customer order processes</div>
                    </div>
                </li>
                <li>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                            <path d="m105-399-65-47 200-320 120 140 160-260 120 180 135-214 65 47-198 314-119-179-152 247-121-141-145 233Zm475 159q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29ZM784-80 676-188q-21 14-45.5 21t-50.5 7q-75 0-127.5-52.5T400-340q0-75 52.5-127.5T580-520q75 0 127.5 52.5T760-340q0 26-7 50.5T732-244l108 108-56 56Z"/>
                        </svg>
                    </div>
                    <div class="text">
                        <div class="title">Today's Sales</div>
                        <div class="info">View sales metrics and performance <br> for the day</div>
                    </div>
                </li>
                <li>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                            <path d="M80-200v-80h400v80H80Zm0-200v-80h200v80H80Zm0-200v-80h200v80H80Zm744 400L670-354q-24 17-52.5 25.5T560-320q-83 0-141.5-58.5T360-520q0-83 58.5-141.5T560-720q83 0 141.5 58.5T760-520q0 29-8.5 57.5T726-410l154 154-56 56ZM560-400q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Z"/>
                        </svg>
                    </div>
                    <div class="text">
                        <div class="title">Supplier Lookup</div>
                        <div class="info">Search for contact details of suppliers <br> or supply-chain managers</div>
                    </div>
                </li>
            </ul>
            </div>
        </div>

        <!-- Alerts Popover -->
        <div class="alerts-pop-over" id="alertsPopOverContent">
            alerts go here
        </div>

        <div class="notifications-pop-over admin" id="notificationsPopOverContent">
            <div class="header">Notifications</div>
            <div class="contents grid-scrollbar-design">
                <div class="empty all-and-unread hide">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                            <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160ZM480-80q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
                        </svg>
                    </div>
                    <div class="message">
                        <span class="top">Currently, nothing to report!</span>
                        <span class="bot">Your notifications will appear here when you have some.</span>
                    </div>
                </div>
                <div class="not-empty all-and-unread">
                    <!--Notification Content will be dynamically added here-->
                    <div class="notification-content">
                        <div class="notif-message">Your notification message appears here appears here appears here appears here appears here</div>
                        <div class="notif-message-date">Jan 1, 2020 | 7:31am</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- #region Content -->
    <div id="content">

        <!-- #region User Accounts -->
        <div class="page" id="user_accounts"><?php include 'pages/user_accounts/user_accounts.php'; ?></div>
        <!-- #endregion -->

        <!-- #region Dashboard -->
        <div class="page" id="dashboard"><?php include 'pages/dashboard/dashboard.php'; ?></div>

        <!-- #region Manage Products -->
        <div class="page" id="product_details"><?php include 'pages/product_details/product_details.php'; ?></div>
        <!-- #endregion -->

        <!-- #region Manage Users -->
        <div class="page" id="manage_users"><?php include 'pages/manage_users/manage_users.php'; ?></div>
        <!-- #endregion -->



        <!-- #region Account Settings Movements -->
        <div class="page" id="account_settings"><?php include '../account_settings.php'; ?></div>
        <!-- #endregion -->



    
    </div>
    <!-- #endregion -->

</body>
</html>