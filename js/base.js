(function() {
    function getPagerItem(content, url, page, classes) {
        classes = classes || '';
        if (url.indexOf('?') === -1) {
            url += '?page=' + page;
        } else if (url.indexOf('page=') === -1) {
            url += '&page=' + page;
        } else {
            url = url.replace(/page=\d+/, 'page=' + page);
        }
        return '<li class="' + classes + '"><a href="' + url + '">' + content + '</a></li>';
    }

    function getPager(total, current, url) {
        if (!total)
            return;
        current = parseInt(current);
        var items = '';
        items += getPagerItem('Prev', url, current - 1, current > 1 ? undefined : 'disabled');
        var min = current - 2;
        var max = current + 2;
        if (min <= 0) {
            max = max + 1 - min;
            min = 1;
        }
        if (max > total) {
            min = Math.max(1, min - max + total);
            max = total;
        }
        if (min !== 1)
            items += getPagerItem(1, url, 1);
        if (min > 2)
            items += getPagerItem('...', url, 0, 'disabled');
        for (var i = min; i <= max; i++)
            items += getPagerItem(i, url, i, i === current ? 'active' : '');
        if (max < total - 1)
            items += getPagerItem('...', url, 0, 'disabled');
        if (max !== total)
            items += getPagerItem(total, url, total);
        items += getPagerItem('Next', url, current + 1, current < total ? undefined : 'disabled');
        return '<div class="pagination"><ul>' + items + '</ul></div>';
    }

    $.fn.extend({
        ajaxPager: function(total, current, url, update) {
            var $container = $(this);
            var $pager = $(getPager(total, current, url));
            $container.append($pager);
            $('a', $pager).click(function(event) {
                var $parent = $(this).parent();
                if (!$parent.hasClass('active') && !$parent.hasClass('disabled'))
                    loadAjaxContent($container, $(this).attr('href'), update);
                event.preventDefault();
            });
        }
    });
    window.getSmallBulbImage = function(){
        return '<img class="bulb-small" src="' + urlConfig.bulbImg + '">';
    };

    window.loadAjaxContent = function($container, url, update) {
        $.get(url, function(data) {
            update($container, data, url);
            scroll(0,0);            
        }, 'json');
    }

    window.getFbImage = function(fuid, linkToFb) {
        if (!fuid) {
            return '<img src="http://graph.facebook.com/1/picture">';
        }
        var link = linkToFb ? getFbLink(fuid) : getProfileLink(fuid);
        var target = linkToFb ? ' target="_blank"' : '';
        return '<a href="' + link + '"' + target + '><img src="http://graph.facebook.com/' +
            fuid + '/picture" class="fb-picture"></img></a>'; 
    }

    window.getFbLink = function(fuid) {
        if (!fuid)
            return '';
        return 'http://www.facebook.com/' + fuid;
    }

    window.getProfileLink = function(fuid) {
        if (!fuid)
            return '';
        return urlConfig.profile + '?fuid=' + fuid;
    }

    window.formateTimestamp = function(timestamp) {
        var time = new Date(timestamp * 1000);
        return '<b>' + time.toDateString().substr(4) + '</b> at <b>' + time.getHours() + ":" + time.getMinutes() + "</b>";
    }

    window.getCategoryElement = function(categoryType, categoryId, categoryName) {
        return '<a class="btn btn-small" href="' + urlConfig.searchResult + '?' + categoryType + '=' + categoryId +'">' +
            categoryName + '</a>';
    }

    window.getQuestionElement = function(question) {
        question.fuid = parseInt(question.fuid);

        if (!question.user) {
            question.user = 'Anonymous';
            question.fuid = 0;
        }
        var subject = getCategoryElement('subject_id', question.subject_id, question.subject_name);
        var institution = ''
        if (question.institution_id != 0)
            institution = getCategoryElement('institution_id', question.institution_id, question.institution_name);
        var level_of_study = '';
        if (question.level_of_study_id != 0)
            level_of_study = getCategoryElement('level_of_study_id', question.level_of_study_id, question.level_of_study_name);

        var timestamp = formateTimestamp(question.timestamp);
        var userImg = getFbImage(question.fuid);
        question.url = urlConfig['question'] + '?qid=' + question.qid;
        var userLink = question.user;
        if (question.fuid)
            userLink = '<a href="' + getProfileLink(question.fuid) + '">' +userLink + '</a>';
        return '\
        <div class="question-item">\
            <table width="100%">\
                <tr>\
                    <td width="70px" align="center">\
                        <div class="like-multiplier"><strong>' + question.bulbs + '</strong><div>Bulbs</div></div>\
                        <div class="answer-multiplier"><strong>' + question.answer + '</strong><div>Answers</div></div>\
                    </td>\
                    <td>\
                        <table height="100px">\
                            <tr><td><div><a href="' + question.url + '"><h3>' + question.title + '</h3></a></div></td></tr>\
                            <tr><td height="100%"><div>' + question.content + '</div></td></tr>\
                            <tr><td><div class="categories">' + subject + institution + level_of_study +  '</div></td></tr>\
                        </table>\
                    </td>\
                    <td width="150px">\
                        <table height="100px">\
                            <tr height="100%"><td></td></tr>\
                            <tr><td>' + timestamp + '</td></tr>\
                            <tr>\
                                <td>\
                                    <table height="50px">\
                                        <tr>\
                                            <td>' + userImg + '</td>\
                                            <td>' + userLink + '\
                                                <div><strong>' + question.score + getSmallBulbImage() + '</strong></div>\
                                            </td>\
                                        </tr>\
                                    </table>\
                                </td>\
                            </tr>\
                        </table>\
                    </td>\
                </tr>\
            </table>\
        </div><hr></hr>';
    }
    
    $(function() {
        $('input, textarea').placeholder();
    });
})();
