<?php

  //222

	include_once "core/db_connect.php";
	include_once "include/auth.php";

?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Система управления СНТ</title>

		<script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/sweetalert.css">
		<link rel="stylesheet" href="css/my.css">
		<script src="js/sweetalert.min.js"></script>

		<style>
			#header {
				background: url(img/header.jpg);
				min-height: 280px;
				background-size: cover;
				background-repeat: no-repeat;
			}
			.news_date {
				color: #777;
			}
			th {text-align:center; vertical-align: middle !important; border: 1px rgb(221, 221, 221) solid;}
			td {text-align:center; vertical-align: middle !important;}

   			p.indent {
     			text-indent: 25px;
   				}
		</style>


		<script>
			var arr = [];
			var i = 0;
			$(":checkbox").change(function(){
				if(this.checked){
					arr[i] = $(this).val();
					i++;
				}else{
					var val = $(this).val();
					var index = arr.indexOf(val);
					arr.splice(index, 1);
					i--;
				}
				console.log(arr);
			});
		</script>

	</head>
	<body>


		<?php
		if (isset($error_msg)) {
			echo $error_msg;
		}

		if (isset($error)) {
			echo $error;
		}
		?>
		<?php include_once "include/head.php"; ?>

		<div class="jumbotron" id="header">
			<div class="container" ></div>
		</div>



					<?php
					if ($is_auth == 1) {
					if ($user_agreement == 0) {
						}
							else {
						?>
					<?php }
					}
					else
					{
					?>
					  <div class="col-md-12">
						  <h2>Вы не авторизованы</h2>
						  <hr>
					  </div>
					<?php
					}
					?>

					<div class="row">
						<div class="text-center">
							<h2>Раздел инструкции</h2>
							<p class="indent">Вданном разделе описываются действия по активации адреса электронной почты от товарищества и настройке переадресации на свой личный адрес электронной почты.</p>
						</div>
					</div>

					<div class="container">
	  <div class="panel panel-default">
		<div class="panel-heading">
		  <button type="button" class="btn btn-default btn-xs spoiler-trigger" data-toggle="collapse">Шаг 1: "<b>Вход в почтовый ящик</b>"</button>
		</div>
		<div class="panel-collapse collapse out">
		  <div class="panel-body">
			<p class="indent">Для входа в почтовый ящик <a href="https://passport.yandex.ru" target="_blank">Yandex надо пройти на страницу авторизации</a>, на которой надо ввести полученные логин и пароль.
			<img src="/img/ya/2.png" class="img-responsive center-block"/>  </p>
		  </div>
		</div>
	  </div>
	  <div class="panel panel-default">
		<div class="panel-heading">
		  <button type="button" class="btn btn-default btn-xs spoiler-trigger" data-toggle="collapse">Шаг 2: "<b>Завершение регистрации</b>"</button>
		</div>
		<div class="panel-collapse collapse out">
		  <div class="panel-body">
			<p class="indent">	При первом входе в почтовый ящик, необходимо внести свои данные для завершения регистрации. Заполнять необходимо все поля, ка кпоказано на картинке и нажать кнопку "<b>Завершить регистрацию</b>".
			<img src="/img/ya/4.png" class="img-responsive center-block"/> </p>
		  </div>
		</div>
	  </div>
	  <div class="panel panel-default">
		<div class="panel-heading">
		  <button type="button" class="btn btn-default btn-xs spoiler-trigger" data-toggle="collapse">Шаг 3: "<b>Настройка переадресации</b>"</button>
		</div>
		<div class="panel-collapse collapse out">
		  <div class="panel-body">
				<p class="indent">Если у вас есть ваш основной личный электронный адрес, и вы хотите получать уведомления от товарищества на него, то вам необходимо настроить переадресацию.</p>
				<p class="indent">Для настройки переадресации на странице электронной почты, в правом верхнем углу нажмите на значёк шестерёнки <img src="/img/ya/6.2.png"/> после чего появится меню настроек, как на катинке ниже, к котором необходимо выбрать пункт: "<b>Правила обработки писем</b>".
				<img src="/img/ya/6.1.png" class="img-responsive center-block"/> </p>
				<p class="indent">Далее войдя в раздел "<b>Правила обработки писем</b>" нажмите на жёлтую кнопку "<b>Создать правило</b>"
				<img src="/img/ya/6.3.png" class="img-responsive center-block"/></p>
				<p class="indent">При создании правила переадресации необходимо в форме заполнить и изменить следующие поля:
				<p>-"<b>Если</b>" - в которой на дописать только адрес текущего ящика в яндексе, а параметры <b>Кому</b> и <b>Совпадает с</b> оставить неизменными;</p>
				<p>-"<b>Выполнить действие</b>" - в которой ставим галочку только на пункте <b>Пересылать по адресу</b> и в поле на против него вписать адрес электронной почты, на который надо пересылать письма;</p>
				<img src="/img/ya/7.png" class="img-responsive center-block"/></p>
				После этих действий нажимаем на жёлтую кнопку "<b>Создать правило</b>" и подверждаем создание правила вводом пароля.
				<img src="/img/ya/8.png" class="img-responsive center-block"/></p>
				<p class="indent">Теперь после создания правила, к вам на указанный адрес для пересылки прийдёт писмо для подтверждения переадресации, в котором необходим на жать на слово (ссылку) "<b>подтвердите</b>", после чего вы перейдёте на страницу, где надо нажать на жёлтую кнопку "<b>Подтвердить пересылку</b>"</p>
				<img src="/img/ya/10.png" class="img-responsive center-block"/></p>
			</p>
		  </div>
		</div>
	  </div>
	</div>



		<?php include_once "include/footer.php"; ?>

		<script>
			$(".spoiler-trigger").click(function() {
				$(this).parent().next().collapse('toggle');
			});
		</script>


		<script>
		$('#indications a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		})
		$('#payments a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		})
		</script>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>


	</body>
</html>
