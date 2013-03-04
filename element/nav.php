<?php 
require_once __DIR__ . '/../util.php';
require_once get_file_path('api/facebook_api.php');
global $user;
?>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <div class="row">
                <div class="span2 offset1">
                    <a class="brand" style="padding:0px" href="<?php echo get_file_url('index.php'); ?>">
                        <img src="<?php echo get_file_url('img/nav_bar_logo.png'); ?>" 
                            width="170px" height="44px" style="margin-left:20px; margin-top:0px; margin-bottom:0px">
                    </a>
                </div>
                <div class="span6">
                    <div class="nav-collapse">
                        <form action="<?php echo get_file_url('search.php'); ?>" method="get">
                            <input style="width: 300px" required=true name="keyword" type="text" class="search-query" placeholder="Search questions">
                            <button type="submit" class="btn"><i class="icon-search"></i></button>
                            <a class="btn" href="#ask-dialog" data-toggle="modal" role="button">Ask Question</a>
                        </form>
                    </div>
                </div>
                <div class="span2">
                    <div class="btn-group pull-right dropdown">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            <img height="23" width="23" src="http://graph.facebook.com/<?php echo $user['fid']; ?>/picture">
                            <span id="username"><?php echo $user['name']; ?></span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href=<?php echo get_file_url("user/me.php?fuid=me") ?> >Profile</a></li>
                            <li class="divider"></li>
                            <li><a href="#" id="logout-link">Log Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once get_file_path('element/ask.php');
?>
