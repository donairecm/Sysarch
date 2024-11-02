<?php
include 'login-interface/php/db_connection.php';
?>

<?php
include 'login-interface/php/login_metrics.php';
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
    <div class="form-container sign-up">
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
        <input type="password" name="password" id="password" placeholder=" " onfocus="hideTooltip('password-error')">
        <label for="password">Password</label>
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
