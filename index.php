<?php
include 'php/db_connection.php';
?>

<?php
include 'php/login_metrics.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Aluminum Sales Corp.</title>
    <link rel="stylesheet" href="login-interface/css/style.css">
    <link rel="stylesheet" href="login-interface/css/media-query.css">
    <script type="text/javascript" src="login-interface/script.js" defer></script>

</head>
<body>
    <div class="logo-container-mobile">
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
    </div>
    <div class="main-container">
        <div class="left-panel">
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
            <div class="welcome-wrapper">
                <h1><span class="welcome">Welcome</span></br><span class="back">Back!</span></h1>
                <p>Fill in your details to log in and get started!</p>
            </div>
            <div class="idk">.</div>
        </div>
        <div class="right-panel">
    <div class="form-container">
    <form action="" method="POST" id="login-form">
    <div class="form-header">Sign in</div>
    
    <!-- Username error container -->
    <div class="username-error-container">
        <div class="login-errors tooltip-style" id="username-error">--error message--</div>
    </div>
    
    <!-- Username input -->
    <div class="input-group">
        <input type="text" name="username" id="username" placeholder=" " onfocus="hideTooltip('username-error')">
        <label for="username">Username</label>
    </div>
    
    <!-- Password error container -->
    <div class="password-error-container">
        <div class="login-errors tooltip-style" id="password-error">--error message--</div>
    </div>
    
    <!-- Password input -->
    <div class="input-group">
        <input type="password" name="password" id="password" placeholder=" " 
        onfocus="showPasswordIcons()" onblur="hidePasswordIcons()">
        <label for="password">Password</label>
        <span class="toggle-password" onclick="togglePasswordVisibility()">
       <!-- Open Eye SVG Icon -->
       <svg id="open-eye" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
            <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/>
        </svg>
        <!-- Closed Eye SVG Icon -->
        <svg id="closed-eye" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" >
            <path d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"/>
     </svg>
    </span>
    </div>
    <div class="mid">
                            <!--<div class="remember">
                                <input type="checkbox">
                                <p class="text">Remember me</p>
                            </div>-->
                            <a href="#" class="text">Forgot your password?</a>
                        </div>
    <button type="submit" name="login">Sign in</button>
</form>
    </div>
</div>

    </div>
</body>
</html>
