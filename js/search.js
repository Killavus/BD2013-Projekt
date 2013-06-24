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

function filter2(word,tBody,site) {
	$.getJSON(site+'?word='+word,function(data) {
		tBody.html('');
		console.log(data);
		for(var i = 0; i < data.length; ++i) {
			if(continuable.indexOf(data[i].id_gry) >= 0) {
				tBody.append('<tr>'+
					'<td>' + data[i].nazwa_gry + '</td>' +
					'<td>' + data[i].nazwa + ' (' + data[i].login + ') </td>' +
					'<td> <a href="?page=play&action=new&gid='+data[i].id_gry+'"' + 
						'class="btn btn-primary btn-small">Nowa gra</a>' +
					'<a href="?page=play&action=continue&gid='+data[i].id_gry+'"' +
						'class="btn btn-info btn-small">Kontunuuj</a>' +
					'</td>' +
				'</tr>');
			}
			else {
				tBody.append('<tr>'+
					'<td>' + data[i].nazwa_gry + '</td>' +
					'<td>' + data[i].nazwa + ' (' + data[i].login + ') </td>' +
					'<td> <a href="?page=play&action=new&gid='+data[i].id_gry+'"' + 
						'class="btn btn-primary btn-small">Nowa gra</a>' +
					'</td>'+
				'</tr>');
			}
		}
	});
}

$(".search_table").each(function(){
	var searchInput = $(".search_input").filter("[search-connection="+$(this).attr('id')+"]");
	var tBody = $(this).find('tbody');
	var site = tBody.attr('search');
	var game_id = $(this).attr('game_id');
	var filter_v = $(this).attr('filter');

	searchInput.keyup(function(){
		if(filter_v == 1)
			filter(searchInput.val(),tBody,site,game_id);
		else
			filter2(searchInput.val(),tBody,site);
	});
	if(filter_v == 1)
		filter('',tBody,site,game_id);
	else
		filter2(searchInput.val(),tBody,site);
});
