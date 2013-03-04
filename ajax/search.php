<?php
require_once __DIR__ . '/../util.php';
require_once get_file_path('api/facebook_api.php');
require_once get_file_path('api/db_query.php');

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

function get_search_result($search_keyword, $page){

 	$posts_per_page = get_post_page_number();
    $posts = search_by_page($search_keyword,($page-1)*$posts_per_page,$posts_per_page);
    
 	$totalPages = ceil(search_count($search_keyword)*1.0/$posts_per_page);

 	return array('data' =>get_all_posts($posts),'message'=>$search_keyword,'totalPages' => $totalPages, 'currentPage' => $page);
}

function get_search_subject($search_keyword, $page){
    $message = get_subject($search_keyword);
    $search_keyword = array($search_keyword);
    $posts_per_page = get_post_page_number();
    $posts = get_posts_belong_to_subjects_by_page($search_keyword,($page-1)*$posts_per_page,$posts_per_page);
    
    $totalPages = ceil(get_posts_belong_to_subjects_count($search_keyword)*1.0/$posts_per_page);

    return array('data' =>get_all_posts($posts),'message'=>$message,'totalPages' => $totalPages, 'currentPage' => $page);
}

function get_search_institution($search_keyword, $page){
    $message = get_institution($search_keyword);
    $search_keyword = array($search_keyword);
    $posts_per_page = get_post_page_number();
    $posts = get_posts_belong_to_institutions_by_page($search_keyword,($page-1)*$posts_per_page,$posts_per_page);
    
    $totalPages = ceil(get_posts_belong_to_institutions_count($search_keyword)*1.0/$posts_per_page);

    return array('data' =>get_all_posts($posts),'message'=>$message,'totalPages' => $totalPages, 'currentPage' => $page);
}

function get_search_level_of_study($search_keyword, $page){
    $message = get_level_of_study($search_keyword);
    $search_keyword = array($search_keyword);
    $posts_per_page = get_post_page_number();
    $posts = get_posts_belong_to_level_of_study_by_page($search_keyword,($page-1)*$posts_per_page,$posts_per_page);
    
    $totalPages = ceil(get_posts_belong_to_level_of_study_count($search_keyword)*1.0/$posts_per_page);
    return array('data' =>get_all_posts($posts),'message'=>$message,'totalPages' => $totalPages, 'currentPage' => $page);
}

if(isset($_GET['keyword'])){
    $search_keyword = $_GET['keyword'];
    echo json_encode(get_search_result($search_keyword,get_current_page() ));
}
else if(isset($_GET['subject_id'])){
    $search_keyword = $_GET['subject_id'];
    echo json_encode(get_search_subject($search_keyword,get_current_page() ));
}
else if(isset($_GET['institution_id'])){
    $search_keyword = $_GET['institution_id'];
    echo json_encode(get_search_institution($search_keyword,get_current_page() ));
}
else if(isset($_GET['level_of_study_id'])){
    $search_keyword = $_GET['level_of_study_id'];
    echo json_encode(get_search_level_of_study($search_keyword,get_current_page() ));
}

