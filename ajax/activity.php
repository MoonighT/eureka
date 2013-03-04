<?php
require_once '../util.php';
require_once '../api/facebook_api.php';
require_once '../api/db_query.php';

function get_activities($page) {
    $friend_ids = get_friend_ids_in_apps();
    $post_per_page = get_post_page_number();

    $id_set = implode($friend_ids, ',');
    $frineds_posts = get_friend_posts_and_answers($id_set,$post_per_page*($page-1),$post_per_page);

    $activities = array();
    $totalPages = ceil(get_friend_posts_and_answers_count($id_set)/$post_per_page);


    foreach ($frineds_posts as $key => $value) {
         $post_info = get_post_info($value['post_id']);
         $activities[] = array('type' => $value['type'],'qid'=>$post_info['post_id'], 'name' => get_user_name($value['user_id']), 'fuid' => $value['user_id'],
             'title' => $post_info['title'],'time'=>$value['date']);
    }

    return array('data' => $activities, 'totalPages' => $totalPages, 'currentPage' => $page);
}
$page = get_current_page();

echo json_encode(get_activities($page));
