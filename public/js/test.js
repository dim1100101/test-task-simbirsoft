var offset = 0;
var defaultLimit = 20;
var currentId = 0;
var files = {};

function sendUpdateRequest() {
	$('#dataContainer').html('');

	var data = {
		'description': $('#updateDescription').val()
	};

	$.ajax({
		'url': apiFilesRoute + '/' + currentId,
		'type': 'PATCH',
		'dataType': 'json',
		'data': data,
		'headers': {
			"X-HTTP-Method-Override": 'PATCH'
		},
		'success': function (data) {
			clearUpdateForm();
			buildDataTable();
		},
		'error': function () {
			alert('Произошла ошибка');
		}
	});
	return false;
}

function deleteFile(id, name) {
	if (confirm('Вы действительно хотите удалить файл ' + name + '?')) {
		$('#dataContainer').html('');
		$.ajax({
			'url': apiFilesRoute + '/' + id,
			'type': 'DELETE',
			'success': function (data) {
				buildDataTable();
				if (data && data['message']) {
					alert(data['message']);
				}
			},
			'error': function () {
				alert('Произошла ошибка');
			}
		});
	}
	return false;
}

function editFile(id) {
	currentId = id;
	$(window).scrollTop(0);
	$('#updateFileName').html(files[id]['filename']);
	$('#updateDescription').val(files[id]['description']);
	$('#updateFileInfo').show();
}

function buildFilesTable() {
	if (!files) {
		return;
	}

	if (!Object.keys(files).length) {
		$('#dataContainer').html('');
		alert("Больше записей не найдено");
	}

	for (var id in files) {
		var file = files[id];

		var tr = $('<tr/>');
		var idCol = $('<td/>', {'scope': 'row'});
		$(idCol).html(id);
		var emailCol = $('<td/>');
		$(emailCol).html(file['email']);
		var filenameCol = $('<td/>');
		$(filenameCol).html('<a href="' + file['route'] + '" target="_blank">' + file['filename'] + '</a>');
		var descriptionCol = $('<td/>');
		$(descriptionCol).html(file['description']);
		var actionsCol = $('<td/>');

		var btnEdit = $('<button/>', {'type': 'submit', 'class': 'btn btn-primary', 'style': 'margin: 5px 5px 5px 0px;', 'onclick': 'editFile(' + id + ')'});
		$(btnEdit).html('Редактировать');

		var btnDel = $('<button/>', {'type': 'submit', 'class': 'btn btn-danger', 'onclick': 'deleteFile(' + id + ', "' + file['filename'] + '")'});
		$(btnDel).html('Удалить');

		$(actionsCol).append($(btnEdit));
		$(actionsCol).append($(btnDel));

		$(tr).append($(idCol));
		$(tr).append($(emailCol));
		$(tr).append($(filenameCol));
		$(tr).append($(descriptionCol));
		$(tr).append($(actionsCol));
		$('#dataContainer').append($(tr));
	}
}

function buildDataTable() {
	$('#dataContainer').html('');
	$.get(apiFilesRoute, {
		'limit': $('#limit').val(),
		'offset': offset
	}, function (data) {
		if (!data) {
			$('#dataContainer').html('');
			alert("Произошла ошибка");
			return;
		}

		if (data['data']) {
			files = data['data'];
			buildFilesTable();
		} else {
			$('#dataContainer').html('');
			alert("Больше записей не найдено");
		}
	});
}

function clearUpdateForm() {
		currentId = 0;
		$('#updateDescription').html('');
		$('#updateFileInfo').hide();
}

$(document).ready(function () {

	$('#updateFileInfo').hide();

	$('#updateBtn').click(function () {
		sendUpdateRequest();
		return false;
	});

	$('#cancelBtn').click(function () {
		clearUpdateForm();
		return false;
	});

	$('#showBtn').click(function () {
		offset = 0;
		buildDataTable();
		return false;
	});

	$('#nextBtn').click(function () {
		limit = $('#limit').val();
		offset = offset + (limit * 1);
		buildDataTable();
		return false;
	});

	$('#prevBtn').click(function () {
		limit = $('#limit').val();
		offset = offset - (limit * 1);
		if (offset < 0) {
			offset = 0;
		}
		buildDataTable();
		return false;
	});

	buildDataTable();
});