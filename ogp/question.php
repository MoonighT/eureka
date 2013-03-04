<?php
require_once '../util.php';
require_once '../api/question.php';
$qid = $_GET['qid'];
if (!$qid)
    exit();
$question = get_question($qid);
?>
<!DOCTYPE html>
<html>
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# eurekax: http://ogp.me/ns/fb/eurekax#" >
        <meta charset="utf-8" />
        <meta property="fb:app_id" content="263953760391187" /> 
        <meta property="og:type" content="eurekax:question" />
        <meta property="og:url"    content="<?php echo get_file_url("ogp/question.php?qid=$qid"); ?>" /> 
        <meta property="og:title"  content="<?php echo $question['title']; ?>" />
        <meta property="og:description" content="<?php echo $question['desc']; ?>" />
        <meta property="og:image"  content="<?php echo $question['img']; ?>" />
    </head>
</html>
