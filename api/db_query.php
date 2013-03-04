<?php
require_once __DIR__ . '/../util.php';

function secure($value)
{
    $value = htmlspecialchars(stripslashes($value));
    $value = str_ireplace("script", "blocked", $value);
    $value = mysql_escape_string($value);
    return $value;
}



function db_connect() {
    $con = mysql_connect("localhost","root","");
    if(!$con) {
        die('Could not connect:'.'mysql_error()');
    }
    mysql_select_db("eureka");
    return $con;
}

function db_close($con) {
    mysql_close($con);
}


//Add a new post
function insert_post($user_id, $title, $content, $subject, $institution = null, $level_of_study = null) {
    $title = secure($title);
    $content = secure($content);
    $subject = secure($subject);
    $post_date = time();
    $subject_id = insert_subject_if_not_exist($subject);
    if($institution)
        $institution_id = insert_institution_if_not_exist($institution);
    else
        $institution_id = null;

    $con = db_connect();
    $result = mysql_query("insert into Post (user_id, content, post_date, title, subject, institution, level_of_study) values ('$user_id', '$content', $post_date,'$title','$subject_id','$institution_id','$level_of_study') ") or die(mysql_error());
    $id = mysql_insert_id();
    db_close($con);

    return $id;
}

//Get Num of Answer of a post
function get_answers_count_of_post($post_id) {
    $con = db_connect();
    $result = mysql_query("select count(*) from Answer where post_id = $post_id");
    $num_rows = mysql_result($result,0);
    db_close($con);
    return $num_rows;
}

//Add a answer
function insert_answer($user_id, $post_id, $content, $answer_date) {
    $content = secure($content);
    $con = db_connect();
    $result = mysql_query("insert into Answer(answer_id, user_id, post_id, content, answer_date) values (NULL, '$user_id', $post_id,'$content', $answer_date)")
        or die(mysql_error());
    db_close($con);

    if($result){
        $con = db_connect();
        $credit = get_credit_by_type('answer');
        mysql_query("update User set credit=credit+$credit where fid = '$user_id'") or die(mysql_error());
        db_close($con);
    }
    return $result;
}

//accept an answer
function accept_answer($ans_id) {
    $con = db_connect();
    mysql_query("update eureka.Answer set accept = 1 where answer_id = $ans_id");
    db_close($con);

    $ans = get_answer_info($ans_id);
    $ans_user = $ans['user_id'];

    $con = db_connect();
    $credit = get_credit_by_type('accept');
    mysql_query("update User set credit=credit+$credit where fid = '$ans_user'") or die(mysql_error());
    db_close($con);
}

function cancel_accept_answer($ans_id) {
    $con = db_connect();
    mysql_query("update eureka.Answer set accept = 0 where answer_id = $ans_id");
    db_close($con);

    $ans = get_answer_info($ans_id);
    $ans_user = $ans['user_id'];

    $con = db_connect();
    $credit = get_credit_by_type('accept');
    mysql_query("update User set credit=credit-$credit where fid = '$ans_user'") or die(mysql_error());
    db_close($con);

}


//Answer information
function get_answer_info($ans_id) {
    $con = db_connect();
    $result = mysql_query("select * from Answer where answer_id = $ans_id");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    return $row;
}

//All Answer Ids belong to a post
function get_all_answers_of_post($post_id) {
    $con = db_connect();
    $rows =  array();
    $result = mysql_query("select answer_id from Answer where post_id = $post_id");
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row['answer_id'];
    }
    db_close($con);
    return $rows;
}


//Interested subject of a user
function get_user_interested_subjects($user_id) {
    $con = db_connect();
    $result = mysql_query("select * from Interest where user_id = $user_id");
    $rows = array();
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row['subject_id'];
    }
    db_close($con);
    return $rows;
}

function get_posts_belong_to_subjects($subjects) {
    $con = db_connect();
    $rows = array();
    $subjects = implode(',', $subjects);
    $result = mysql_query("select distinct p.post_id from Post p where p.subject in ($subjects)");
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row['post_id'];
    }

    db_close($con);
    return $rows;
}

function get_posts_belong_to_subjects_count($subjects) {
    $con = db_connect("eureka");
    if (!count($subjects))
        return 0;
    $subjects = implode(',', $subjects);
    $result = mysql_query("select count(distinct p.post_id) from Post p where p.subject in ($subjects)");
    $num = mysql_result($result, 0);
    db_close($con);
    return $num;
}

function get_posts_belong_to_subjects_by_page($subjects, $start, $limit) {
    $con = db_connect("eureka");
    $rows = array();
    if (!count($subjects))
        return array();
    $subjects = implode(',', $subjects);
    $result = mysql_query("select post_id from Post where subject in ($subjects) order by post_date desc limit $start, $limit ");
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row['post_id'];
    }

    db_close($con);
    return $rows;
}

function get_posts_belong_to_institutions_count($institutions) {
    $con = db_connect("eureka");
    if (!count($institutions))
        return 0;
    $institutions = implode(',', $institutions);
    $result = mysql_query("select count(distinct p.post_id) from Post p where p.institution in ($institutions)");
    $num = mysql_result($result, 0);
    db_close($con);
    return $num;
}

function get_posts_belong_to_institutions_by_page($institutions, $start, $limit) {
    $con = db_connect("eureka");
    $rows = array();
    if (!count($institutions))
        return array();
    $institutions = implode(',', $institutions);
    $result = mysql_query("select post_id from Post where institution in ($institutions) limit $start, $limit ");
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row['post_id'];
    }

    db_close($con);
    return $rows;
}

function get_posts_belong_to_level_of_study_count($level_of_studys) {
    $con = db_connect("eureka");
    if (!count($level_of_studys))
        return 0;
    $level_of_studys = implode(',', $level_of_studys);
    $result = mysql_query("select count(distinct p.post_id) from Post p where p.level_of_study in ($level_of_studys)");
    $num = mysql_result($result, 0);
    db_close($con);
    return $num;
}

function get_posts_belong_to_level_of_study_by_page($level_of_studys, $start, $limit) {
    $con = db_connect("eureka");
    $rows = array();
    if (!count($level_of_studys))
        return array();
    $level_of_studys = implode(',', $level_of_studys);
    $result = mysql_query("select post_id from Post where level_of_study in ($level_of_studys) limit $start, $limit ");
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row['post_id'];
    }

    db_close($con);
    return $rows;
}

//get all posts by time
function get_recent_posts() {
    $con = db_connect("eureka");
    $result = mysql_query("select * from Post order by post_date desc");
    $rows = array();
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row['post_id'];
    }
    db_close($con);
    return $rows;
}

function get_recent_posts_count() {
    $con = db_connect("eureka");
    $result = mysql_query("select count(*) from Post order by post_date desc");
    $num = mysql_result($result, 0);
    db_close($con);
    return $num;
}

function get_recent_posts_by_page($start, $limit) {
    $con = db_connect("eureka");
    $result = mysql_query("select * from Post order by post_date desc limit $start, $limit");
    $rows = array();
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row['post_id'];
    }
    db_close($con);
    return $rows;
}


function get_subject_id($post_id) {
    $con = db_connect();
    $result = mysql_query("select * from Post where post_id = $post_id");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    return $row['subject'];
}

function get_user_answered_post_by_page($user_id, $start, $limit) {
    $con = db_connect();
    $result = mysql_query("select distinct(post_id) from Answer where user_id = '$user_id' limit $start, $limit");
    $rows = array();
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row['post_id'];
    }
    db_close($con);
    return $rows;
}

function get_user_answered_post_count($user_id) {
    $con = db_connect();
    $result = mysql_query("select count(distinct(post_id)) from Answer where user_id = $user_id");
    $row = mysql_result($result,0);
    db_close($con);
    return $row;
}

//Post belong to a user
function get_user_post_by_page($user_id, $start, $limit) {
    $con = db_connect();
    $result = mysql_query("select * from Post where user_id = '$user_id' limit $start, $limit");
    $rows = array();
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row['post_id'];
    }
    db_close($con);
    return $rows;
}


function get_user_post_count($user_id) {
    $con = db_connect();
    $result = mysql_query("select count(*) from Post where user_id = $user_id");
    $row = mysql_result($result,0);
    db_close($con);
    return $row;
}


//Posts belong to a group of users
function get_friend_posts($user_ids = array(), $start, $limit) {
    $con = db_connect();
    $result = mysql_query("select * from Post where user_id in ($user_ids) order by post_date desc limit $start, $limit");
    $rows = array();
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row['post_id'];
    }
    db_close($con);
    return $rows;
}

function get_friend_posts_count($user_ids = array()) {
    $con = db_connect();
    $result = mysql_query("select count(*) from Post where user_id in ($user_ids)");
    $num = mysql_result($result, 0);
    db_close($con);
    return $num;
}

function get_friend_posts_and_answers($user_ids = array(), $start, $limit) {
    $con = db_connect();
    $result = mysql_query("(select post_id, user_id, answer_date as date, 2 as type from Answer where user_id in ($user_ids)) union 
                        (select post_id, user_id, post_date as date , 1 as type from Post where user_id in ($user_ids)  ) order by date desc limit $start, $limit");
    $rows = array();
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row;
    }
    db_close($con);
    return $rows;
}

function get_friend_posts_and_answers_count($user_ids = array()) {
    $con = db_connect();
    $result = mysql_query("select count(*) from Answer where user_id in ($user_ids) ");
    $num = mysql_result($result, 0);
    $result = mysql_query("select count(*) from Post where user_id in ($user_ids) ");
    $num += mysql_result($result, 0);
    db_close($con);
    return $num;
}

//Post information

function get_post_info($post_id, $full=0) {
    $con = db_connect();
    $result = mysql_query("select * from Post where post_id = $post_id");

    $row = mysql_fetch_assoc($result);
    if($full==0){
        if(strlen($row['title'])>40)
            $row['title'] = substr($row['title'], 0, 40).'...';
        if(strlen($row['content']) >200)
            $row['content'] = substr($row['content'],0,200).'...';
    }
    db_close($con);
    return $row;
}


//Tag table

function get_subject($subject_id) {
    $con = db_connect();
    $result = mysql_query("select * from Subject where subject_id = $subject_id");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    return $row['name'];
}

function get_institution($institution_id) {
    $con = db_connect();
    $result = mysql_query("select * from Institution where institution_id = $institution_id");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    return $row['name'];
}

function get_level_of_study($level_id) {
    $con = db_connect();
    $result = mysql_query("select * from Level_of_Study where level_id = $level_id");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    return $row['name'];
}

//User table
function get_user_name($user_id) {
    $con = db_connect();
    $result = mysql_query("select name from User where fid = '$user_id'");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    if (!$row)
        return 'Anonymous';
    return $row['name'];
}

function get_user($user_id) {
    $con = db_connect();
    $result = mysql_query("select * from User where fid = '$user_id'");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    if (!$row) {
        return array(
            'fid' => 0,
            'name' => 'Anonymous',
            'credit' => 0,
            'institution' => 0,
            'join_date' => 0,
        );
    }
    return $row;
}

//Add a new user
function insert_user($fid, $name, $institution) {
    $name = secure($name);
    $institution = secure($institution);
    $join_date = time();
    $institution = insert_institution_if_not_exist($institution);
    $con = db_connect();
    mysql_query("insert into User(fid, name,join_date, institution, credit) values ('$fid','$name','$join_date', '$institution', '5')");
    db_close($con);
}

function update_user_institution($fid, $institution) {
    $name = secure($name);
    $institution = secure($institution);
    $institution = insert_institution_if_not_exist($institution);
    $con = db_connect();
    mysql_query("update User set institution='$institution' where fid='$fid'");
    db_close($con);
}

function delete_user($fid) {
    $con = db_connect();
    mysql_query("delete from user where fid = '$fid'");
    mysql_query("delete from interest where user_id = '$fid'");
    mysql_query("update post set user_id = '0' where user_id = '$fid'");
    mysql_query("update answer set user_id = '0' where user_id = '$fid'");
    db_close($con); 
}

function update_user_interests($fid, $subjects) {
    $fid = secure($fid);
    $subject_ids = array();
    foreach ($subjects as $subject){
        $subject = secure($subject);
        $subject_ids[] = insert_subject_if_not_exist($subject);
    }
    $con = db_connect();
    mysql_query("delete from interest where user_id='$fid'");
    foreach ($subject_ids as $subject_id)
        mysql_query("insert into Interest(subject_id, user_id) values ('$subject_id', '$fid')");
    db_close($con);

}

function get_ranking($limit = 'limit 0,5', $where = '') {
    $con = db_connect();
    $result = mysql_query("select fid,name,credit from User $where order by credit desc,join_date asc $limit");
    $rows = array();
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row;
    }
    db_close($con);
    return $rows;
}

function get_global_ranking_count() {
    $con = db_connect();
    $result = mysql_query("select count(fid) from User");
    $row = mysql_result($result,0);
    db_close($con);
    return $row;
}

function insert_if_not_exist($select, $insert, $id_field) {
    $con = db_connect();
    $result = mysql_query($select);
    $row = mysql_fetch_assoc($result);
    if ($row) {
        db_close($con);
        return $row[$id_field];
    }
    $result = mysql_query($insert) or die(mysql_error());
    $id = mysql_insert_id();
    db_close($con);
    return $id;
}

function insert_subject_if_not_exist($subject) {
        $subject = secure($subject);
    return insert_if_not_exist(
        "select subject_id from Subject where LOWER(name)=LOWER('$subject')",
        "insert into Subject(name) values('$subject')",
        'subject_id');
}

function insert_institution_if_not_exist($institution) {
        $institution = secure($institution);    
    return insert_if_not_exist(
        "select institution_id from Institution where LOWER(name) = LOWER('$institution')",
        "insert into Institution(name) values('$institution')",
        'institution_id');
}

function thumb_up_question($post_id, $user_id) {
    $con = db_connect();
    $result = mysql_query("insert into Post_thumb_up(post_id, user_id) values ('$post_id','$user_id')");
    db_close($con);
    if($result){
        $post_user = get_post_info($post_id);
        $post_user = $post_user['user_id'];
        $con = db_connect();
        $credit = get_credit_by_type('thumb_up');
        mysql_query("update User set credit=credit+$credit where fid = '$post_user'");
        db_close($con);
    }
    return $result;
}

function cancel_thumb_up_question($post_id, $user_id) {
    $con = db_connect();
    $result = mysql_query("delete from Post_thumb_up where post_id = '$post_id' and user_id = '$user_id' ");
    db_close($con);
    if($result){
        $post_user = get_post_info($post_id);
        $post_user = $post_user['user_id'];
        $con = db_connect();
        $credit = get_credit_by_type('thumb_up');
        mysql_query("update User set credit=credit-$credit where fid = '$post_user'");
        db_close($con);
    }
    return $result;
}

function is_question_thumbed_up($post_id, $user_id) {
    $con = db_connect();
    $result = mysql_query("select post_id from Post_thumb_up where post_id = '$post_id' and user_id = '$user_id'");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    return $row;
}

function get_question_thumb_up_count($post_id) {
    $con = db_connect();
    $result = mysql_query("select count(user_id) as count from Post_thumb_up where post_id = '$post_id'");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    return $row['count'];
}


function thumb_up_answer($answer_id, $user_id) {
    $con = db_connect();
    $result = mysql_query("insert into Answer_thumb_up(answer_id, user_id) values ('$answer_id','$user_id')");
    db_close($con);
    if($result){
        $ans_user = get_answer_info($answer_id);
        $ans_user = $ans_user['user_id'];
        $con = db_connect();
        $credit = get_credit_by_type('thumb_up');
        mysql_query("update User set credit=credit+$credit where fid = '$ans_user'");
        db_close($con);
    }
    return $result;

}

function cancel_thumb_up_answer($answer_id, $user_id) {
    $con = db_connect();
    $result = mysql_query("delete from Answer_thumb_up where answer_id = '$answer_id' and user_id = '$user_id' ");
    db_close($con);
    if($result){
        $ans_user = get_answer_info($answer_id);
        $ans_user = $ans_user['user_id'];
        $con = db_connect();
        $credit = get_credit_by_type('thumb_up');
        mysql_query("update User set credit=credit-$credit where fid = '$ans_user'");
        db_close($con);
    }
    return $result;
}

function is_answer_thumbed_up($answer_id, $user_id) {
    $con = db_connect();
    $result = mysql_query("select answer_id from answer_thumb_up where answer_id = '$answer_id' and user_id = '$user_id'");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    return $row;
}

function get_answer_thumb_up_count($answer_id) {
    $con = db_connect();
    $result = mysql_query("select count(user_id) as count from Answer_thumb_up where answer_id = '$answer_id'");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    return $row['count'];
}

function search_by_page($keyword, $start, $limit) {
    $keyword = trim($keyword);
    $keyword = secure($keyword);
    $keyword = strtolower($keyword);
    $con = db_connect();
    $result = mysql_query("
        SELECT p.post_id, p.post_date,
        SUM(((LENGTH(p.content) - LENGTH(REPLACE(LOWER(p.content), '$keyword', '')))/LENGTH('$keyword'))+
        ((LENGTH(p.title) - LENGTH(REPLACE(LOWER(p.title), '$keyword', '')))/LENGTH('$keyword')) + 
        ((LENGTH(s.name) - LENGTH(REPLACE(LOWER(s.name), '$keyword', '')))/LENGTH('$keyword')) )
        AS Occurrences
        FROM Post AS p, Subject As s
        WHERE (LOWER(p.content) LIKE '%$keyword%' or LOWER(p.title) LIKE '%$keyword%' or LOWER(s.name) LIKE '%$keyword%') AND p.subject = s.subject_id
        GROUP BY p.post_id
        ORDER BY Occurrences DESC,p.post_date DESC limit $start, $limit ");
    if(!$result) return array();
    $rows = array();
    while ($row = mysql_fetch_assoc($result)) {
        $rows[] = $row['post_id'];
    }

    db_close($con);
    return $rows;
}

function search_count($keyword) {
    $keyword = trim($keyword);
    $keyword = secure($keyword);
    $con = db_connect();
    $result = mysql_query("
        SELECT p.post_id,
        SUM(((LENGTH(p.content) - LENGTH(REPLACE(p.content, '$keyword', '')))/LENGTH('$keyword'))+
        ((LENGTH(p.title) - LENGTH(REPLACE(p.title, '$keyword', '')))/LENGTH('$keyword'))+
        ((LENGTH(s.name) - LENGTH(REPLACE(s.name, '$keyword', '')))/LENGTH('$keyword')) )
        AS Occurrences
        FROM Post AS p, Subject As s
        WHERE (p.content LIKE '%$keyword%' or p.title LIKE '%$keyword%' or s.name LIKE '%$keyword%') AND p.subject = s.subject_id
        GROUP BY p.post_id
        ORDER BY Occurrences DESC ");
    if(!$result) return 0;
    $rows = mysql_num_rows($result);
    db_close($con);
    return $rows;
}

function get_all_levels_of_study() {
    $con = db_connect();
    $result = mysql_query("select * from Level_of_Study");
    $rows = array();
    while ($row = mysql_fetch_assoc($result))
        $rows[] = $row;
    db_close($con);
    return $rows;
}

function search_category($table, $keyword) {
    $keyword = trim($keyword);
    $keyword = secure($keyword);
    if (!$keyword)
        return array();
    $con = db_connect();
    $result = mysql_query("select name from $table where LOWER(name) LIKE LOWER('%$keyword%')");
    $names = array();
    while ($row = mysql_fetch_assoc($result))
        $names[] = $row['name'];
    db_close($con);
    return $names;
}

function search_institution($keyword) {
    return search_category('Institution', $keyword);
}

function search_subject($keyword) {
    return search_category('Subject', $keyword);
}

function search_level_of_study($keyword) {
    return search_category('Level_of_Study', $keyword);
}

function get_user_rank($user_id) {
    $user_info = get_user($user_id);
    $credit = $user_info['credit'];
    $con = db_connect();
    $result = mysql_query("select count(fid)+1 as rank from User where credit>'$credit' ");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    return $row['rank'];
}

function get_user_friend_rank($user_id, $items){
    $user_info = get_user($user_id);
    $credit = $user_info['credit'];
    $con = db_connect();
    $result = mysql_query("select count(fid)+1 as rank from User where credit>'$credit' and fid in ($items) ");
    $row = mysql_fetch_assoc($result);
    db_close($con);
    return $row['rank'];
}

function get_best_at($user_id, $limit) {
    $con = db_connect();
    $result = mysql_query("select count(ans.answer_id) as rank,sub.name from Answer as ans, Post as pos, Subject as sub
        where ans.post_id = pos.post_id and ans.user_id = '$user_id' and pos.subject = sub.subject_id
        group by sub.name order by rank desc limit 0, $limit");
    $rows = array();
    while ($row = mysql_fetch_assoc($result))
        $rows[] = $row['name'];
    db_close($con);
    return $rows;

}

function get_degree($credit) {
    if($credit>500)
        $degree = 'PhD';
    else if($credit>200)
        $degree = 'Master';
    else if($credit>100)
        $degree = 'Undergraduate';
    else if($credit>50)
        $degree = 'Junior college';
    else if($credit>25)
        $degree = 'Secondary';
    else if($credit>10)
        $degree = 'Primary';
    else
        $degree = 'Kindergarten';

    return $degree;
}

?>
