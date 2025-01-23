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
        <title>Sales Interface</title>

        <link rel="stylesheet" href="../global_css_js/global.css">
        <script type="text/javascript" src="../global_css_js/page_switching.js" defer></script>
        <script type="text/javascript" src="../global_css_js/get_acc_details.js" defer></script>
        <script type="text/javascript" src="../global_css_js/choose_prof_pic.js" defer></script>
        <link rel="stylesheet" href="../inventory-interface/pages/product_details/css/product_details.css">

        <!-- #region Navbar-Sidebar CSS/JS-->
        <link rel="stylesheet" href="../global_css_js/navbar.css">
        <link rel="stylesheet" href="../global_css_js/sidebar.css">
        <script type="text/javascript" src="../global_css_js/popovers.js" defer></script>
        <script type="text/javascript" src="../global_css_js/sidebar.js" defer></script>
        <!-- #endregion -->

        <!-- #region Order Processing CSS/JS-->
        <link rel="stylesheet" href="pages/order_processing/css/order_processing.css">
        <!-- #endregion -->

        <!-- #region Manage Orders CSS/JS-->
        <link rel="stylesheet" href="pages/manage_orders/css/manage_orders.css">
        <script type="text/javascript" src="pages/manage_orders//js/get_sales_data.js" defer></script>
        <script type="text/javascript" src="pages/manage_orders//js/order_item.js" defer></script>
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


    <div id="overlay"></div>

    <nav id="navbar" class="supply_chain">
    <div class="logo-wrapper" onclick="goToMainPage()">
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



        <div class="icon-container">
            <div class="left-container">
         
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

    <!-- #region Manage Orders -->
    <div class="page" id="order_processing"><?php include 'pages/manage_orders/manage_orders.php'; ?></div>
    <!-- #endregion -->

    <!-- #region Account Settings Movements -->
    <div class="page" id="account_settings"><?php include '../account_settings.php'; ?></div>
    <!-- #endregion -->

    </div>
    <!-- #endregion -->



</body>
</html>