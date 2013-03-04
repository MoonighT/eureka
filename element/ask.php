<?php
require_once __DIR__ . '/../util.php';
require_once get_file_path('api/facebook_api.php');
require_once get_file_path('api/question.php');
require_once get_file_path('api/db_query.php');

global $user;
$institution = get_institution($user['institution']);
?>
<div style="display: none" class="modal" id="ask-dialog" tabindex="-1" role="dialog" aria-labelledby="ask-header" aria-hidden="true">
     <style type="text/css">
        [name="subject"] {
            width: 150px;
        }
        [name="title"], [name="content"] {
            width: 100%;
        }
        #ask-form {
            margin-left: 0;
            padding-right: 10px;
            height: 220px;
        }
        .fb-feed {
            margin-left: 20px;
        }
        .fb-feed input[type="checkbox"] {
            margin-bottom: 9px;
        }
        .fb-feed img {
            margin-bottom: 7px;
        }
        [name="institution"] {
            margin-left: 10px;
        }
    </style>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="ask-header">Ask a Question</h3>
    </div>
    <div class="modal-body">
        <form id="ask-form" action="<?php echo get_file_url('ajax/ask.php') ?>" method="POST">
            <div>
                <input required="true" type="text" name="subject" placeholder="Subject">
                <span class="fb-feed">
                    <input type="checkbox" id="ask-feed" checked="1">
                    <img src="<?php echo get_file_url('img/fb.png'); ?>" width="25px" height="25px">
                </span>
            </div>
            <div>
                <input required="true" type="text" name="title" placeholder="Title">
            </div>
            <div>
                <textarea required="true" name="content" rows="5" 
                    placeholder="Please provide more information about your question">
                </textarea>
            </div>
            <div>
                <select name="level-of-study">
                    <option value="0"> - Level of Question - </option>
                    <?php
                    $levels = get_all_levels_of_study();
                    foreach ($levels as $level)
                    echo '<option value="' . $level['level_id'] . '">' . $level['name'] . '</option>';
                    ?>
                </select>
                <input name="institution" type="text" data-default="<?php echo $institution; ?>" placeholder="Institution">
            </div>

        </form>
    </div>
    <div class="modal-footer">
        <button id="cancel-ask-btn" class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button id="ask-btn" class="btn btn-primary">Ask</button>
    </div>
</div>


  
