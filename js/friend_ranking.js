(function() {
    function getDegree(credit) {
        if(credit>500)
            degree = 'PhD';
        else if(credit>200)
            degree = 'Master';
        else if(credit>100)
            degree = 'Undergraduate';
        else if(credit>50)
            degree = 'Junior college';
        else if(credit>25)
            degree = 'Secondary';
        else if(credit>10)
            degree = 'Primary';
        else
            degree = 'Kindergarten';

        return degree;
    }
    function getRankingItem(rank, friend) {
        var degree = '<div class="ranking-degree">' + getDegree(friend.credit) + '</div>';
        return '<li><div style="margin-bottom: 5px">' +
            '<span class="badge badge-info">' + rank + '</span> ' +
            '<a href="' + getProfileLink(friend.fid) + '">' + friend.name + '</a>' +
            '<span class="pull-right">' + friend.credit + getSmallBulbImage() + '</span>' + 
            degree + '</div></li>';
    }

    $(document).on('fb-init', function() {
        loadAjaxContent($('#friend-ranking-block'), urlConfig.friendRanking, function($block, data) {
            var $list = $('ul', $block);
            $.each(data, function(index, friend) {
                $list.append(getRankingItem(index + 1, friend));
            });
            $block.slideDown(500);
        });
    });
})();
