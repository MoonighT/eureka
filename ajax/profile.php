<?php
require_once __DIR__ . '/../util.php';
require_once get_file_path('api/facebook_api.php');
require_once get_file_path('api/db_query.php');

function get_all_posts($posts = array()){
    $questions = array();
    foreach ($posts as $key => $value) {
        $post_info = get_post_info($value);
        
        //get all tags name
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
        
        $temp = array('qid' => $post_info['post_id'], 'title'=>$post_info['title'],'content'=>$post_info['content'],'bulbs'=>get_question_thumb_up_count($post_info['post_id']),'answer'=> get_answers_count_of_post($post_info['post_id']),
        'subject_id'=>$subject_id,'subject_name'=> $subject , 'institution_id'=>$institution_id, 'institution_name'=>$institution_name,'level_of_study_id'=>$level_of_study_id,'level_of_study_name'=>$level_of_study_name,
         'fuid'=>$post_info['user_id'], 'user'=>get_user_name($post_info['user_id']),'userLink'=>'profile.php/?fuid='.$post_info['user_id'] ,'score'=> 100, 'timestamp'=>$post_info['post_date']);

        $questions[]= $temp;
    }
    return $questions;
}

function get_all_questions($type, $page) {
    
    
    if($_GET['fuid']=='me'){
        $fb = get_fb_client();
        $fid = $fb->getUser();
    }else
        $fid =$_GET['fuid'];
        
    $posts_per_page = get_post_page_number();
    //asked questions

    
    $posts_asked = get_user_post_by_page($fid,($page-1)*$posts_per_page,$posts_per_page);

    $total_post_num = get_user_post_count($fid);

    $totalPages_asked = ceil ($total_post_num*1.0/$posts_per_page);

    //answered posts
    $answered_posts = get_user_answered_post_by_page($fid,($page-1)*$posts_per_page,$posts_per_page);
    
    $total_post_num2 = get_user_answered_post_count($fid);
    $totalPages_answered = ceil($total_post_num2*1.0/$posts_per_page);


    $totalPages = $type == 'asked' ? $totalPages_asked: $totalPages_answered;
    $questions = $type == 'asked' ? get_all_posts($posts_asked) : get_all_posts($answered_posts);
    
    return array('data' => $questions, 'totalPages' => $totalPages, 'currentPage' => $page);
}


$type = empty($_GET['type']) ? 'asked' : $_GET['type'] ;
echo json_encode(get_all_questions($type,get_current_page()));



