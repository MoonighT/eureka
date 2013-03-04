<?php
require_once 'api/question.php';
require_once 'api/facebook_api.php';

check_login_status();
$tab = $_GET['tab'];

if ($tab != 'friend') {
    $global_class = 'class="active"';
    $global_tab_class = 'active';
    $friend_class = $friend_tab_class = '';
} else {
    $friend_class = 'class="active"';
    $friend_tab_class = 'active';
    $global_class = $global_tab_class = '';
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8">
        <?php require_once 'element/include_css.php'; ?>
        <title>Ranking - Eureka</title>
    </head>
    <body>
        <?php require_once("element/nav.php"); ?>
        <div class="container">
            <div class = "row">
                <div class="span7 offset1">
                    <ul class="nav nav-tabs ajax-tabs">
                        <li <?php echo $global_class; ?>><a href="#global" data-toggle="tab">Global Ranking</a></li>
                        <li <?php echo $friend_class; ?>><a href="#friend" data-toggle="tab">Friend Ranking</a></li>
                    </ul>
                    <div class="tab-content ranking-list">
                        <div class="tab-pane <?php echo $global_tab_class; ?>" id="global"></div>
                        <div class="tab-pane <?php echo $friend_tab_class; ?>" id="friend"></div>
                    </div>
                </div>
                <div class="span3">
                    <div class="well sidebar-nav">
                        <ul class="nav nav-list">
                            <li class="nav-header">How to gain Bulbs?</li>
                            <li>
                                <div style="margin-bottom:5px">
                                    <span style="color:#08c">Post an answer</span> 
                                    <span class="pull-right">+1<span><img src=<?php echo get_file_url('img/eureka_on_16.png'); ?> ></span></span>
                                </div>
                                <div style="margin-bottom:5px">
                                    <span style="color:#08c">Answer gets "Light"</span> 
                                    <span class="pull-right">+1<span><img src=<?php echo get_file_url('img/eureka_on_16.png'); ?> ></span></span>
                                </div>
                                <div style="margin-bottom:5px">
                                    <span style="color:#08c">Question gets "Light"</span> 
                                    <span class="pull-right">+1<span><img src=<?php echo get_file_url('img/eureka_on_16.png'); ?> ></span></span>
                                </div>
                                <div style="margin-bottom:5px">
                                    <span style="color:#08c">Answer gets selected</span> 
                                    <span class="pull-right">+5<span><img src=<?php echo get_file_url('img/eureka_on_16.png'); ?> ></span></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="well sidebar-nav">
                        <ul class="nav nav-list">
                            <li class="nav-header">Academic Attainment</li>
                            <li>
                                <div style="margin-bottom:5px">
                                    <span style="color:#08c">PhD</span> 
                                    <span class="pull-right">500<span><img src=<?php echo get_file_url('img/eureka_on_16.png'); ?> ></span></span>
                                </div>
                            </li>
                            <li>
                                <div style="margin-bottom:5px">
                                    <span style="color:#08c">Masters</span>
                                    <span class="pull-right">200<span><img src=<?php echo get_file_url('img/eureka_on_16.png'); ?> ></span></span>
                                </div>
                            </li>
                            <li>
                                <div style="margin-bottom:5px">
                                    <span style="color:#08c">Undergraduate</span>
                                    <span class="pull-right">100<span><img src=<?php echo get_file_url('img/eureka_on_16.png'); ?> ></span></span>
                                </div>
                            </li>
                            <li>
                                <div style="margin-bottom:5px">
                                    <span style="color:#08c">Junior College</span>
                                    <span class="pull-right">50<span><img src=<?php echo get_file_url('img/eureka_on_16.png'); ?> ></span></span>
                                </div>
                            </li>
                            <li>
                                <div style="margin-bottom:5px">
                                    <span style="color:#08c">Secondary</span>
                                    <span class="pull-right">25<span><img src=<?php echo get_file_url('img/eureka_on_16.png'); ?> ></span></span>
                                </div>
                            </li>
                            <li>
                                <div style="margin-bottom:5px">
                                    <span style="color:#08c">Primary</span>
                                    <span class="pull-right">10<span><img src=<?php echo get_file_url('img/eureka_on_16.png'); ?> ></span></span>
                                </div>
                            </li>
                            <li>
                                <div style="margin-bottom:5px">
                                    <span style="color:#08c">Kindergarten</span>
                                    <span class="pull-right">0<span><img src=<?php echo get_file_url('img/eureka_on_16.png'); ?> ></span></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once 'element/include_js.php'; ?>
        <script src="js/ranking.js"></script>
    </body>
</html>
