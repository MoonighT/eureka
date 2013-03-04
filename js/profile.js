(function() {

    function updateProfile($tab, data, url){
        if (!data) {
            loadTab($('.nav li.active a', $tab));
            return;
        }
        var html = '';
        $.each(data.data, function(index, question) {
            html += getQuestionElement(question);
        });
        $tab.html(html);
        $tab.ajaxPager(data.totalPages, data.currentPage, url, updateProfile);
    }

    
    function loadTab($a) {
        var $tab = $($a.attr('href'));
        var key = $tab.attr('id');
        var config = tabConfig(key);

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
    function tabConfig(key){
        var fid = window.fuid;
        if(key == 'asked')
            return {url:urlConfig.prof_page+fid+'&type=asked',update:updateProfile};
        else if (key=='answered')
            return {url:urlConfig.prof_page+fid+'&type=answered', update:updateProfile};
    }

    var $editButton, $cancelButton;
    var $institutionInput, $interestInput;
    function getInterests() {
        var $labels = $('#interest-labels .label');
        var interests = '';
        $labels.each(function() {
            if (interests !== '') {
                interests += ', ';
            }
            interests += $(this).html();
        });
        return interests;
    }
    
    function updateInterestLabels() {
        var interests = $interestInput.val().split(/,\s*/);
        var html = '';
        $.each(interests, function(index, value) {
            if (!value)
                return;
            html += '<span class="label label-warning" style = "margin-left:5px">' + value + '</span>';
        });
        $('#interest-labels').html(html);
    }

    function editProfile() {
        $('#institution-label, #interest-labels').hide();
        $interestInput.val(getInterests());
        $institutionInput.val($('#institution-label').html());
        $institutionInput.show();
        $interestInput.show();
        toggleEditButton();
    }

    function checkInput($input) {
        var value = $input.val().trim();
        $input.val(value);
        return value !== '';
    }

    function saveProfile() {
        if (!checkInput($institutionInput) || !checkInput($interestInput))
            return;
        $editButton.prop('disabled' ,1);
        $cancelButton.prop('disabled', 1);
        $.post(urlConfig.editProfile, {interests: $interestInput.val(), institution: $institutionInput.val()}, function(response) {
            var $institutionLabel = $('#institution-label');
            toggleEditButton();
            if (response) {
                updateInterestLabels();
                $institutionLabel.html($institutionInput.val());
            }
            $institutionInput.hide();
            $interestInput.hide();
            $institutionLabel.show();
            $('#interest-labels').show();
            $editButton.removeProp('disabled');
            $cancelButton.removeProp('disabled');
        });
    }

    function toggleEditButton() {
        var $icon = $('i', $editButton);
        var newTitle;
        if ($icon.hasClass('icon-edit')) {
            newTitle = 'Save';
            $cancelButton.show();
        } else {
            newTitle = 'Edit';
            $cancelButton.hide();
        }
        $editButton.tooltip('hide').attr('data-original-title', newTitle).tooltip('fixTitle').tooltip('show');
        $icon.toggleClass('icon-edit icon-ok');
    }
    
    $(function() {
        window.fuid = document.location.search;
        initTabs();
        $institutionInput = $('[id="profile-institution"]');
        $interestInput = $('[id="profile-subjects"]');
        $editButton = $('#edit-btn').removeProp('disabled');
        $editButton.tooltip({placement: 'right'}).click(function() {
            var $icon = $('i', this);
            if ($icon.hasClass('icon-edit')) {
                editProfile();
            } else {
                saveProfile();
            }
        });
        $cancelButton = $('#cancel-btn').removeProp('disabled').tooltip({placement: 'right'}).click(function() {
            $institutionInput.hide();
            $interestInput.hide();
            $('#institution-label, #interest-labels').show();
            toggleEditButton();
        });
    });
})();
