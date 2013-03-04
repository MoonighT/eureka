(function() {
    if ($('.invite-sidebar').length === 0)
        return;
    function inviteFriendToJoin(id, inviteCallback) {
        FB.ui({
            title: 'Invite Friends to Join Eureka',
            method: 'apprequests',
            message: 'Join Eureka to crowd source solutions and solve challenging academic questions!',
            to: id,
            filters: ['app_non_users'],
        }, inviteCallback);
    }

    function getInviteItem(friend) {
        return '\
        <li class="invite-item">\
            <table width="100%">\
                <tr>\
                    <td width="55px">' + getFbImage(friend.uid, true) + '</td>\
                    <td>\
                        <div><a target="_blank" href="' + getFbLink(friend.uid) + '"><strong>' + friend.name + '</strong></a></div>\
                        <div align="right">\
                            <button uid="' + friend.uid + '" class="invite-btn btn btn-mini btn-primary">Invite</button>\
                        </div>\
                    </td>\
                </tr>\
            </table>\
        </li>';
    }
    $(document).on('fb-init', function(event) {
        $('.mass-invite-btn').click(function() {
            inviteFriendToJoin(null, function() {});
        });
        FB.api({
            method: 'fql.query',
            query: 'select uid, name from user where uid in (select uid2 from friend where uid1=me()) and is_app_user=0'
        }, function(friends) {
            var html = '';
            var start = Math.floor(Math.random() * (friends.length - 3));
            var current = 0;
            for (var current = 0; current < 3; current++) {
                html += getInviteItem(friends[start + current]);
            }
            var $inviteList = $('#invite-list').append(html);
            $('.invite-btn').live('click', function() {
                $this = $(this);
                $this.prop('disabled', true);
                inviteFriendToJoin($this.attr('uid'), function(response) {
                    if (response) {
                        $this.parents('.invite-item').remove();
                        current++;
                        var $item = $(getInviteItem(friends[(start + current) % friends.length]));
                        $item.hide();
                        $inviteList.append($item);
                        $item.slideDown('show');
                    } else {
                        $this.removeProp('disabled');
                    }
                });
            });
            $('#invite-block').slideDown(500);
        });
    });
})();
