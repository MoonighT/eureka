(function() {
    var $submit, $answerInput;

    function showAnswerForm() {
        $answerInput.prop('rows', 5);
        $submit.show();
    }
    function hideAnswerForm() {
        $answerInput.prop('rows', 1);
        $submit.hide();
    }

    function thumbUp(event) {
        var $link = $(this);
        var action = $link.attr('data-action');
        var type = $link.attr('data-type');
        $.post(urlConfig.thumbUp, {
            action: action,
            type: type,
            id: $link.attr('data-id'),
        }, function(response) {
            if (!response)
                return;
            var $img = $('img', $link);
            var src = $img.prop('src');
            if (action === 'thumb-up') {
                src = src.replace('off', 'on');
            } else {
                src = src.replace('on', 'off');
            }
            $img.prop('src', src);
            $link.attr('data-action', action === 'thumb-up' ? 'cancel-thumb-up' : 'thumb-up');

            var $multiplier = $('.thumb-up-multiplier', $link.parents('.' + type + '-container'))
            $multiplier.html(response.count);
        }, 'json');
        event.preventDefault();
    }

    function acceptAnswer(event) {
        event.preventDefault();
        var $link = $(this);
        var action = $link.attr('data-action');
        if (action === 'accept' && $('a[data-action="cancel-accept"]').length)
            return;

        $.post(urlConfig.acceptAnswer, {
            action: action,
            id: $link.attr('data-id'),
        }, function(response) {
            if (!response)
                return;
            var $img = $('img', $link);
            var src = $img.prop('src');
            if (action === 'accept') {
                src = src.replace('pending', 'accepted');
            } else {
                src = src.replace('accepted', 'pending');
            }
            $img.prop('src', src);
            $link.attr('data-action', action === 'accept' ? 'cancel-accept' : 'accept');
        }, 'json');
    }

    function inviteFriends() {
        FB.ui({
            method: 'send',
            name: $('#question-title').html(),
            link: document.location.href,
            display: 'popup',
            description: $('#question-content').html(),
        });
    }
    
    function setThumbUpToolTip($link) {
        var title = 'This ' + $link.attr('data-type') + ' is useful<br>(click again to undo)';
        $('img.bulb-big', $link).tooltip({title: title});
    }

    function setAcceptToolTip($link) {
        var title = 'This is the best answer<br>(click again to undo)';
        $('img.accept-status', $link).tooltip({title: title});
    }

    $(function() {
        var $answerForm = $('#quick-answer-form');
        $submit = $('[type="submit"]', $answerForm);
        $answerInput = $('textarea', $answerForm);
        $answerInput.focus(showAnswerForm).blur(function() {
            if ($answerInput.val())
                return;
            hideAnswerForm();
        });
        $answerForm.submit(function(event) {
            if (!$(this).validate)
                event.preventDefault();
        });

        $('.thumb-up').click(thumbUp).each(function() {
            setThumbUpToolTip($(this));
        });
        $('.accept-answer').click(acceptAnswer).each(function() {
            setAcceptToolTip($(this));
        });
        $('#invite-link').click(inviteFriends);
    });
})();
