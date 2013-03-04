(function() {
    function updateActivities($tab, data) {
        var html = '';
        $.each(data.data, function(index, value) {
            value.fuid = parseInt(value.fuid);
            var action;
            switch (value.type) {
                case '1':
                    action = 'has asked a question';
                    break;
                case '2':
                    action = 'has answered a question';
                    break;
            }
            var friendImg = getFbImage(value.fuid);
            var friendLink = value.name;
            if (value.fuid != 0)
                friendLink = '<a href="' + getProfileLink(value.fuid) + '">' + friendLink + '</a>';

            var question_link = 'question.php?qid=';
            html += '\
            <div class="row activity">\
                <div class="span1" align="center" style="height:100%">' + friendImg + '</div>\
                <div class="span4">\
                    <h3>' + friendLink + ' ' + action + '</h3>\
                    <h3><a href="' + question_link+value.qid + '">' + value.title + '</a></h3>\
                </div>\
                <div class="span2">\
                    '+ formateTimestamp(value.time)+'\
                </div>\
            </div><hr></hr>';
        });
        $tab.html(html);
        $tab.ajaxPager(data.totalPages, data.currentPage, urlConfig.activity, updateActivities);
    }

    function updateChallenges($tab, data, url) {
        if (!data) {
            loadTab($('.nav li.active a', $tab));
            return;
        }
        var html = '';
        $.each(data.data, function(index, question) {
            html += getQuestionElement(question);
        });
        $tab.html(html);
        $tab.ajaxPager(data.totalPages, data.currentPage, url, updateChallenges);
    }

    function updateTags($tab, data, url) {
        var $table = $('<table class="tag-table">');
        var tags = data.data;
        for (var row = 0; row <= tags.length / 4; row ++) {
            var $tr = $('<tr>');
            for (var col = 0; col < 4; col ++) {
                var index = row * 4 + col;
                if (!tags[index]) {
                    $tr.append('<td></td>');
                    continue;
                }
                $tr.append('<td>' + getTagElement(tags[index]) + '<span class="item-multiplier"> X' + tags[index].count + '</span></td>');
            }
            $table.append($tr);
        }
        $tab.html($table).ajaxPager(data.totalPages, data.currentPage, url, updateTags);
    }
    
    function loadTab($a) {
        var $tab = $($a.attr('href'));
        var key = $tab.attr('id');
        var config = tabConfig[key];
        if (config.container)
            $tab = $(config.container, $tab);
        if (config.url) {
            loadAjaxContent($tab, config.url, config.update);
        } else {
            config.update($tab);
        }
    }

    function initTabs() {
        var $ajaxTabs = $('ul.ajax-tabs');
        $ajaxTabs.children().each(function() {
            $('a', this).click(function() {
                loadTab($(this));
            });
        });
        loadTab($('li.active a', $ajaxTabs.filter('.nav-tabs')));
    }

    var tagSearchToken = null;
    function initTagSearch() {
         $('#tag-search').on('keyup', function(event) {
            clearTimeout(tagSearchToken);
            var $this = $(this);
            tagSearchToken = setTimeout(function() {
                loadAjaxContent($('#tag-container'), urlConfig.tag + '?search=' + $this.val(), updateTags);
            }, 500);
        });
   }

    var tabConfig = {
        activity: {url: urlConfig.activity, update: updateActivities},
        challenge: {update: updateChallenges},
        interesting: {url: urlConfig.challenge + '?type=interesting', update: updateChallenges},
        recent: {url: urlConfig.challenge + '?type=recent', update: updateChallenges},
        tag: {url: urlConfig.tag, update: updateTags, container: '#tag-container'},
    };

    $(function() {
        initTabs();
        initTagSearch();
        
    });
})();
