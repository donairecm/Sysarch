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

        <link rel="stylesheet" href="../global_css_js/global.css">
        <script type="text/javascript" src="../global_css_js/page_switching.js" defer></script>

        <!-- #region Navbar-Sidebar CSS/JS-->
        <link rel="stylesheet" href="../global_css_js/navbar.css">
        <link rel="stylesheet" href="../global_css_js/sidebar.css">
        <script type="text/javascript" src="../global_css_js/popovers.js" defer></script>
        <script type="text/javascript" src="../global_css_js/sidebar.js" defer></script>
        <!-- #endregion -->

        <!-- #region Dashboard CSS/JS-->
        <link rel="stylesheet" href="pages/dashboard/css/dashboard_grid.css">
        <script type="text/javascript" src="pages/dashboard/js/stock_levels.js" defer></script>
        <script type="text/javascript" src="pages/dashboard/js/units_sold_per_product.js" defer></script>
        <!-- #endregion -->

        <!-- #region Inventory CSS/JS-->
        <link rel="stylesheet" href="pages/inventory/css/inventory_grid.css">
        <script type="text/javascript" src="pages/inventory/js/hp_inventory_activities.js" defer></script>
        <script type="text/javascript" src="pages/inventory/js/hp_inventory_movements.js" defer></script>

        <link rel="stylesheet" href="pages/inventory/css/product_details.css">
        <!-- #endregion -->

        <!-- #region Sales CSS/JS-->
        <link rel="stylesheet" href="pages/sales/css/sales_grid.css">
        <script type="text/javascript" src="pages/sales/js/hp_recent_orders.js" defer></script>
        <!-- #endregion -->

        <!-- #region Supply-chain CSS/JS-->
        <link rel="stylesheet" href="pages/supply_chain/css/supply-chain-grid.css">
        <script type="text/javascript" src="pages/supply_chain/js/hp_order_samples.js" defer></script>
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
            <!-- Inventory -->
            <li class="has-submenu">
                <a class="sidebar-item" href="#" data-page="inventory">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="M620-163 450-333l56-56 114 114 226-226 56 56-282 282Zm220-397h-80v-200h-80v120H280v-120h-80v560h240v80H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h167q11-35 43-57.5t70-22.5q40 0 71.5 22.5T594-840h166q33 0 56.5 23.5T840-760v200ZM480-760q17 0 28.5-11.5T520-800q0-17-11.5-28.5T480-840q-17 0-28.5 11.5T440-800q0 17 11.5 28.5T480-760Z"/>
                            </svg>
                        </div>
                        <span class="sidebar-hover">Inventory</span>
                    </div>
                    <div class="right">
                        <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                        </svg>
                    </div>
                </a>
                <!-- Inventory Menu -->
                <ul class="submenu">
                    <!-- Product List -->
                    <li class="submenu-container">
                        <a class="sidebar-sub-item" href="#" data-page="product_details">
                            <div class="submenu-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M320-280q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm0-160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 320h240v-80H440v80Zm0-160h240v-80H440v80Zm0-160h240v-80H440v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/>
                                </svg>
                            </div>
                            <span>Product Details</span>
                        </a>
                    </li>
                    <!-- Stock Levels -->
                    <li class="submenu-container">
                        <a class="sidebar-sub-item" href="#" data-page="stock_levels">
                            <div class="submenu-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M160-160v-120h160v120H160Zm0-160v-160h160v160H160Zm0-200v-280h160v280H160Zm240 360v-280h160v280H400Zm0-320v-160h160v160H400Zm0-200v-120h160v120H400Zm240 520v-80h160v80H640Zm0-120v-160h160v160H640Zm0-200v-320h160v320H640Z"/>
                                </svg>
                            </div>
                            <span>Stock Levels</span>
                        </a>
                    </li>
                    <!-- Reorder Ponts -->
                    <li class="submenu-container">
                        <a class="sidebar-sub-item" href="#" data-page="reorder_points">
                            <div class="submenu-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-160v-80h200v-80H80v-80h160v-80H122v-80h118v-118l-78-168 72-34 94 200h464l-78-166 72-34 94 200v520H40Zm440-280h160q17 0 28.5-11.5T680-480q0-17-11.5-28.5T640-520H480q-17 0-28.5 11.5T440-480q0 17 11.5 28.5T480-440ZM320-240h480v-360H320v360Zm0 0v-360 360Z"/>
                                </svg>
                            </div>
                            <span>Reorder Points</span>
                        </a>
                    </li>
                    <!-- Inventory Movements -->
                    <li class="submenu-container">
                        <a class="sidebar-sub-item" href="#" data-page="inventory_movements">
                            <div class="submenu-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M824-80 716-188q-22 13-46 20.5t-50 7.5q-75 0-127.5-52.5T440-340q0-75 52.5-127.5T620-520q75 0 127.5 52.5T800-340q0 26-7.5 50T772-244l108 108-56 56ZM620-240q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm220-320h-80v-200h-80v120H280v-120h-80v560h200v80H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h167q11-35 43-57.5t70-22.5q40 0 71.5 22.5T594-840h166q33 0 56.5 23.5T840-760v200ZM480-760q17 0 28.5-11.5T520-800q0-17-11.5-28.5T480-840q-17 0-28.5 11.5T440-800q0 17 11.5 28.5T480-760Z"/>
                                </svg>
                            </div>
                            <span>Inv. Movements</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Sales -->
            <li class="has-submenu">
                <a class="sidebar-item" href="#" data-page="sales">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="M120-120v-80l80-80v160h-80Zm160 0v-240l80-80v320h-80Zm160 0v-320l80 81v239h-80Zm160 0v-239l80-80v319h-80Zm160 0v-400l80-80v480h-80ZM120-327v-113l280-280 160 160 280-280v113L560-447 400-607 120-327Z"/>
                            </svg>
                        </div>
                        <span class="sidebar-hover">Sales</span>
                    </div>
                    <div class="right">
                        <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                        </svg>
                    </div>
                </a>
                <!-- Sales Menu -->
                <ul class="submenu">
                    <!-- Sales Overview -->
                    <li class="submenu-container">
                        <a class="sidebar-sub-item" href="#" data-page="sales_overview">
                            <div class="submenu-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="m787-145 28-28-75-75v-112h-40v128l87 87Zm-587 25q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v268q-19-9-39-15.5t-41-9.5v-243H200v560h242q3 22 9.5 42t15.5 38H200Zm0-120v40-560 243-3 280Zm80-40h163q3-21 9.5-41t14.5-39H280v80Zm0-160h244q32-30 71.5-50t84.5-27v-3H280v80Zm0-160h400v-80H280v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Z"/>
                                </svg>
                            </div>
                            <span>Sales Overview</span>
                        </a>
                    </li>
                    <!-- Manage Orders -->
                    <li class="submenu-container">
                        <a class="sidebar-sub-item" href="#" data-page="manage_orders">
                            <div class="submenu-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M160-160v-516L82-846l72-34 94 202h464l94-202 72 34-78 170v516H160Zm240-280h160q17 0 28.5-11.5T600-480q0-17-11.5-28.5T560-520H400q-17 0-28.5 11.5T360-480q0 17 11.5 28.5T400-440ZM240-240h480v-358H240v358Zm0 0v-358 358Z"/>
                                </svg>
                            </div>
                            <span>Manage Orders</span>
                        </a>
                    </li>
                    <!-- Customer Details -->
                    <li class="submenu-container">
                        <a class="sidebar-sub-item" href="#" data-page="customer_details">
                            <div class="submenu-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M680-320q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T720-440q0-17-11.5-28.5T680-480q-17 0-28.5 11.5T640-440q0 17 11.5 28.5T680-400ZM440-40v-116q0-21 10-39.5t28-29.5q32-19 67.5-31.5T618-275l62 75 62-75q37 6 72 18.5t67 31.5q18 11 28.5 29.5T920-156v116H440Zm79-80h123l-54-66q-18 5-35 13t-34 17v36Zm199 0h122v-36q-16-10-33-17.5T772-186l-54 66Zm-76 0Zm76 0Zm-518 0q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v200q-16-20-35-38t-45-24v-138H200v560h166q-3 11-4.5 22t-1.5 22v36H200Zm80-480h280q26-20 57-30t63-10v-40H280v80Zm0 160h200q0-21 4.5-41t12.5-39H280v80Zm0 160h138q11-9 23.5-16t25.5-13v-51H280v80Zm-80 80v-560 137-17 440Zm480-240Z"/>
                                </svg>
                            </div>
                            <span>Customer Details</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Supply-chain -->
            <li class="has-submenu">
                <a class="sidebar-item" href="#" data-page="supply_chain">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="M240-160q-50 0-85-35t-35-85H40v-440q0-33 23.5-56.5T120-800h560v160h120l120 160v200h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85H360q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T280-280q0-17-11.5-28.5T240-320q-17 0-28.5 11.5T200-280q0 17 11.5 28.5T240-240ZM120-360h32q17-18 39-29t49-11q27 0 49 11t39 29h272v-360H120v360Zm600 120q17 0 28.5-11.5T760-280q0-17-11.5-28.5T720-320q-17 0-28.5 11.5T680-280q0 17 11.5 28.5T720-240Zm-40-200h170l-90-120h-80v120ZM360-540Z"/>
                            </svg>
                        </div>
                        <span class="sidebar-hover">Supply-chain</span>
                    </div>
                    <div class="right">
                        <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                        </svg>
                    </div>
                </a>
                <!-- Supply-chain Menu -->
                <ul class="submenu">
                    <!-- Order Processing -->
                    <li class="submenu-container">
                        <a class="sidebar-sub-item" href="#" data-page="order_processing">
                            <div class="submenu-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M440-183v-274L200-596v274l240 139Zm80 0 240-139v-274L520-457v274Zm-80 92L160-252q-19-11-29.5-29T120-321v-318q0-22 10.5-40t29.5-29l280-161q19-11 40-11t40 11l280 161q19 11 29.5 29t10.5 40v318q0 22-10.5 40T800-252L520-91q-19 11-40 11t-40-11Zm200-528 77-44-237-137-78 45 238 136Zm-160 93 78-45-237-137-78 45 237 137Z"/>
                                </svg>
                            </div>
                            <span>Order Processing</span>
                        </a>
                    </li>
                    <!-- Supplier Details -->
                    <li class="submenu-container">
                        <a class="sidebar-sub-item" href="#" data-page="supplier_details">
                            <div class="submenu-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M80-120q-33 0-56.5-23.5T0-200v-560q0-33 23.5-56.5T80-840h800q33 0 56.5 23.5T960-760v560q0 33-23.5 56.5T880-120H80Zm556-80h244v-560H80v560h4q42-75 116-117.5T360-360q86 0 160 42.5T636-200ZM360-400q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm400 160 80-80-60-80h-66q-6-18-10-38.5t-4-41.5q0-21 4-40.5t10-39.5h66l60-80-80-80q-54 42-87 106.5T640-480q0 69 33 133.5T760-240Zm-578 40h356q-34-38-80.5-59T360-280q-51 0-97 21t-81 59Zm178-280q-17 0-28.5-11.5T320-520q0-17 11.5-28.5T360-560q17 0 28.5 11.5T400-520q0 17-11.5 28.5T360-480Zm120 0Z"/>
                                </svg>
                            </div>
                            <span>Supplier Details</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- users -->
            <li class="has-submenu">
                <a class="sidebar-item" href="#" data-page="users">
                    <div class="left">
                        <div class="icon-container">
                            <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q14-36 44-58t68-22q38 0 68 22t44 58h168q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm280-670q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790Zm0 350q58 0 99-41t41-99q0-58-41-99t-99-41q-58 0-99 41t-41 99q0 58 41 99t99 41ZM200-200h560v-46q-54-53-125.5-83.5T480-360q-83 0-154.5 30.5T200-246v46Z"/>
                            </svg>
                        </div>
                        <span class="sidebar-hover">Users</span>
                    </div>
                    <div class="right">
                        <svg class="sidebar-hover" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                        </svg>
                    </div>
                </a>
                <!-- Staff Menu -->
                <ul class="submenu">
                    <!-- User Profiles -->
                    <li class="submenu-container">
                        <a class="sidebar-sub-item" href="#" data-page="user_profiles">
                            <div class="submenu-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M120-200q-33 0-56.5-23.5T40-280v-400q0-33 23.5-56.5T120-760h400q33 0 56.5 23.5T600-680v400q0 33-23.5 56.5T520-200H120Zm0-146q44-26 94-40t106-14q56 0 106 14t94 40v-334H120v334Zm200 26q-41 0-80 10t-74 30h308q-35-20-74-30t-80-10Zm0-110q-45 0-77.5-32.5T210-540q0-45 32.5-77.5T320-650q45 0 77.5 32.5T430-540q0 45-32.5 77.5T320-430Zm0-74q15 0 25.5-10.5T356-540q0-15-10.5-25.5T320-576q-15 0-25.5 10.5T284-540q0 15 10.5 25.5T320-504Zm360 304v-560h80v560h-80Zm160 0v-560h80v560h-80ZM320-540Zm0 260Z"/>
                                </svg>
                            </div>
                            <span>User Profiles</span>
                        </a>
                    </li>
                    <!-- Activity Logs -->
                    <li class="submenu-container">
                        <a class="sidebar-sub-item" href="#" data-page="activity_logs">
                            <div class="submenu-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-480H200v480Zm80-280v-80h400v80H280Zm0 160v-80h240v80H280Z"/>
                                </svg>
                            </div>
                            <span>Activity Logs</span>
                        </a>
                    </li>
                </ul>
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

                <!-- User Activities -->
                <div class="ic-container ic3" data-tooltip="user-activities-ic-nav"> <!--User Activities hehe-->
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M560-440h200v-80H560v80Zm0-120h200v-80H560v80ZM200-320h320v-22q0-45-44-71.5T360-440q-72 0-116 26.5T200-342v22Zm160-160q33 0 56.5-23.5T440-560q0-33-23.5-56.5T360-640q-33 0-56.5 23.5T280-560q0 33 23.5 56.5T360-480ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Z"/>
                    </svg>
                    <svg class="icon show" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M560-440h200v-80H560v80Zm0-120h200v-80H560v80ZM200-320h320v-22q0-45-44-71.5T360-440q-72 0-116 26.5T200-342v22Zm160-160q33 0 56.5-23.5T440-560q0-33-23.5-56.5T360-640q-33 0-56.5 23.5T280-560q0 33 23.5 56.5T360-480ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z"/>
                    </svg>
                    <span>User Activities</span>
                    <div class="badge">
                    </div>
                </div>

                <!-- Action Tools / Utilities -->
                <div class="ic-container ic4" data-tooltip="Action Tools">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M666-440 440-666l226-226 226 226-226 226Zm-546-80v-320h320v320H120Zm400 400v-320h320v320H520Zm-400 0v-320h320v320H120Z"/>
                    </svg>
                    <svg class="icon show" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M666-440 440-666l226-226 226 226-226 226Zm-546-80v-320h320v320H120Zm400 400v-320h320v320H520Zm-400 0v-320h320v320H120Zm80-480h160v-160H200v160Zm467 48 113-113-113-113-113 113 113 113Zm-67 352h160v-160H600v160Zm-400 0h160v-160H200v160Zm160-400Zm194-65ZM360-360Zm240 0Z"/>
                    </svg>
                </div>
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
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                            <path d="m787-145 28-28-75-75v-112h-40v128l87 87Zm-587 25q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v268q-19-9-39-15.5t-41-9.5v-243H200v560h242q3 22 9.5 42t15.5 38H200Zm0-120v40-560 243-3 280Zm80-40h163q3-21 9.5-41t14.5-39H280v80Zm0-160h244q32-30 71.5-50t84.5-27v-3H280v80Zm0-160h400v-80H280v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Z"/>
                        </svg>
                        <span>Activity Logs</span>
                    </a>
                </li>
                <li>
                    <a href="#">
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

        <!-- User Activities Navbar -->
        <div class="user-activities-nav" id="userActivitiesPopOverContent">
            <div class="header">User Activities</div>
            <div class="row2">
                <div class="filters">
                    <div class="all f active">All</div>
                    <div class="unread f">Inventory </div>
                    <div class="unread f">Sales</div>
                    <div class="unread f">Supply-chain </div>
                </div>
            </div>
            <div class="contents">
                    <div class="empty all-and-unread">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                                <path d="M560-440h200v-80H560v80Zm0-120h200v-80H560v80ZM200-320h320v-22q0-45-44-71.5T360-440q-72 0-116 26.5T200-342v22Zm160-160q33 0 56.5-23.5T440-560q0-33-23.5-56.5T360-640q-33 0-56.5 23.5T280-560q0 33 23.5 56.5T360-480ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z"/>
                            </svg>
                        </div>
                        <div class="message">
                            <span class="top">No activities to show yet!</span>
                            <span class="bot">User activities will appear here once actions have been made.</span>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Notifications Popover -->
        <div class="notifications-pop-over" id="notificationsPopOverContent">
                <div class="header">Notifications</div>
                <div class="row2">
                    <div class="filters">
                        <div class="all f active">All</div>
                        <div class="unread f">Unread 
                            <span class="count"></span>
                        </div>
                        <div class="alerts f ">Alerts
                            <span class="count"></span>
                        </div>
                    </div>
                </div>
                <div class="contents">
                    <div class="empty all-and-unread">
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
                </div>
                <div class="previous-notifs">See previous notifications</div>
        </div>
    </div>

    <!-- #region Content -->
    <div id="content">
    
        <!-- #region Dashboard -->
        <div class="page" id="dashboard"><?php include 'pages/dashboard/pages/dashboard.php'; ?></div>
    
        <!-- #region Inventory -->
        <div class="page" id="inventory"><?php include 'pages/inventory/pages/inventory.php'; ?></div>
        <div class="page" id="product_details"><?php include 'pages/inventory/pages/product_details.php'; ?></div>
        <div class="page" id="stock_levels"><?php include 'pages/inventory/pages/stock_levels.php'; ?></div>
        <div class="page" id="reorder_points"><?php include 'pages/inventory/pages/reorder_points.php'; ?></div>
        <div class="page" id="inventory_movements"><?php include 'pages/inventory/pages/inventory_movements.php'; ?></div>
        <!-- #endregion -->
    
        <!-- #region Sales -->
        <div class="page" id="sales"><?php include 'pages/sales/pages/sales.php'; ?></div>
        <div class="page" id="sales_overview"><?php include 'pages/sales/pages/sales_overview.php'; ?></div>
        <div class="page" id="manage_orders"><?php include 'pages/sales/pages/manage_orders.php'; ?></div>
        <div class="page" id="customer_details"><?php include 'pages/sales/pages/customer_details.php'; ?></div>
        <!-- #endregion -->
    
        <!-- #region Supply Chain -->
        <div class="page" id="supply_chain"><?php include 'pages/supply_chain/pages/supply_chain.php'; ?></div>
        <div class="page" id="order_processing"><?php include 'pages/supply_chain/pages/order_processing.php'; ?></div>
        <div class="page" id="supplier_details"><?php include 'pages/supply_chain/pages/supplier_details.php'; ?></div>
        <!-- #endregion -->
    
        <!-- #region Users -->
        <div class="page" id="users"><?php include 'pages/users/pages/users.php'; ?></div>
        <div class="page" id="user_profiles"><?php include 'pages/users/pages/user_profiles.php'; ?></div>
        <div class="page" id="activity_logs"><?php include 'pages/users/pages/activity_logs.php'; ?></div>
        <!-- #endregion -->
    </div>
    <!-- #endregion -->

</body>
</html>