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
			
			
			if (isset($_GET['edit_user']) && strlen($_GET['edit_user']) != 0) {
				$edit_user = $_GET['edit_user'];
				//выбираем данные по пользователю 
				$result_user_data = mysql_query("SELECT 
														u.id, 
														u.uchastok, 
														u.email, 
														u.name, 
														u.phone, 
														u.sch_model, 
														u.sch_num, 
														u.sch_plomb_num, 
														u.balans, 
														uc.num, 
														uc.date_start 
													FROM users u, users_contracts uc WHERE u.id = ".$_GET['edit_user']." AND u.id = uc.user AND uc.date_end IS NULL") or die(mysql_error());
				//echo "SELECT u.id, u.uchastok, u.name, u.phone, u.sch_model, u.sch_num, u.sch_plomb_num, u.balans, uc.num, uc.date_start FROM users u, users_contracts uc WHERE u.id = ".$_GET['edit_user']." AND u.id = uc.user AND uc.date_end IS NULL";
				
				while ($user_data = mysql_fetch_assoc($result_user_data)) {
					$user_id = $user_data['id'];
					$user_email = $user_data['email'];
					$user_uchastok = $user_data['uchastok'];
					$user_name = $user_data['name'];
					$user_phone = $user_data['phone'];
					$user_sch_model = $user_data['sch_model'];
					$user_sch_num = $user_data['sch_num'];
					$user_sch_plomb_num = $user_data['sch_plomb_num'];
					$user_contract_num = $user_data['num'];
					$user_contract_date = $user_data['date_start'];
				}
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
				
				$q_upd_user = "UPDATE users SET 
												name = '$add_fio', 
												email = '$add_email', 
												phone='$add_phone', 
												uchastok = '$add_uchastok', 
												sch_model = '$add_sch_model', 
												sch_num = '$add_sch_num', 
												sch_plomb_num = '$add_sch_pl_num' WHERE id = $edit_user";
				//echo $q_upd_user;
				mysql_query($q_upd_user) or die(mysql_error());
				
				
				//Удаляем тарифы пользователя 
				mysql_query("DELETE FROM users_tarifs WHERE user = $edit_user") or die(mysql_error());
				
				//Добавляем основной тариф
				//echo 'Добавляем тариф1';
				//echo '<br>';
				$q_add_tarif1 = "INSERT INTO users_tarifs SET user = $edit_user, tarif = $add_tarif1";
				//echo $q_add_tarif1;
				//echo '<br>';
				mysql_query($q_add_tarif1) or die(mysql_error());
				
				
				if ($add_tarif2 != 0) {
					//echo 'Добавляем тариф2';
					//echo '<br>';
					$q_add_tarif2 = "INSERT INTO users_tarifs SET user = $edit_user, tarif = $add_tarif2";
					//echo $q_add_tarif2;
					mysql_query($q_add_tarif2) or die(mysql_error());
				}
				
				//Проверяем изменился ли договор
				if ($user_contract_num != $add_contract_num) {
					//закрываем предыдущий договор 
					mysql_query("UPDATE users_contracts SET date_end = '$curdate' WHERE user = $edit_user") or die(mysql_error());
					//Добавляем новый договор
					mysql_query("INSERT INTO users_contracts SET user = $edit_user, type = 1, num = '$add_contract_num', date_start = '$add_contract_date'") or die(mysql_error());
				}
				
				//Добавляем пользователю договор на энергопотребление
				//$q_add_contract = "INSERT INTO users_contracts SET user = $add_user_id, type = 1, num = '$add_contract_num', date_start = '$add_contract_date'";
				//mysql_query($q_add_contract) or die(mysql_error());
				
				echo '<script type="text/javascript">swal("", "Пользователь сохранен", "success")</script>';
				
				header("Location: admin_user_edit.php?edit_user=$edit_user");
				
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
		<link rel="stylesheet" href="css/my.css">
		

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
										<?php include_once "include/admin_menu.php"; ?>
									</nav>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
								  <h3>Редактирование пользователя</h3>
									<div class="table-responsive">
									
									
											<form method="GET" role="form" id="AddUser">
												<div class="form-group">
													<label for="InputFIO">ФИО</label>
													<input name="fio" type="text" class="form-control" id="InputFIO" value="<?php echo $user_name; ?>">
													<input name="edit_user" type="hidden" value="<?php echo $_GET['edit_user']; ?>">
												</div>
												<div class="form-group">
													<label for="InputEmail">Email</label>
													<input name="email" type="email" class="form-control" id="InputEmail" value="<?php echo $user_email; ?>">
												</div>
												<div class="form-group">
													<label for="InputPhone">Телефон</label>
													<input name="phone" type="text" class="form-control" id="InputPhone" value="<?php echo $user_phone; ?>">
												</div>
												
												<div class="form-group">
													<label for="InputUcastok">Номер участка</label>
													<input name="uchastok" type="text" class="form-control" id="InputUcastok" value="<?php echo $user_uchastok; ?>">
												</div>
												<div class="form-group">
													<label for="InputSchModel">Модель счетчика</label>
													<input name="sch_model" type="text" class="form-control" id="InputSchModel" value="<?php echo $user_sch_model; ?>">
												</div>
												<div class="form-group">
													<label for="InputSchNum">Номер счетчика</label>
													<input name="sch_num" type="text" class="form-control" id="InputSchNum" value="<?php echo $user_sch_num; ?>">
												</div>
												<div class="form-group">
													<label for="InputSchPlumbNum">Номер пломбы</label>
													<input name="sch_pl_num" type="text" class="form-control" id="InputSchPlumbNum" value="<?php echo $user_sch_plomb_num; ?>">
												</div>
												
												<div class="form-group">
													<label for="InputContractNum">Договор на электропотребление</label>
													<input name="contract_num" type="text" class="form-control" id="InputContractNum" value="<?php echo $user_contract_num; ?>">
													
													<input name="contract_date" type="date" class="form-control" id="InputContractNum" value="<?php echo $user_contract_date; ?>">
												</div>
												
												
												
												
														<?php
														$result_tarifs = mysql_query("SELECT * FROM tarifs") or die(mysql_error());
														
														$result_curent_tarifs = mysql_query("SELECT t.name FROM tarifs t, users_tarifs ut WHERE t.id = ut.tarif AND ut.user = $edit_user") or die(mysql_error());
														
														if (mysql_num_rows($result_curent_tarifs) == 1) {
															echo '<div class="form-group">';
															echo '<label for="InputTarif1">Основной тариф</label>';
															echo '<select class="form-control" name="tarif1" id="InputTarif1">';
															$result_curent_tarif1 = mysql_query("SELECT t.id FROM tarifs t, users_tarifs ut WHERE t.id = ut.tarif AND ut.user = $edit_user LIMIT 1") or die(mysql_error());
															while ($curent_tarif = mysql_fetch_assoc($result_curent_tarif1)) {
																$curent_tarif_id = $curent_tarif['id'];
																
															}
															while ($tarif = mysql_fetch_assoc($result_tarifs)) {
																if ($curent_tarif_id == $tarif['id']) {
																	echo '<option value="'.$tarif['id'].'" selected="selected">'.$tarif['name'].'</option>';
																}
																else {
																	echo '<option value="'.$tarif['id'].'">'.$tarif['name'].'</option>';
																}
															}
															echo '</select>';
															echo '</div>';
															echo '<div class="form-group">';
															echo '<label for="InputTarif2">Дополнительный тариф</label>';
															echo '<select class="form-control" name="tarif2" id="InputTarif2">';
															$result_tarifs2 = mysql_query("SELECT * FROM tarifs") or die(mysql_error());
															echo '<option value="0">Нет</option>';
															while ($tarif2 = mysql_fetch_assoc($result_tarifs2)) {
																
																	echo '<option value="'.$tarif2['id'].'">'.$tarif2['name'].'</option>';
																
															}
															echo '</select>';
															echo '</div>';
														}
														else if (mysql_num_rows($result_curent_tarifs) == 2) {
															echo '<div class="form-group">';
															echo '<label for="InputTarif1">Основной тариф</label>';
															echo '<select class="form-control" name="tarif1" id="InputTarif1">';
															$result_curent_tarif1 = mysql_query("SELECT t.id FROM tarifs t, users_tarifs ut WHERE t.id = ut.tarif AND ut.user = $edit_user LIMIT 1") or die(mysql_error());
															while ($curent_tarif = mysql_fetch_assoc($result_curent_tarif1)) {
																$curent_tarif_id = $curent_tarif['id'];
															}
															while ($tarif = mysql_fetch_assoc($result_tarifs)) {
																if ($curent_tarif_id == $tarif['id']) {
																	echo '<option value="'.$tarif['id'].'" selected="selected">'.$tarif['name'].'</option>';
																}
																else {
																	echo '<option value="'.$tarif['id'].'">'.$tarif['name'].'</option>';
																}
															}
															echo '</select>';
															echo '</div>';
															
															echo '<div class="form-group">';
															echo '<label for="InputTarif2">Дополнительный тариф</label>';
															echo '<select class="form-control" name="tarif2" id="InputTarif2">';
															$result_tarifs2 = mysql_query("SELECT * FROM tarifs") or die(mysql_error());
															$result_curent_tarif2 = mysql_query("SELECT t.id FROM tarifs t, users_tarifs ut WHERE t.id = ut.tarif AND ut.user = $edit_user ORDER BY t.id DESC LIMIT 1") or die(mysql_error());
															while ($curent_tarif = mysql_fetch_assoc($result_curent_tarif2)) {
																$curent_tarif_id = $curent_tarif['id'];
															}
															while ($tarif2 = mysql_fetch_assoc($result_tarifs2)) {
																if ($curent_tarif_id == $tarif2['id']) {
																	echo '<option value="'.$tarif2['id'].'" selected="selected">'.$tarif2['name'].'</option>';
																}
																else {
																	echo '<option value="'.$tarif2['id'].'">'.$tarif2['name'].'</option>';
																}
															}
															echo '</select>';
															echo '</div>';
														}
														
														
														
														
														?>
													
												
												<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</button>
											</form>
									<br><br>
									
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