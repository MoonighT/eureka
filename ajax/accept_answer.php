<?php
require_once '../api/facebook_api.php';
require_once '../api/db_query.php';
check_login_status();

$answer_id = $_POST['id'];
$action = $_POST['action'];
if (!$answer_id || !$action)
    return;

global $user;
if ($action == 'accept') {
    accept_answer($answer_id);
} else {
    cancel_accept_answer($answer_id);
}
$status = 'success';
echo json_encode(array('status' => $status));
