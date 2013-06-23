function filter(word,tBody,site,game_id) {
	$.getJSON(site+'?word='+word+'&gid='+game_id,function(data) {
		tBody.html('');
		for(var i = 0; i < data.length; ++i){
			tBody.append('<tr>'+
				'<td> '+data[i].id_uzytkownika+' </td>'+
				'<td> '+data[i].nazwa+' </td>'+
				'<td> <a href="actions/add_creator.php?gid='+game_id+'&uid='+data[i].id_uzytkownika+'"'+
					'class="btn btn-primary btn-small">Dodaj</a>'+
				'</td>'+
			'</tr>');
		}
	});
}

$(".search_table").each(function(){
	var searchInput = $(".search_input").filter("[search-connection="+$(this).attr('id')+"]");
	var tBody = $(this).find('tbody');
	var site = tBody.attr('search');
	var game_id = $(this).attr('game_id');

	searchInput.keyup(function(){
		filter(searchInput.val(),tBody,site,game_id);
	});
	filter('',tBody,site,game_id);
});
