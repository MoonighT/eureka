(function() {
    function split(val) {
        return val.split(/,\s*/);
    }
    function extractLast(term){
        return split(term).pop();
    }

    $(function() {
        var $institution = $('[name=institution]');
        $('[name=institution]').autocomplete({
            source: function(request, response) {
                $.get(urlConfig.autocomplete + '?type=institution&query=' + request.term, response, 'json');
            },
        });

        $('[name=subject]').autocomplete({
            source: function(request, response) {
                $.get(urlConfig.autocomplete + '?type=subject&query=' + request.term, response, 'json');
            },
        });

        $('[name=subjects]').each(function() {
            var $this = $(this);
            $this.bind( "keydown", function( event ) {
                if (event.keyCode === $.ui.keyCode.TAB && $this.data("autocomplete").menu.active)
                    event.preventDefault();
            }).autocomplete({
                source: function(request, response) {
                    query = extractLast(request.term).trim();
                    if (query === '')
                        return;
                    $.get(urlConfig.autocomplete + '?type=subject&query=' + query, function(subjects) {
                        var terms = split($this.val());
                        var availableSubjects = [];
                        $.each(subjects, function(index, value) {
                            if (terms.indexOf(value) == -1)
                                availableSubjects.push(value);
                        });
                        response(availableSubjects);
                    }, 'json');
                },
                focus: function(event) {
                    return false;
                },
                select: function(event, ui) {
                    var terms = split($this.val());
                    terms.pop();
                    terms.push(ui.item.value);
                    terms.push("");
                    this.value = terms.join(", ");
					return false;
                }
            });
        }); 
        
    });
})();
