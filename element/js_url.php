<?php
require_once __DIR__ . '/../util.php';
$urls = array(
    'activity' => 'ajax/activity.php',
    'challenge' => 'ajax/challenge.php',
    'search' => 'ajax/search.php',
    'question' => 'question.php',
    'logout' => 'user/logout.php',
    'friendRanking' => 'ajax/friend_ranking.php',
    'searchResult' => 'search.php',
    'autocomplete' => 'ajax/autocomplete.php',
    'profile' => 'user/profile.php',
    'ask' => 'ajax/ask.php',
    'prof_page'=> 'ajax/profile.php',
    'ranking'=> 'ajax/ranking.php',
    'thumbUp' => 'ajax/thumb_up.php',
    'acceptAnswer' => 'ajax/accept_answer.php',
    'bulbImg' => 'img/eureka_on_32.png',
    'editProfile' => 'ajax/edit_profile.php',
);
?>
<script type="text/javascript">
urlConfig = {
    <?php
    foreach ($urls as $name => $url)
        echo $name . ':  "' . get_file_url($url) . '",';
    ?>
};
</script>
