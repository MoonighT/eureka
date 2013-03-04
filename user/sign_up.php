<?php
require_once '../api/facebook_api.php';
require_once '../api/db_query.php';
check_login_status('sign_up');

function validate_sign_up_input($institution, $subjects) {
    $messages = array();
    if (!$institution)
        $messages[] = 'Please enter your current institution';
    if (count($subjects) == 0)
        $messages[] = 'Please enter at least one interested subject';
    return $messages;
}

$messages = array();
$me = get_me();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $institution = trim($_POST['institution']);
    $subjects = array();
    foreach (explode(',', $_POST['subjects']) as $index => $subject) {
        $subject = trim($subject);
        if ($subject)
            $subjects[] = $subject;
    }
    $subjects = array_unique($subjects);

    $messages = validate_sign_up_input($institution, $subjects);
    if (count($messages) == 0) {
        insert_user($me['id'], $me['name'], $institution);
        update_user_interests($me['id'], $subjects);
        $_SESSION['is_new_user'] = 'true';
        header('Location: ../index.php');
        exit();
    }
}

$high_school = $college = '';
foreach ($me['education'] as $education) {
    if ($education['type'] == 'College') {
        $college = $education['school']['name'];
    } else if ($education['type'] == 'High School') {
        $high_school = $education['school']['name'];
    }
}
$school = $college ? $college : $high_school;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8" />
        <title>Eureka</title>
        <?php require_once '../element/include_css.php' ?>
    </head>
    <body style="padding: 0">
        <div style="background:#000000; margin-bottom:20px">
            <div class="row">
                <div class="span4 offset2">
                    <img src="../img/latest_logo.png" width="300px" height="180px">
                </div>
                <div class="span5">
                    <h3 style="margin-top: 60px; color: #FFD700">
                        Join the fun social learning platform to crowd source solutions and solve challenging academic questions!
                    </h3>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="span6 offset3">
                    <legend>Please tell us more about you...</legend>
                    <?php
                    foreach ($messages as $message)
                        echo "<div class='alert alert-error'>$message</div>";
                    ?>
                   <form class="form-horizontal" method="POST">
                        <div class="control-group">
                            <label class="control-label">Name</label>
                            <label class="control-label"><b><?php echo $me['name']; ?></b></label>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><span class="required-sign">*</span>Current Institution</label>
                            <div class="controls">
                                <input name="institution" type="text" required="true" value="<?php echo $school ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><span class="required-sign">*</span>Interested Subjects</label>
                            <div class="controls">
                                <input type="text" name="subjects" required="true" >
                                <i class="icon-question-sign" title="Please separate subjects by comma"></i>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn btn-warning" type="submit"><b>I'm ready to start!</b></button>
                            </div>
                        </div>
                    </form>
				</div>
			</div>
        </div>
        <?php
        global $exclude_facebook_js;
        $exclude_facebook_js = true;
        require_once '../element/include_js.php';
        ?>
   </body>
</html>
