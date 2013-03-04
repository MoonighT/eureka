<?php
require_once 'api/facebook_api.php';
check_login_status();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Questions - Eureka</title>
        <?php require_once 'element/include_css.php'; ?>
    </head>
    <body>
        <?php require_once 'element/nav.php'; ?>
        <div class="container">
            <div class="row">
                <div class="span7 offset1" id="result_container">
                    <div class="loading"></div>
                </div>
                <?php require_once 'element/sidebar.php'; ?>
            </div>
            <?php require_once 'element/footer.php'; ?>
        </div>
        <?php require_once 'element/include_js.php'; ?>
        <script src="js/search.js"></script>
    </body>
</html>
