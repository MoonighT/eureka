<?php
require_once '../util.php';
require_once '../api/db_query.php';
require_once '../api/facebook_api.php';

function get_all_posts($posts = array()){
    $questions = array();
    foreach ($posts as $key => $value) {
        $post_info = get_post_info($value);
        
        $subject_id = (get_subject_id($post_info['post_id']));
        $subject = get_subject($subject_id);

        $institution_id = $post_info['institution'];
        if($institution_id==0)
            $institution_name = "";
        else
            $institution_name = get_institution($institution_id);

        $level_of_study_id = $post_info['level_of_study'];
        if($level_of_study_id==0)
            $level_of_study_name = "";
        else
            $level_of_study_name = get_level_of_study($level_of_study_id);
        $user_array = get_user($post_info['user_id']);
        $credit = $user_array['credit'];

        $temp = array('qid' => $post_info['post_id'], 'title'=>$post_info['title'],'content'=>$post_info['content'],'bulbs'=>get_question_thumb_up_count($post_info['post_id']),'answer'=> get_answers_count_of_post($post_info['post_id']),
        'subject_id'=>$subject_id,'subject_name'=> $subject , 'institution_id'=>$institution_id, 'institution_name'=>$institution_name,'level_of_study_id'=>$level_of_study_id,'level_of_study_name'=>$level_of_study_name,
         'fuid'=>$post_info['user_id'], 'user'=>get_user_name($post_info['user_id']),'score'=> $credit, 'timestamp'=>$post_info['post_date']);

        $questions[]= $temp;
    }
    return $questions;
}


function get_all_questions($type, $page) {
    $fb = get_fb_client();
    $fid = $fb->getUser();
    $posts_per_page = get_post_page_number();
    //interesting posts
    $UserSubjects = get_user_interested_subjects($fid);

    $posts = get_posts_belong_to_subjects_by_page($UserSubjects,($page-1)*$posts_per_page,$posts_per_page);

    $total_post_num = get_posts_belong_to_subjects_count($UserSubjects);
    $totalPages_interesting = ceil($total_post_num*1.0/$posts_per_page);

    //recent posts
    $recent_posts = get_recent_posts_by_page(($page-1)*$posts_per_page,$posts_per_page);
    
    $total_post_num2 = get_recent_posts_count();
    $totalPages_recent = ceil($total_post_num2*1.0/$posts_per_page);


    $totalPages = $type == 'interesting' ? $totalPages_interesting: $totalPages_recent;
    $questions = $type == 'interesting' ? get_all_posts($posts) : get_all_posts($recent_posts);
    
    return array('data' => $questions, 'totalPages' => $totalPages, 'currentPage' => $page);
}
$type = empty($_GET['type']) ? 'interesting' : $_GET['type'];

echo json_encode(get_all_questions($type, get_current_page()));
