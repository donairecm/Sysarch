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

// Return the logged-in employee ID

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inventory Interface</title>

        <link rel="stylesheet" href="../global_css_js/global.css">
        <script type="text/javascript" src="../global_css_js/page_switching.js" defer></script>
        <script type="text/javascript" src="../global_css_js/get_acc_details.js" defer></script>
        <script type="text/javascript" src="../global_css_js/choose_prof_pic.js" defer></script>

        <!-- #region Navbar-Sidebar CSS/JS-->
        <link rel="stylesheet" href="../global_css_js/navbar.css">
        <link rel="stylesheet" href="../global_css_js/sidebar.css">
        <script type="text/javascript" src="../global_css_js/popovers.js" defer></script>
        <script type="text/javascript" src="../global_css_js/sidebar.js" defer></script>
        <!-- #endregion -->

        <!-- #region Dashboard CSS/JS-->
        <link rel="stylesheet" href="../super_admin-interface/pages/dashboard/css/dashboard_grid.css">
        <link rel="stylesheet" href="pages/dashboard/css/inventory_grid.css">
        <script type="text/javascript" src="pages/dashboard/js/get_stock_lvl.js" defer></script>
        <script type="text/javascript" src="pages/dashboard/js/get_in_val.js" defer></script>
        <script type="text/javascript" src="pages/dashboard/js/get_in_turno.js" defer></script>
        <script type="text/javascript" src="pages/dashboard/js/get_in_rr.js" defer></script>
        <script>
    const employeeID = "<?php echo $employeeID; ?>";
</script>
        <script type="text/javascript" src="get_notifications.js" defer></script>

        <!-- #endregion -->

        <!-- #region Product Details-->
        <link rel="stylesheet" href="pages/product_details/css/product_details.css">
        <script type="text/javascript" src="pages/product_details/js/get_pd.js" defer></script>
        <script type="text/javascript" src="pages/product_details/js/filters.js" defer></script>
        <script type="text/javascript" src="pages/product_details/js/update_pd.js" defer></script>
        <script type="text/javascript" src="pages/product_details/js/add_product.js" defer></script>
        <script type="text/javascript" src="pages/product_details/js/request_reorder.js" defer></script>
        <!-- #endregion -->

        <!-- #region Inventory Movements-->
        <link rel="stylesheet" href="pages/inventory_movements/css/inventory_movements.css">
        <script type="text/javascript" src="pages/inventory_movements/js/filters.js" defer></script>
        <script type="text/javascript" src="pages/inventory_movements/js/get_imv_rr.js" defer></script>
        <!-- #endregion -->


        <!-- #region chart.js scripts-->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.2"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix"></script>
        <!-- #endregion -->

    </head>
<body>
    <nav id="sidebar">
        <div class="logo-wrapper">
            <div class="logo-container">
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
            <!-- Product Details -->
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
            <!-- Inventory Movements -->
            <li class="has-submenu">
                <a class="sidebar-item" href="#" data-page="inventory_movements">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M740-208v-112h-40v128l86 86 28-28-74-74ZM480-800 243-663l237 137 237-137-237-137ZM120-321v-318q0-22 10.5-40t29.5-29l280-161q10-5 19.5-8t20.5-3q11 0 21 3t19 8l280 161q19 11 29.5 29t10.5 40v159h-80v-116L479-434 200-596v274l240 139v92L160-252q-19-11-29.5-29T120-321ZM720 0q-83 0-141.5-58.5T520-200q0-83 58.5-141.5T720-400q83 0 141.5 58.5T920-200q0 83-58.5 141.5T720 0ZM480-491Z"/></svg>
                        </div>
                        <span class="sidebar-hover">Inventory Movements</span>
                    </div>
                </a>
            </li>
             <!-- Stock Levels not needed but just in case
             <li class="has-submenu">
                <a class="sidebar-item" href="#" data-page="product_details">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="M640-160v-280h160v280H640Zm-240 0v-640h160v640H400Zm-240 0v-440h160v440H160Z"/>
                            </svg>
                        </div>
                        <span class="sidebar-hover">Stock Levels</span>
                    </div>
                </a>
            </li> -->
             <!-- Reorder Points not needed but just in case
             <li class="has-submenu">
                <a class="sidebar-item" href="#" data-page="product_details">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M320-160q-117 0-198.5-81.5T40-440q0-107 70.5-186.5T287-718l-63-66 56-56 160 160-160 160-56-57 59-59q-71 14-117 69t-46 127q0 83 58.5 141.5T320-240h120v80H320Zm200-360v-280h360v280H520Zm0 360v-280h360v280H520Zm80-80h200v-120H600v120Z"/></svg>
                        </div>
                        <span class="sidebar-hover">Reorder Points</span>
                    </div>
                </a>
            </li> -->
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
                <div class="ic-container ic1 inventory" data-tooltip="Notifications">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160ZM480-80q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
                    </svg>
                    <svg class="icon show" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80ZM320-280h320v-280q0-66-47-113t-113-47q-66 0-113 47t-47 113v280Z"/>
                    </svg>
                    <div class="badge">
                    </div>
                </div>

                <!-- Inventory Movements redundant
                <div class="ic-container ic3 in" data-tooltip="user-activities-ic-nav"> 
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M560-440h200v-80H560v80Zm0-120h200v-80H560v80ZM200-320h320v-22q0-45-44-71.5T360-440q-72 0-116 26.5T200-342v22Zm160-160q33 0 56.5-23.5T440-560q0-33-23.5-56.5T360-640q-33 0-56.5 23.5T280-560q0 33 23.5 56.5T360-480ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Z"/>
                    </svg>
                    <svg class="icon show" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M240-440h360v-80H240v80Zm0-120h360v-80H240v80Zm-80 400q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z"/>
                    </svg>
                    <span>Inventory Movements</span>
                    <div class="badge">
                    </div>
                </div> -->

                <!-- Action Tools / Utilities not really needed the system is not that complex
                <div class="ic-container ic4" data-tooltip="Action Tools">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M666-440 440-666l226-226 226 226-226 226Zm-546-80v-320h320v320H120Zm400 400v-320h320v320H520Zm-400 0v-320h320v320H120Z"/>
                    </svg>
                    <svg class="icon show" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M666-440 440-666l226-226 226 226-226 226Zm-546-80v-320h320v320H120Zm400 400v-320h320v320H520Zm-400 0v-320h320v320H120Zm80-480h160v-160H200v160Zm467 48 113-113-113-113-113 113 113 113Zm-67 352h160v-160H600v160Zm-400 0h160v-160H200v160Zm160-400Zm194-65ZM360-360Zm240 0Z"/>
                    </svg>
                </div> -->
            </div>
            <!-- Profile -->
            <div class="profile-container inventory" onclick="toggleDropdown()">
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
        <div class="profile-pop-over inventory" id="profilePopOverContent" >
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
                    <a href="#" >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                            <path d="m787-145 28-28-75-75v-112h-40v128l87 87Zm-587 25q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v268q-19-9-39-15.5t-41-9.5v-243H200v560h242q3 22 9.5 42t15.5 38H200Zm0-120v40-560 243-3 280Zm80-40h163q3-21 9.5-41t14.5-39H280v80Zm0-160h244q32-30 71.5-50t84.5-27v-3H280v80Zm0-160h400v-80H280v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Z"/>
                        </svg>
                        <span>Activity Logs</span>
                    </a>
                </li>
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

        <!-- Notifications Popover -->
        <div class="notifications-pop-over inventory" id="notificationsPopOverContent">
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
        
        <!-- #region Dashboard -->
        <div class="page" id="dashboard"><?php include 'pages/dashboard/dashboard.php'; ?></div>
        <!-- #endregion -->

        <!-- #region Product Details -->
        <div class="page" id="product_details"><?php include 'pages/product_details/product_details.php'; ?></div>
        <!-- #endregion -->

        <!-- #region Inventory Movements -->
         <div class="page" id="inventory_movements"><?php include 'pages/inventory_movements/inventory_movements.php'; ?></div>
        <!-- #endregion -->

        <!-- #region Account Settings Movements -->
        <div class="page" id="account_settings"><?php include '../account_settings.php'; ?></div>
        <!-- #endregion -->
        


    </div>
    <!-- #endregion -->



</body>
</html>