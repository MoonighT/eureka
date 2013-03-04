<?php
require_once __DIR__ . '/db_query.php';
require_once __DIR__ . '/facebook_api.php';

function get_user_details($fid) {
    $user = get_user($fid);
    $user['institution_name'] = get_institution($user['institution']);
    return $user;
}

function get_question($qid) {
    $question = get_post_info($qid, 1);
    $user = get_user_details($question['user_id']);
    $question['user'] = $user;
    $question['subject_name'] = get_subject($question['subject']);
    $question['institution_name'] = get_institution($question['institution']);
    $question['level_of_study_name'] = get_level_of_study($question['level_of_study']);
    return $question;
}

function add_question($title, $content, $subject, $institution, $level_of_study) {
    $fb = get_fb_client();
    $qid = insert_post($fb->getUser(), $title, $content, $subject, $institution, $level_of_study);
    // create_ask_action($qid);
    return $qid;
}

function get_answers($qid) {
    $answer_ids = get_all_answers_of_post($qid);
    $answers = array();
    foreach ($answer_ids as $id) {
        $answer = get_answer_info($id);
        $user = get_user_details($answer['user_id']);
        $answer['user'] = $user;
        $answers[] = $answer;
    }
    return $answers;
}

function add_answer($qid, $answer) {
    global $user;
    return insert_answer($user['fid'], $qid, $answer, time());
}
