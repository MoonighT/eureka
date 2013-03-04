<?php
require_once 'api/question.php';
require_once 'api/facebook_api.php';
check_login_status();

$qid = $_GET['qid'];
$question = get_question($qid);
if (!$question)
    return;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answer = trim($_POST['answer']);
    if ($answer) {
        add_answer($qid, $answer);
        $_SESSION['show_answer_msg'] = 'true';
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$answers = get_answers($qid);
$poster = $question['user'];
$fid = $poster['fid'];

function get_fb_link_element($fid) {
    if (!$fid)
        $fid = 1;
    $picture_url = "http://graph.facebook.com/$fid/picture";
    $picture = '<img src="' . $picture_url . '" class="fb-picture" style="float:left; margin-right:10px; margin-top:0px">';
    if ($fid == 1)
        return $picture;

    $fb_url = get_file_url("user/profile.php?fuid=$fid");
    $fb_link = '<a href="' . $fb_url . '">' . $picture . '</a>';
    return $fb_link;
}

function get_category_link_element($question, $category_type) {
    $category_id = $question[$category_type];
    $category_name = $question[$category_type . '_name'];
    if (!$category_id)
        return '';
    return '<a class="btn btn-small" href="' . get_file_url('search.php?' . $category_type . '_id=' . $category_id) . 
        '">' . $category_name . '</a>';
}

function get_thumb_up_element($type, $id) {
    global $user;
    if ($type == 'question') {
        $is_thumbed_up = is_question_thumbed_up($id, $user['fid']);
    } else {
        $is_thumbed_up = is_answer_thumbed_up($id, $user['fid']);
    }
    if ($is_thumbed_up) {
        $action = 'cancel-thumb-up';
        $img = get_big_bulb_on_element();
    } else {
        $action = 'thumb-up';
        $img = get_big_bulb_off_element();
    }
    return '<a href="#" class="thumb-up" data-action="' . $action . '" data-type="' . $type . '" data-id="' . $id . '">' . $img . '</a>';
}

function get_accept_answer_element($answer) {
    global $question;
    global $user;
    $status = '';
    $title = '';
    $is_poster_viewing = ($question['user_id'] == $user['fid']);
    if ($answer['accept']) {
        $status = 'accepted';
        $action = 'cancel-accept';
        if (!$is_poster_viewing)
            $title = 'Accepted answer';
    } else if ($is_poster_viewing) {
        $status = 'pending';
        $action = 'accept';
    }
    if ($status) {
        $element = '<img class="accept-status" src="' . get_file_url('img/answer_' . $status . '_200.png') . '">';

        if ($is_poster_viewing)
            $element = '<a href="#" class="accept-answer" data-id="' . $answer['answer_id'] . '" data-action="' . $action . '">' . $element . '</a>';

        return '<td width="50px" title="' . $title . '">' . $element . '</td>';
    }
    return '';
}

function get_user_name_element($id, $name) {
    if (!$id)
        return $name;
    return '<a class="username" href="' . get_file_url('user/profile.php?fuid=') . $id . '">' . $name . '</a>';
}

?>
<!DOCTYPE html>
<html>
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# eurekax: http://ogp.me/ns/fb/eurekax#" >
        <meta charset="utf-8">
        <meta property="fb:app_id" content="263953760391187" /> 
        <meta property="og:type" content="eurekax:question" />
        <meta property="og:url"    content="<?php echo get_file_url("question.php?qid=$qid"); ?>" /> 
        <meta property="og:title"  content="<?php echo $question['title']; ?>" />
        <meta property="og:description" content="<?php echo $question['content']; ?>" />
        <meta property="og:image"  content="<?php echo get_file_url('img/eureka_on_128.png'); ?>" />
        <title><?php echo $question['title']; ?> - Eureka</title>
        <?php require_once 'element/include_css.php'; ?>
    </head>
    <body>
        <?php require_once 'element/nav.php'; ?>
        <div class="container">
            <div class="row">
                <div class="span10 offset1">
                    <?php
                    if ($_SESSION['show_answer_msg'] === 'true') {
                    ?>
                    <div class="alert alert-success">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <strong>Eureka!</strong><br>
                        We've awarded you 1 Bulb for your answer! Test your intellect by trying out other challenging questions!
                    </div>
                    <?php
                        unset($_SESSION['show_answer_msg']);
                    }
                    ?>
                    <div>
                        <div class="row">
                            <div class="span7">
                                <?php echo get_fb_link_element($poster['fid']); ?>
                                <?php echo get_user_name_element($poster['fid'], $poster['name']); ?>
                                <p style="font-size:12px; font-style:italic; margin-bottom:0px"><?php echo $poster['institution_name']; ?></p>
                                <p style="margin-top:0px">
                                    <?php 
                                    echo $poster['credit'];
                                    echo get_small_bulb_element();
                                    ?>
                                    <a href="#"><?php echo get_degree($poster['credit']); ?></a>
                                </p>
                            </div>
                            <div class="span3">
                                <p align="right">Posted on:</p>
                                <p align="right" style="font-size:14px"><strong><?php echo date('d M Y h:m a', $question['post_date']); ?></strong></p>
                            </div>
                        </div>
					</div>
					<div class="well question-container">
                        <div>
                            <p class="thumb-up-multiplier"><?php echo get_question_thumb_up_count($qid); ?></p>
                            <?php echo get_thumb_up_element('question', $question['post_id']); ?>
                            <h2 id="question-title" style="margin-bottom:10px"><?php echo $question['title']; ?></h2>
                        </div>
                        <div>
                            <?php
                            echo get_category_link_element($question, 'subject');
                            echo get_category_link_element($question, 'institution');
                            echo get_category_link_element($question, 'level_of_study');
                            ?>
                        </div>
                        <div style="margin-top: 10px">
                            <p id="question-content"><?php echo $question['content']; ?></p>
                        </div>
					</div>
                    <div>
                        <form id="quick-answer-form" method="POST">
                            <textarea name="answer" required="true" rows="1" cols="500" placeholder="I have an answer!"></textarea>
                            <div align="right">
                                <input name="submit" type="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </form>
					</div>
					<div>
                        <ul class="unstyled">
                            <?php
                            foreach ($answers as $answer) {
                            ?>
                            <li>
                                <div class="well answer-container">
                                    <div><p> <?php echo $answer['content']; ?> </p></div>
                                    <hr/>  
                                    <div>
                                        <table width="100%">
                                            <tr>
                                                <?php echo get_accept_answer_element($answer); ?>
                                                <td width="32px"><?php echo get_thumb_up_element('answer', $answer['answer_id']); ?></td>
                                                <td width="10px">
                                                    <p class="thumb-up-multiplier"> 
                                                        <?php echo get_answer_thumb_up_count($answer['answer_id']); ?>
                                                    </p>
                                                </td>
                                                <td>
                                                    <div align="right">
                                                        <?php echo get_user_name_element($answer['user']['fid'], $answer['user']['name']); ?>
                                                    </div>
                                                    <p style="font-size:12px; font-style:italic; margin-bottom:0px" align="right"></p>
                                                    <p style="margin-top:0px" align="right">
                                                        <?php 
                                                        echo $answer['user']['credit'];
                                                        echo get_small_bulb_element();
                                                        ?>
                                                        <a href="#"><?php echo get_degree($answer['user']['credit']); ?></a>
                                                    </p>
                                                </td>
                                                <td width="50px"><?php echo get_fb_link_element($answer['user_id']); ?></td>
                                            </tr>
                                        </table>
                                    </div> 
                                </div>
                            </li>
                            <?php
                            }
                            ?>	
						</ul>
					</div>

                </div>
            </div>
            <?php require_once 'element/footer.php'; ?>
        </div>
        <?php 
        global $js_includes;
        $js_includes[] = 'js/question.js';
        require_once 'element/include_js.php'; 
        ?>
    </body>
</html>

