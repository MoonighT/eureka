<?php
require_once '../api/db_query.php';
require_once '../api/facebook_api.php';

check_login_status();

$institution = trim($_POST['institution']);
$interests = trim($_POST['interests']);
if (!$institution || !$interests)
    return;

global $user;
$fid = $user['fid'];
$subjects = array();
foreach (explode(',', $interests) as $index => $subject) {
    $subject = trim($subject);
    if ($subject)
        $subjects[] = $subject;
}
$subjects = array_unique($subjects);
if (count($subjects) == 0)
    return;

update_user_institution($fid, $institution);
update_user_interests($fid, $subjects);
echo json_encode(array('status' => 'success'));
