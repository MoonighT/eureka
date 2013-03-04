(function() {
    $.fn.extend({
        validate: function() {
            $form = $(this).filter('form');
            var valid = true;
            $('[required=true]', $form).each(function() {
                $this = $(this);
                $this.val($this.val().trim());
                if ($this.val() === '') {
                    valid = false;
                    $this.addClass('error');
                }
            });
            return valid;
        }
    });

    $(function() {
        $('form [required=true]').focus(function(event) {
            $(this).removeClass('error');
        });
        $('form [type=submit]').click(function(event) {
            if (!$(this).parents('form').validate())
                event.preventDefault();
        });
    });
})();
