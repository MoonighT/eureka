(function() {
	function getRankElement(item){
		var userImg = getFbImage(item.user_id);
		var best_at_html = '';
		$.each(item.user_best_at,function(index,item){
			best_at_html += '<span class="label label-info">'+item+'</span>'+'</br>';
		});
        var profile_link = urlConfig.profile + '?fuid='+item.user_id;
		return '\
		<div class="row">\
	        <div class="span1" align="right" style="height:100%">\
	            <span class="badge badge-info" style="font-size:14px">No. '+item.user_rank+'</span>\
	        </div>\
            <div class="span1" align="left" style="height:100%">\
            	'+userImg+'\
        	</div>\
		    <div class="span2">\
		        <h3><a href=' + profile_link + '>'+item.user_name+'</a></h3>\
		        <i>'+item.user_institution+'</i>\
		    </div>\
		    <div class="span2">\
		        <p><font size="4" style="bold">Best at:</font></p>\
		        <div>\
		            '+best_at_html+'\
		        </div>\
		    </div>\
		    <div class="span1">\
				<p>'+item.user_credit+'<span><img src="img/eureka_on_32.png" width="16px" height="16px"></span></p>\
				<p>'+item.user_degree+'</p>\
			</div>\
		</div>\
		<hr>';
	}
    function updateRanking($tab, data, url){
        if (!data) {
            loadTab($('.nav li.active a', $tab));
            return;
        }
        var html = '';
        $.each(data.data, function(index, item) {
             html += getRankElement(item);
        });
        $tab.html(html);
        console.log(data);
        $tab.ajaxPager(data.totalPages, data.currentPage, url, updateRanking);
    }

    
    function loadTab($a) {
        var $tab = $($a.attr('href'));
        var key = $tab.attr('id');
        var config = tabConfig[key];

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
    var tabConfig = {
        global: {url: urlConfig.ranking + '?type=global', update: updateRanking},
        friend: {url: urlConfig.ranking + '?type=friend', update: updateRanking},
    };
      
    
    $(function() {
        initTabs();
    });
})();
