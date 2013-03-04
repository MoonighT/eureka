(function() {
    function updateStatus(response) {
        if (response.authResponse) {
            $(document).trigger('fb-init');
            window.fbUserId = response.authResponse.userID;
        }
    }

    window.fbAsyncInit = function() {
        FB.init({
            appId: '263953760391187', // App ID
            status: true, // check login status
            cookie: true, // enable cookies to allow the server to access the session
            xfbml: true  // parse XFBML
        });
        FB.getLoginStatus(updateStatus);

        $('#logout-link').click(function() {
            FB.logout(function() {
                document.location.href = urlConfig.logout;
            });
        });
    };

    (function(d) {
        var e = document.createElement('script');
        e.async = true;
        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        var $fbRoot = $('<div id="fb-root"></div>');
        $('body').append($fbRoot);
        $fbRoot.append(e);
    }(document));
})();
