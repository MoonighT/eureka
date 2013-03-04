<?php
require_once __DIR__ . '/../util.php';
require_once get_file_path('api/facebook_api.php');
require_once get_file_path('api/db_query.php');

$fb = get_fb_client();
$friend_ids = get_friend_ids_in_apps();
$friend_ids[] = $fb->getUser();
$id_set = implode($friend_ids, ',');
$ranking = get_ranking('limit 0, 5', "where fid in ($id_set)");

echo json_encode($ranking);
