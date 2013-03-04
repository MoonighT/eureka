<?php
require_once '../util.php';
require_once get_file_path('api/question.php');
require_once get_file_path('api/facebook_api.php');

check_login_status();

$fb = get_fb_client();
$fuid = $fb->getUser();
 
$profile_img = '<a target="_blank" href="'.'http://www.facebook.com/'.$fuid.'"><img width="200" src="http://graph.facebook.com/'.$fuid.'/picture?type=large"></img></a>';
$profile = array();
$profile = get_user($fuid);

$best_at = array();
$best_at = get_best_at($fuid,3);

$best_at_html = '';
foreach ($best_at as $key => $value) {
    $best_at_html .= '<span class="label label-info" style = "margin-left:5px">'.$value.'</span>';
}
$Interest = array();
$Interest = get_user_interested_subjects($fuid);
$interest_html = '';
foreach ($Interest as $key => $value) {
    $value = get_subject($value);
    $interest_html .= '<span class="label label-warning" style = "margin-left:5px">'.$value.'</span>';
}


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $profile['name']; ?> - Eureka</title>
        <?php require_once get_file_path('element/include_css.php'); ?>
        <style>
            input[type="text"][name="subjects"] {
                margin-left: 7px;
                display: none;
            }
            input[type="text"][name="institution"] {
                display: none;
            }
        </style>
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
                            <button id="edit-btn" data-original-title="Edit" class="btn"><i class="icon-edit"></i></button>
                            <button id="cancel-btn" data-original-title="Cancel" class="btn" style="display: none"><i class="icon-remove"></i></button>
                        </div>
                    </div>
                    <div>
                        <table class="table table-condensed">
                            <tbody>
                                <tr>
                                    <td width = "50px">
                                        <strong>Institution:</strong>
                                    </td>
                                    <td>
                                         <i id="institution-label"><?php echo get_institution($profile['institution']); ?></i> 
                                         <input required="true" id="profile-institution" name="institution" type="text">
                                    </td>
                                </tr>
                                <tr>
                                    <td width = "50px">
                                        <strong>Points:</strong>
                                    </td>
                                    <td>
                                        <i><?php echo $profile['credit']; ?><span><img src=<?php echo get_file_url('img/eureka_on_32.png'); ?> width="16px" height="16px"></span><?php echo get_degree($profile['credit']); ?></i> 
                                    </td>
                                </tr>
                                <tr>
                                    <td width = "50px">
                                        <strong>Ranking:</strong>
                                    </td>
                                    <td>
                                        <i><?php echo get_user_rank($fuid); ?></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td width = "50px">
                                        <strong>Interest:</strong>
                                    </td>
                                    <td id="interest-row">
                                        <div id="interest-labels">
                                            <?php echo $interest_html ?>
                                        </div>
                                        <input required="true" name="subjects" type="text" id="profile-subjects">
                                    </td>
                                </tr>
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

