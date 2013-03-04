<?php
require_once __DIR__ . '/../util.php';
require_once get_file_path('api/db_query.php');
require_once get_file_path('api/facebook_api.php');

global $js_includes;
$js_includes[] = 'js/friend_ranking.js';
$global_ranking = get_ranking('limit 0, 5');

function get_ranking_item($rank, $user) {
    $name = $user['name'];
    $credit = $user['credit'];
    $user_id = $user['fid'];
    $degree = '<div class="ranking-degree">' .get_degree($credit).'</div>';
    return '<li><div style="margin-bottom: 5px">' .
        '<span class="badge badge-info">' . $rank . '</span> ' .
        '<a href="' . get_file_url('user/profile.php?fuid=' . $user['fid']) . '">' . $name . '</a>' .
        '<span class="pull-right">' . $credit .get_small_bulb_element(). '</span>'. $degree .
        '</div></li>';
}
?>
<div class="span3">
    <div class="well" style="margin-bottom: 20px; padding: 0">
        <div class="fb-like-box" data-href="http://www.facebook.com/eurekax" data-width="270" data-show-faces="true" 
            data-stream="false" data-header="false" data-height="200" data-border-color="#F5F5F5"></div>
    </div>
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
            <li class="nav-header">Global Ranking</li>
            <?php
            foreach ($global_ranking as $index => $user)
                echo get_ranking_item($index + 1, $user);
            ?>
        </ul>
        <div align="right">
            <a class="btn btn-primary btn-mini" href="<?php echo get_file_url('ranking.php?tab=global'); ?>">View More</a>
        </div>
    </div>
    <div class="well sidebar-nav" id="friend-ranking-block" style="display: none">
        <ul class="nav nav-list">
            <li class="nav-header">Friend Ranking</li>
        </ul>
        <div align="right">
            <a class="btn btn-primary btn-mini" href="<?php echo get_file_url('ranking.php?tab=friend'); ?>">View More</a>
        </div>
    </div>
    <?php require_once get_file_path('element/invite_sidebar.php'); ?>
</div>
