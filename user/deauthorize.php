<?php
require_once '../api/db_query.php';
require_once '../api/facebook_api.php';

$fb = get_fb_client();
$result = $fb->getSignedRequest();
if (!$result)
    return;

$fid = $result['user_id'];

delete_user($fid);
