<?php
	
	include_once "core/db_connect.php";
	include_once "include/auth.php";
	
	
	
	$curdate = date("Y-m-d");
	
	if ($is_auth == 1) { 
	
		
		
		$result_user_is_admin = mysql_query("SELECT is_admin FROM users WHERE email = '".$_COOKIE["user"]."'") or die(mysql_error());
		
		while ($user_is_admin = mysql_fetch_assoc($result_user_is_admin)) {
			$is_admin = $user_is_admin['is_admin'];
		}
		
		//выбираем существующие тарифы
		
		
		
		if ($is_admin == 1) {
			
			$result_tarifs = mysql_query("SELECT * FROM tarifs") or die(mysql_error());
			$result_tarifs2 = mysql_query("SELECT * FROM tarifs") or die(mysql_error());
			
			if (isset($_GET['del_user']) && strlen($_GET['del_user'])!=0) {
				//Ставим пользователю пометку об удалении
				mysql_query("UPDATE users SET is_del = 1 WHERE id = ".$_GET['del_user']) or die(mysql_error());
				header("Location: admin_users.php");
			}
			
			if (isset($_GET['fio']) && strlen($_GET['fio'])!=0) {
				$add_fio = $_GET['fio'];
				$add_email = $_GET['email'];
				$add_phone = $_GET['phone'];
				$add_password = md5($_GET['password']);
				$add_uchastok = $_GET['uchastok'];
				$add_sch_model = $_GET['sch_model'];
				$add_sch_num = $_GET['sch_num'];
				$add_sch_pl_num = $_GET['sch_pl_num'];
				$add_start_ind = $_GET['start_ind'];
				$add_start_bal = $_GET['start_bal'];
				$add_tarif1 = $_GET['tarif1'];
				$add_tarif2 = $_GET['tarif2'];
				$add_contract_num = $_GET['contract_num'];
				$add_contract_date = $_GET['contract_date'];
				
				$q_add_user = "INSERT INTO users SET name = '$add_fio', email = '$add_email', pass = '$add_password', phone='$add_phone', uchastok = '$add_uchastok', sch_model = '$add_sch_model', sch_num = '$add_sch_num', sch_plomb_num = '$add_sch_pl_num', balans = $add_start_bal, start_indications = $add_start_ind, start_balans = $add_start_bal";
				//echo $q_add_user;
				mysql_query($q_add_user) or die(mysql_error());
				
				$add_user_id = mysql_insert_id();
				//добавляем пользователю основной тариф
				//echo 'Добавляем тариф1';
				//echo '<br>';
				$q_add_tarif1 = "INSERT INTO users_tarifs SET user = $add_user_id, tarif = $add_tarif1";
				//echo $q_add_tarif1;
				//echo '<br>';
				mysql_query($q_add_tarif1) or die(mysql_error());
				
				
				if ($add_tarif2 != 0) {
					//echo 'Добавляем тариф2';
					//echo '<br>';
					$q_add_tarif2 = "INSERT INTO users_tarifs SET user = $add_user_id, tarif = $add_tarif2";
					//echo $q_add_tarif2;
					mysql_query($q_add_tarif2) or die(mysql_error());
				}
				
				//Добавляем пользователю договор на энергопотребление
				$q_add_contract = "INSERT INTO users_contracts SET user = $add_user_id, type = 1, num = '$add_contract_num', date_start = '$add_contract_date'";
				mysql_query($q_add_contract) or die(mysql_error());
				
				$error_msg = '<script type="text/javascript">swal("", "Пользователь добавлен", "success")</script>';
				
			}
			
		}
	}
	
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
			.del_user{
				color:Crimson;
			}
			.del_user:hover{
				color:red;
			}
		</style>
		
		
		
	</head>
	<body>
		<?php echo $error_msg; ?>
		<?php include_once "include/head.php"; ?>
		
		
		
		<div class="container" style="padding-bottom: 50px;">
			
				
				
				  
					<?php 
					if ($is_auth == 1) { 
						if ($is_admin == 1) {
						?>
							<div class="row">
								<div class="col-md-12">
									<h2>Панель администратора</h2>
									<hr>
									<nav>
										<ul class="nav navbar-nav">
											<li><a href="admin_users.php">Пользователи</a></li>
											<li><a href="admin_indications.php">Показания</a></li>
											<li><a href="admin_payments.php">Платежи</a></li>
										</ul>
									</nav>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
								  <h3>Список пользователей</h3>
									<div class="table-responsive">
									<a href="#myModal" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> Добавить пользователя</a>
									<!-- HTML-код модального окна -->
									<div id="myModal" class="modal fade">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <!-- Заголовок модального окна -->
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Добавление пользователя</h4>
										  </div>
										  <!-- Основное содержимое модального окна -->
										  <div class="modal-body">
											<form method="GET" role="form" id="AddUser">
												<div class="form-group">
													<label for="InputFIO">ФИО</label>
													<input name="fio" type="text" class="form-control" id="InputFIO" placeholder="ФИО">
												</div>
												<div class="form-group">
													<label for="InputEmail">Email</label>
													<input name="email" type="email" class="form-control" id="InputEmail" placeholder="Email">
												</div>
												<div class="form-group">
													<label for="InputPhone">Телефон</label>
													<input name="phone" type="text" class="form-control" id="InputPhone" placeholder="Телефон">
												</div>
												<div class="form-group">
													<label for="InputPass">Пароль</label>
													<input name="password" type="password" class="form-control" id="InputPass" placeholder="Пароль">
												</div>
												<div class="form-group">
													<label for="InputUcastok">Номер участка</label>
													<input name="uchastok" type="text" class="form-control" id="InputUcastok" placeholder="Номер участка">
												</div>
												<div class="form-group">
													<label for="InputSchModel">Модель счетчика</label>
													<input name="sch_model" type="text" class="form-control" id="InputSchModel" placeholder="Модель счетчика">
												</div>
												<div class="form-group">
													<label for="InputSchNum">Номер счетчика</label>
													<input name="sch_num" type="text" class="form-control" id="InputSchNum" placeholder="Номер счетчика">
												</div>
												<div class="form-group">
													<label for="InputSchPlumbNum">Номер пломбы</label>
													<input name="sch_pl_num" type="text" class="form-control" id="InputSchPlumbNum" placeholder="Номер пломбы">
												</div>
												<div class="form-group">
													<label for="InputStartIndications">Начальный баланс</label>
													<input name="start_ind" type="text" class="form-control" id="InputStartIndications" value="0">
												</div>
												<div class="form-group">
													<label for="InputStartBalans">Начальные показания счетчика</label>
													<input name="start_bal" type="text" class="form-control" id="InputStartBalans" value="0">
												</div>
												<div class="form-group">
													<label for="InputContractNum">Договор на электропотребление</label>
													<input name="contract_num" type="text" class="form-control" id="InputContractNum" placeholder="Номер договора">
													
													<input name="contract_date" type="date" class="form-control" id="InputContractNum" placeholder="дата договора">
												</div>
												<div class="form-group">
													<label for="InputTarif1">Основной тариф</label>
													<select class="form-control" name="tarif1" id="InputTarif1">
														<?php
														while ($tarif = mysql_fetch_assoc($result_tarifs)) {
															echo '<option value="'.$tarif['id'].'">'.$tarif['name'].'</option>';
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<label for="InputTarif2">Дополнительный тариф</label>
													<select class="form-control" name="tarif2" id="InputTarif2">
														<option value="0">Нет</option>
														<?php
														while ($tarif2 = mysql_fetch_assoc($result_tarifs2)) {
															echo '<option value="'.$tarif2['id'].'">'.$tarif2['name'].'</option>';
														}
														?>
													</select>
												</div>
												
											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="document.getElementById('AddUser').submit(); return false;" >Сохранить</button>
										  </div>
										</div>
									  </div>
									</div>
									<br><br>
								  <?php 
									//выбираем всех пользователей
									$result_all_users = mysql_query("SELECT u.id, u.uchastok, u.name, u.phone, u.sch_model, u.sch_num, u.sch_plomb_num, u.balans, uc.num, uc.date_start FROM users u, users_contracts uc WHERE u.is_del = 0 AND u.id = uc.user AND uc.date_end IS NULL") or die(mysql_error());
									
									
									echo '<table class="table table-condensed">';
									echo '<tr>';
									echo '<th>Участок</th>';
									echo '<th>ФИО</th>';
									echo '<th>Телефон</th>';
									echo '<th>Номер договора</th>';
									echo '<th>Модель счетчика</th>';
									echo '<th>Номер счетчика</th>';
									echo '<th>Номер пломбы</th>';
									echo '<th>Баланс</th>';
									echo '<th></th>';
									echo '<th></th>';
									echo '</tr>';
									
									while ($users = mysql_fetch_assoc($result_all_users)) {
										if ($users['balans'] >= 0) {
											echo '<tr>';
										}
										else {
											echo '<tr class="danger">';
										}
										
										echo '<td>'. $users['uchastok'].'</td>';
										echo '<td>'. $users['name'].'</td>';
										echo '<td>'. $users['phone'].'</td>';
										//$date_indications = date( 'd.m.Y',strtotime($users['date_start']));
										echo '<td>'. $users['num'].' от '.date( 'd.m.Y',strtotime($users['date_start'])).'</td>';
										echo '<td>'. $users['sch_model'].'</td>';
										echo '<td>'. $users['sch_num'].'</td>';
										echo '<td>'. $users['sch_plomb_num'].'</td>';
										echo '<td>'. $users['balans'].'</td>';
										echo '<td><a href="admin_user_edit.php?edit_user='.$users['id'].'"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>';
										//echo '<td><a class="del_user" href="admin_users.php?del_user='.$users['id'].'"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
										echo '<td><a class="del_user" href="#" onclick="ConfirmDelUser('.$users['id'].')"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
										echo '</tr>';
									}
									echo '</table>';
									
								  ?>
									
									<script>
										function ConfirmDelUser(user_id) 
										{
											swal({
												title: 'Удалить пользователя?',
												text: 'Восстановление будет невозможно!',
												type: 'warning',
												showCancelButton: true,
												confirmButtonColor: '#dd6b55',
												cancelButtonColor: '#999',
												confirmButtonText: 'Да, удалить',
												cancelButtonText: 'Отмена',
												closeOnConfirm: false
											}, function() {
												swal(
												  'Выполнено!',
												  'Пользователь удален.',
												  'success'
												);
												document.location.href = "admin_users.php?del_user="+user_id;
											})
										}
									</script>
									
									</div>
								</div>
								
							</div>
						
						<?php 
						}
					} 
					else 
					{
					?>
					  <div class="col-md-12">
						  <h2>Вы не авторизованы</h2>
						  
					  </div>
					<?php
					}
					?>
			   
			
			
		</div>
		
		<?php include_once "include/footer.php"; ?>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

		
	</body>
</html>