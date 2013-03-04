(function() {
    $(function() {
        $('#ask-dialog').on('show', function() {
            $('input,textarea', $(this)).val('').removeProp('disabled');
            $('select', $(this)).val(0).removeProp('disabled');
            $('#ask-btn').html('Ask').add($('#cancel-ask-btn')).removeClass('disabled').removeProp('disabled');
            $('#ask-feed').prop('checked', true);
            var $institution = $('[name=institution]');
            // $institution.val($institution.attr('data-default'));
            $('#ask-form').show();
        });
        $('#ask-btn').click(function() {
            var $form = $('#ask-form');
            if (!$form.validate())
                return;
            var $btn = $(this);
            var data = $form.serialize();
            $btn.html('Asking...').add($('#cancel-ask-btn')).addClass('disabled').prop('disabled', true);
            $('input,textarea,select', $form).prop('disabled', true);
            $.post(urlConfig.ask, data, function(response) {
                var qid = response.qid;
                var questionUrl = urlConfig.question + '?qid=' + qid;
                if ($('#ask-feed:checked').length) {
                    FB.api('/me/feed', 'post', {
                        link: questionUrl,
                        name: $('[name="title"]').val(),
                        caption: questionUrl,
                        description: $('[name="content"]').val(),
                    }, function(response) {
                        document.location.href = questionUrl;
                    });
                } else {
                    document.location.href = questionUrl;
                }
            }, 'json');
        });
        $('#ask-feed').parent().tooltip({title: 'Share with your Facebook friends'});
    });
})();
