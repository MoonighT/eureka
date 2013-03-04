<?php
require_once '../api/facebook_api.php';
require_once '../api/question.php';

check_login_status();

function validate_new_question($title, $content, $subject) {
    if (empty($title))
        return false;
    if (empty($content))
        return false;
    if (empty($subject))
        return false;
    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $subject = trim($_POST['subject']);
    $institution = trim($_POST['institution']);
    $level_of_study = $_POST['level-of-study'];
    if ($level_of_study == 0)
        $level_of_study = null;
    $valid = validate_new_question($title, $content, $subject, $institution, $level_of_study);
    if ($valid) {
        $qid = add_question($title, $content, $subject, $institution, $level_of_study);
        $response = array('status' => 'success', 'qid' => $qid);
    } else {
        $response = array('status' => 'error');
    }
    echo json_encode($response);
}
