<?php
require_once '../api/facebook_api.php';
check_login_status('login');
$fb = get_fb_client();
$login_url = $fb->getLoginUrl(array(
    'scope' => 'email, publish_actions, user_education_history',
    'redirect_uri' => get_file_url('user/sign_up.php'),
));
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="https://www.facebook.com/2008/fbml">
    <head>
        <meta charset="utf8" />
        <meta property="og:title" content="Eureka" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="http://54.251.48.198/user/login.php" />
        <meta property="og:image" content="http://54.251.48.198/img/eureka_on_254.png" />
        <meta property="og:site_name" content="Eureka" />
        <meta property="fb:app_id" content="263953760391187" />
        <meta property="og:description" 
            content="Join the fun social learning platform to crowd source solutions and solve challenging academic questions!" />
        <title>Eureka</title>
        <?php require_once '../element/include_css.php' ?>
    </head>
    <body style="background-color:#000000">
        <div class="container" >
            <div style="width:960px; margin:0px auto; position:relative">
                <div style="width:400px; float:right">
                    <h3 style="color:yellow; font-family:fantasy,Helvetica,Arial,sans-serif; margin-top:100px ">Join the <big style="color:white; font-weight:900">fun</big> social learning platform to crowd source solutions and solve <big style="color:white">challenging</big> academic questions!</p>
                </div>
                <div style="width:500px">
                    <img src="../img/latest_logo.png" height="300px" width="500px">
                </div>
            </div>
            <div style="margin-top:40px; width:100%; text-align:center">
                <a href="<?php echo $login_url; ?>" class="btn btn-warning" style="font-size:16px; padding:10px 20px; color:black">
                    Enter with Facebook
                </a>
            </div>
        </div>
        <script src="../js/jquery-1.7.2.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/google_analytics.js"></script>
        <script src="../js/facebook.js"></script>
   </body>
</html>
