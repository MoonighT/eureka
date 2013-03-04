<?php
require_once __DIR__ . '/../util.php';
require_once get_file_path('api/facebook_api.php');
require_once get_file_path('api/db_query.php');

function get_all_items($items = array(), $page, $type, $id_set = null){
    $rank_items = array();
    foreach ($items as $key => $value) {
        $user_id = $value['fid'];
        if($type == 'global')
            $user_rank = get_user_rank($user_id);
        else
            $user_rank = get_user_friend_rank($user_id, $id_set);
        $user_name = $value['name'];
        $user_credit = $value['credit'];
        $user_info = get_user($user_id);
        $user_institution = get_institution($user_info['institution']);
        $user_best_at = get_best_at($user_id,3);
        $user_degree = get_degree($user_credit);
        $temp = array('user_id'=>$user_id,'user_rank'=>$user_rank,'user_name'=>$user_name,'user_credit'=>$user_credit,
        	'user_institution'=>$user_institution,'user_best_at'=>$user_best_at,'user_degree'=>$user_degree);
        $rank_items[]=$temp;
    }
    return $rank_items;
}

function get_all_global($type, $page) {
    
    $fb = get_fb_client();
    $fid = $fb->getUser();

    $posts_per_page = get_post_page_number();
    //global rank
    $start = ($page-1)*$posts_per_page;
    $limit = 'limit '.$start.','.$posts_per_page;
    $rank_list = get_ranking($limit);
    $total_item_num = get_global_ranking_count();

    $totalPages_global = ceil ($total_item_num*1.0/$posts_per_page);

    //friend rank

	$friend_ids = get_friend_ids_in_apps();
	$friend_ids[] = $fb->getUser();
	$total_item_num_friend = sizeof($friend_ids);
	
	$id_set = implode($friend_ids, ',');
	$rank_list_friend = get_ranking($limit, "where fid in ($id_set)");

    
    $totalPages_friend = ceil($total_item_num_friend*1.0/$posts_per_page);


    $totalPages = $type == 'global' ? $totalPages_global: $totalPages_friend;
    $items = $type == 'global' ? get_all_items($rank_list,$page,'global') : get_all_items($rank_list_friend,$page,'friend',$id_set);

    return array('data' => $items, 'totalPages' => $totalPages, 'currentPage' => $page);
}


$type = empty($_GET['type']) ? 'global' : $_GET['type'] ;
echo json_encode(get_all_global($type,get_current_page()));

