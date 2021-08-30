jQuery(document).ready(function ($) {
	const search_input = $('.search-form__input');
	const search_results = $('.ajax-search');

	search_input.keyup(function () {
		 let search_value = $(this).val();

		 if (search_value.length > 0) { // кол-во символов 
			  $.ajax({
					url: '/wp-admin/admin-ajax.php',
					type: 'POST',
					data: {
						 'action': 'ajax_search', // functions.php 
						 'term': search_value
					},
					success: function (results) {
						 search_results.fadeIn(200).html(results);
					}
			  });
		 } else {
			  search_results.fadeOut(200);
		 }
	});

	// Закрытие поиска при клике вне его 
	$(document).mouseup(function (e) {
		 if (
			  (search_input.has(e.target).length === 0) &&
			  (search_results.has(e.target).length === 0)
		 ) {
			  search_results.fadeOut(200);
		 };
	});
});