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
        <title>Inventory Interface</title>

        <link rel="stylesheet" href="../global_css_js/global.css">
        <link rel="stylesheet" href="../inventory-interface/pages/product_details/css/product_details.css">
        <script type="text/javascript" src="../global_css_js/page_switching.js" defer></script>
        <script type="text/javascript" src="../global_css_js/get_acc_details.js" defer></script>
        <script type="text/javascript" src="../global_css_js/choose_prof_pic.js" defer></script>

        <!-- #region Navbar-Sidebar CSS/JS-->
        <link rel="stylesheet" href="../global_css_js/navbar.css">
        <link rel="stylesheet" href="../global_css_js/sidebar.css">
        <script type="text/javascript" src="../global_css_js/popovers.js" defer></script>
        <script type="text/javascript" src="../global_css_js/sidebar.js" defer></script>
        <!-- #endregion -->

        <!-- #region Order Processing CSS/JS-->
        <link rel="stylesheet" href="pages/order_processing/css/order_processing.css">
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
            <!-- Order Processing -->
            <li class="active">
                <a class="sidebar-item" href="#" data-page="order_processing">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="M240-160q-50 0-85-35t-35-85H40v-440q0-33 23.5-56.5T120-800h560v160h120l120 160v200h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85H360q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T280-280q0-17-11.5-28.5T240-320q-17 0-28.5 11.5T200-280q0 17 11.5 28.5T240-240ZM120-360h32q17-18 39-29t49-11q27 0 49 11t39 29h272v-360H120v360Zm600 120q17 0 28.5-11.5T760-280q0-17-11.5-28.5T720-320q-17 0-28.5 11.5T680-280q0 17 11.5 28.5T720-240Zm-40-200h170l-90-120h-80v120ZM360-540Z"/>
                            </svg>
                        </div>
                        <span class="sidebar-hover">Order Processing</span>
                    </div>
                </a>
            </li>
            
            <!-- Manage Suppliers -->
            <li class="has-submenu">
                <a class="sidebar-item" href="#" data-page="manage_suppliers">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="M80-120q-33 0-56.5-23.5T0-200v-560q0-33 23.5-56.5T80-840h800q33 0 56.5 23.5T960-760v560q0 33-23.5 56.5T880-120H80Zm556-80h244v-560H80v560h4q42-75 116-117.5T360-360q86 0 160 42.5T636-200ZM360-400q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm400 160 80-80-60-80h-66q-6-18-10-38.5t-4-41.5q0-21 4-40.5t10-39.5h66l60-80-80-80q-54 42-87 106.5T640-480q0 69 33 133.5T760-240Zm-578 40h356q-34-38-80.5-59T360-280q-51 0-97 21t-81 59Zm178-280q-17 0-28.5-11.5T320-520q0-17 11.5-28.5T360-560q17 0 28.5 11.5T400-520q0 17-11.5 28.5T360-480Zm120 0Z"/>
                            </svg>
                        </div>
                        <span class="sidebar-hover">Manage Suppliers</span>
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
                <div class="ic-container ic1 supply_chain" data-tooltip="Notifications">
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
        <div class="profile-pop-over supply_chain" id="profilePopOverContent" >
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
        <div class="notifications-pop-over supply_chain" id="notificationsPopOverContent">
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

    <!-- #region Order Processing -->
    <div class="page" id="order_processing"><?php include 'pages/order_processing/order_processing.php'; ?></div>
    <!-- #endregion -->

    <!-- #region Manage Orders -->
    <div class="page" id="manage_suppliers"><?php include 'pages/manage_suppliers/manage_suppliers.php'; ?></div>
    <!-- #endregion -->

    <!-- #region Account Settings Movements -->
    <div class="page" id="account_settings"><?php include '../account_settings.php'; ?></div>
    <!-- #endregion -->

    </div>
    <!-- #endregion -->



</body>
</html>