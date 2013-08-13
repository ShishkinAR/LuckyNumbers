/*
* Функция для обработка ответа сервера со списком счастливых номеров
* */
function load_list_callback(response) {
	var oMessage = $("#message"),
		oNumbers = $("#numbers"),
		oList = oNumbers.find('.list');
	if (!response.type) return false;
	if (response.type == 'error') {
		oMessage.html(response.message);
		return false;
	}
	if (response.type == 'ok' && response.data.numbers) {
		// Если ответ положительный и содержит список чисел - добавляем их в соответствующий div
		for (var i in response.data.numbers) if (response.data.numbers.hasOwnProperty(i)) {
			oList.append($("<p>" + response.data.numbers[i] + "</p>"));
		}
		return true;
	}
	return false;
}

$(function() {
	/*
	* Обработчик изменения значения в поле для ввода числа с проверкой этого числа
	* и запросом к серверу на получение количества соответствующих счастливых билетов
	* */
	$("#test_number").on('keyup', function(e) {
		e = e || window.event;
		var oBtn = $("#number_btn"),
			oMessage = $("#message"),
			oContext = $(this),
			oNumbers = $("#numbers"),
			sVal = oContext.val(),
			iVal = parseInt(sVal);
		oMessage.html('');
		oMessage.removeClass('loading');
		if (e.key && e.key == 13 && oBtn.attr('disabled') != 'disabled') {
			oBtn.click();
			return;
		}
		oBtn.attr('disabled', 'disabled');
		if (!sVal || !iVal || sVal != iVal.toString() || isNaN(iVal) || iVal < 0) {
			oMessage.html('Введите допустимое значение.');
			return;
		}
		if ((iVal % 2) == 1) {
			oMessage.html('Число должно быть четным.');
			return;
		}
		oMessage.addClass('loading');
		setTimeout(function() {
			if (sVal == oContext.val()) {
				oNumbers.find('.list').html('');
				$.ajax({
					type: "POST",
					url: "/number/api/check/",
					data: { number: iVal },
					dataType: 'json',
					success: function(response) {
						oMessage.removeClass('loading');
						if (sVal != oContext.val() || !response.type) return;
						if (response.type == 'error') {
							oMessage.html(response.message);
							return;
						}
						if (response.type == 'ok') {
							oMessage.html(response.message);
							oBtn.removeAttr('disabled');
						}
					},
					error: function(response) {
						oMessage.removeClass('loading');
						alert("Error in request. See console!");
						console.log(response);
					}
				});
			}
		}, 333);
	});
	/*
	* Обработчик нажатия на кнопку получения списка счастливых билетов
	* */
	$("#number_btn").on('click', function () {
		var oNumbers = $("#numbers"),
			oList = oNumbers.find('.list'),
			oContext = $(this);
		oList.html('');
		if (oContext.hasClass('loading')) {
			return;
		}
		oContext.addClass('loading');
		$.ajax({
			type: "GET",
			url: "/number/api/list/",
			data: { number: $("#test_number").val(), page: 1 },
			dataType: 'json',
			success: function(response) {
				oContext.removeClass('loading');
				if (load_list_callback(response)) {
					var oScroll = $.data(oList[0], 'infinitescroll');
					if (!oScroll) {
						// Подключение плагина для автоматической постраничной подгрузки списка
						oList.infinitescroll({
							loading: {
								finishedMsg: '<em>Вы достигли конца списка</em>',
								msgText: '<em>Загружаю список...</em>',
								selector: '.list_loader'
							},
							nextSelector: "div.navigation:last a:first",
							navSelector: "div.navigation:last",
							dataType: 'json',
							extraScrollPx: 500,
							appendCallback: false,
							path: function(p) {
								return '/number/api/list/?number=' + $("#test_number").val() + '&page=' + p;
							}
						}, function (json) {
							return load_list_callback(json);
						});
					} else {
						// Обновляем параметры скрола
						oScroll._binding('unbind');
						oScroll._binding('bind');
						oScroll.options.state.currPage = 1;
						oScroll.options.state.isDone = false;
						oScroll.options.state.isDuringAjax = false;
						oScroll.options.loading.msg
							.find('img')
							.show()
							.parent()
							.find('div').html(oScroll.options.loading.msgText);
					}
				}
			},
			error: function(response) {
				oContext.removeClass('loading');
				alert("Error in request. See console!");
				console.log(response);
			}
		});
	});
});