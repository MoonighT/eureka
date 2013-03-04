<?php
require_once __DIR__ . '/../util.php';
require_once get_file_path('sdk/facebook.php');
require_once get_file_path('api/db_query.php');
session_start();

function get_fb_client() {
    static $fb;
    if (!$fb) {
        $fb = new Facebook(array(
            'appId' => '263953760391187', 
            'secret' => '6f30f5917aac5ca99bd366ac4531d167'
        ));
    }
    return $fb;
}

function check_login_status($current_page = '') {
    $fb = get_fb_client();
    if ($fb->getUser()) {
        // user has logged into facebook
        global $user;
        $user = get_user($fb->getUser());
        if ($user && $user['fid'] == 0)
            $user = false;

        if ($current_page == 'login') {
            header('Location: ' . get_file_url($user ? 'index.php' : 'user/sign_up.php'));
            exit;
        } else if ($current_page == 'sign_up') {
            if ($user) {
                header('Location: ' . get_file_url('index.php'));
                exit;
            }
        } else if (!$user) {
            header('Location: ' . get_file_url('user/sign_up.php'));
            exit;
        }
    } else if($current_page != 'login') {
        header('Location: ' . get_root_url() . 'user/login.php');
    }
}

function get_me() {
    $fb = get_fb_client();
    return  $fb->api('/me', 'GET');
}

/*
function get_friends() {
    $fb = get_fb_client();
    $response = $fb->api('/me/friends', 'GET');
    if (!$response)
        return array();   
    return $response['data'];
}
 */

function get_friend_ids_in_apps() {
    if (isset($_SESSION['friends']))
        return $_SESSION['friends'];
    
    $fb = get_fb_client();
    $response = $fb->api(array(
        'method' => 'fql.query',
        'query' => 'select uid from user where uid in (select uid2 from friend where uid1=me()) and is_app_user=1',
    ));

    if (!$response)
        return array();
    $ids = array();
    foreach ($response as $friend)
        $ids[] = $friend['uid'];

    $_SESSION['friends'] = $ids;
    return $ids;
}

function get_fb_link($id) {
    return "http://www.facebook.com/$id";
}

function create_ask_action($qid) {
    $fb = get_fb_client();
    try {
        $fb->api('/me/eurekax:ask', 'POST', array('question' => get_file_url("question.php?qid=$qid")));
    } catch(Exception $ex) {
    }
}

function create_answer_action($qid) {
    $fb = get_fb_client();
    $fb->api('/me/eurekax:answer', 'POST', array('question' => get_file_url("question.php?qid=$qid")));
}

