(function() {
	// body...


	function updateResults($container, data, url){
		$message = '<h3>Search result for "' + data.message + '":</h3><hr>';
        var html = $message + '\n';
        
        if(data.data.length){
	   	   $.each(data.data, function(index, question) {
	            html += getQuestionElement(question);
	        });
		}
		else 
			html += "Sorry, there is no reuslt...";
        $container.html(html);
        $container.ajaxPager(data.totalPages, data.currentPage, url, updateResults);

	}

	

	$(function() {
		var url = document.location.search;
		loadAjaxContent($('#result_container'), urlConfig.search + url, updateResults);
	});
})();
