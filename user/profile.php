<?php
require_once '../util.php';
require_once get_file_path('api/question.php');
require_once get_file_path('api/facebook_api.php');

check_login_status();

$fuid = $_GET['fuid'];
$profile_img = '<a target="_blank" href="'.'http://www.facebook.com/'.$fuid.'"><img width="200" src="http://graph.facebook.com/'.$fuid.'/picture?type=large"></img></a>';
$profile = array();
$profile = get_user($fuid);

$facebook_link = 'http://www.facebook.com/'.$fuid;

$best_at = array();
$best_at = get_best_at($fuid,3);

$best_at_html = '';
foreach ($best_at as $key => $value) {
    $best_at_html .= '<span class="label label-info" style = "margin-left:5px">'.$value.'</span>';
}



?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $profile['name']; ?> - Eureka</title>
        <?php require_once get_file_path('element/include_css.php'); ?>
    </head>
    <body>
        <?php require_once get_file_path('element/nav.php'); ?>
        <div class="container">
            <div class="row">

                <div class="span2 offset1">
                    <?php echo $profile_img; ?>
                    <h3 align="middle"><?php echo $profile['name']; ?></h3>
                </div>
                <div class="span8">
                    <div class="row">
                        <div class="span5">
                            <h2>Personal Information</h2>
                        </div>
                        <div class="span3" align="right">
                            <a class="btn btn-primary" href=<?php echo $facebook_link?> >Visit on Facebook</a>
                        </div>
                    </div>
                    <div>
                        <table class = "table table-condensed">
                            <tbody>
                                <tr>
                                    <td width = "50px">
                                        <strong>Institution:</strong>
                                    </td>
                                    <td>
                                         <i><?php echo get_institution($profile['institution']); ?></i> 
                                    </td>
                                </tr>
                            </tbody>

                            <tbody>
                                <tr>
                                    <td width = "50px">
                                        <strong>Points:</strong>
                                    </td>
                                    <td>
                                        <i><?php echo $profile['credit']; ?><span><img src=<?php echo get_file_url('img/eureka_on_32.png'); ?> width="16px" height="16px"></span><?php echo get_degree($profile['credit']); ?></i> 
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td width = "50px">
                                        <strong>Ranking:</strong>
                                    </td>
                                    <td>
                                        <i><?php echo get_user_rank($fuid); ?></i>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td width = "50px">
                                        <strong>Best at:</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <?php echo $best_at_html; ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h2>Recent Activities</h2>
                            <ul class="nav nav-tabs ajax-tabs">
                                <li class = "active"><a href="#asked" data-toggle="tab">Asked questions</a></li>
                                <li><a href="#answered" data-toggle="tab">Answered questions</a></li>
                            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="asked">
                                <div class="loading"></div>
                            </div>
                            <div class="tab-pane" id="answered">
                                <div class="loading"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
       </div>
        <?php 
        global $js_includes;
        $js_includes[] = 'js/profile.js';
        require_once get_file_path('element/include_js.php');
        ?>
    </body>
</html>

