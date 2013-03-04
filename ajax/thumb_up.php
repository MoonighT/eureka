<?php
require_once '../api/facebook_api.php';
require_once '../api/db_query.php';

check_login_status();

global $user;
$action = $_POST['action'];
$type = $_POST['type'];
$id = $_POST['id'];
if (!$action || !$type || !$id)
    return;

$user_id = $user['fid'];
if ($type == 'question') {
    $question = get_post_info($id);
    if (!$question || $question['user_id'] == $user_id)
        return;

    if ($action == 'thumb-up') {
        thumb_up_question($id, $user_id);
    } else {
        cancel_thumb_up_question($id, $user_id);
    }
    $count = get_question_thumb_up_count($id);
} else {
    $answer = get_answer_info($id);
    if (!$answer || $answer['user_id'] == $user_id)
        return;

    if ($action == 'thumb-up') {
        thumb_up_answer($id, $user_id);
    } else {
        cancel_thumb_up_answer($id, $user_id);
    }
    $count = get_answer_thumb_up_count($id);
}

echo json_encode(array('count' => $count));
