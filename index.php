<?php
require_once 'util.php';
require_once 'api/facebook_api.php';
check_login_status();
$is_new_user = $_SESSION['is_new_user'] === 'true';
if ($is_new_user)
    unset($_SESSION['is_new_user']);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8" />
        <?php require_once 'element/include_css.php'; ?>
        <title>Eureka</title>
    </head>
    <body>
        <?php require_once('element/nav.php'); ?>
        <div class="container">
            <div class="row">
                <div class="span7 offset1">
                    <?php
                    if ($is_new_user) {
                    ?>
                    <div class="alert alert-success">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <strong>Congratulations!</strong><br>
                        We've just awarded 5 Bulbs to get you started! Start answering questions to earn more and rise up in rank!
                    </div>
                    <?php
                    }
                    ?>
                    <ul class="nav nav-tabs ajax-tabs">
                        <li class="active"><a href="#challenge" data-toggle="tab">Challenges</a></li>
                        <li><a href="#activity" data-toggle="tab">Activities</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="activity">
                            <div class="loading"></div>
                        </div>
                        <div class="tab-pane active" id="challenge">
                            <ul class="nav nav-pills ajax-tabs">
                                <li>
                                    <a href="#interesting" data-toggle="tab">Interesting</a>
                                </li>
                                <li class="active">
                                    <a href="#recent" data-toggle="tab">Recent</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane" id="interesting">
                                    <div class="loading"></div>
                                </div>
                                <div class="tab-pane active" id="recent">
                                    <div class="loading"></div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div> 
                <?php require_once 'element/sidebar.php'; ?>
            </div>
            <?php require_once 'element/footer.php'; ?>
        </div>
        <?php require_once 'element/include_js.php'; ?>
        <script src="js/eureka.js"></script>
    </body>
</html>
