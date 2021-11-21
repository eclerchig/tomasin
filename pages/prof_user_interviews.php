<?php  
include "includes/bd.php";
$id = $_SESSION['id'];
?>

<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST["do_interview"]))
  {
    $query = "SELECT * FROM `interviews` WHERE ID_user='$id' AND accepted='0';";
    $rows = mysqli_query($connect,$query);
    $count = mysqli_num_rows($rows);
    if ($count < 1)
    {
       $query = "INSERT INTO `interviews` (ID_user) VALUES ('$id');";
        mysqli_query($connect, $query);
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<title>Главная страница "Томасина"</title>
	<link rel="stylesheet" href="../../style.css">
</head>
<body>

<?php include "moduls/mod_reg.php" ?>
<?php include "moduls/mod_log.php" ?>
<?php include "moduls/nav_menu.php" ?>

<div class="container-fluid background pt-5">
	<div class="row justify-content-center">	
		<div class="col-lg-1 col-0"> </div> 
    <div class="row col-lg-10 col-12">
    <div class="col-md-4 col-12">
    <div class="col-12 content py-5 mb-5" id="col-left">
			<ul class="nav flex-column nav-left">
  				<li class="nav-item">
  					<div class="row nav-left-row">	
  						<div class="col-lg-2 col-12"><p id="center-i"><img src="/tomasina/pic/settings.png" alt="" class="item-m"></p></div>
  						<div class="col-lg-10 col-12"><a class="nav-link" href="/tomasina/pages/prof/edit">Редактировать профиль</a></div>
  					</div>	
  				</li>
  				<li class="nav-item">
  					<div class="row nav-left-row ">	
  						<div class="col-lg-2 col-12"><p id="center-i"><img src="/tomasina/pic/interview.png" alt="" class="item-m"></p></div>
  						<div class="col-lg-10 col-12"><a class="nav-link" href="/tomasina/pages/prof/interviews">Собеседования</a></div>
  					</div>
  				</li>
  				<li class="nav-item">
  					<div class="row nav-left-row">	
  						<div class="col-lg-2 col-12"><p id="center-i"><img src="/tomasina/pic/cat.png" alt="" class="item-m"></div>
  						<div class="col-lg-10 col-12"><a class="nav-link" href="/tomasina/pages/prof/cats">Приобрести котика</a></div>
  					</div>
  				</li>
			</ul>
		</div>
		<div class="col-12">	</div>
		</div>	
		<div class="col-md-7 offset-md-1 col-12 content mb-5">
			<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  				<ol class="breadcrumb">
    				<li class="breadcrumb-item" aria-current="page"><a href="/tomasina/pages/prof">Личный кабинет</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Собеседования</li>
  				</ol>
			</nav>
			<h1>СОБЕСЕДОВАНИЯ</h1>
        <?php 
          $rows = mysqli_query($connect, "SELECT * FROM `interviews` WHERE `ID_user` = '$id' AND `result` <> '0' ORDER BY `datetime`");
          if  (empty ($rows))
          {
            ?>
              <label>Не пройдено ни одного собеседования.</label>
          <?php
          }
          else
          {?>
            <table class="table mt-4 table-success table-hover table_int">
            <thead>
              <tr align="center">
                <th rowspan="2" scope="col" align="center" style="border-bottom-color: #000000">Дата и время</th>
                <th colspan="3" scope="col" align="center">Баллы</th>
                <!-- <th  rowspan="2" scope="col" align="center" style="border-bottom-color: #000000">Действует до</th> -->
                <th  rowspan="2" scope="col" align="center" style="border-bottom-color: #000000">Итоговая оценка</th>
              </tr>
              <tr>
                <td align="center">Оценка подготовки дома</td>
                <td align="center">Оценка транспортировки</td>
                <td align="center">Оценка обеспечения питанием</td>
              </tr>
            </thead>
            <tbody>
              <?php
              while ($row = mysqli_fetch_assoc($rows)) {
                ?>
                <tr align="center" style="vertical-align: middle;">
                <th scope="row"><?php echo $row['datetime']?></th>
                <td><?php echo $row['rate_home']?></td>
                <td><?php echo $row['rate_trans']?></td>
                <td><?php echo $row['rate_care']?></td>
                <td><?php 
                echo (($row['result'] < 6) ? 'Не пройдено':$row['result'])?></td>
                </tr>
                <?php
              }
              ?>
            </tbody>
            </table>
            <?php
              }
      ?>
      <div class="row justify-content-center">
        <div class="col-2"> </div>
          <div class="col- mb-5">     
            <button type="button" class="btn btn-info col-12 mt-3" data-bs-toggle="modal" data-bs-target="#order_interview">Записаться на собеседование</button>
          </div>
        <div class="col-2"> </div>
      </div>
      <h4 >Ближайшее собеседование</h4>
      <?php 
          $rows = mysqli_query($connect, "SELECT * FROM `interviews` WHERE `ID_user` = '$id' AND `accepted` = '1';");
          $count = mysqli_num_rows($rows);
          if  ($count < 1)
          {
            ?>
            <label>Вы не назначали собеседований в ближайшее время</label>
          <?php
          }
          else
          {?>
          <label>
            Дата и время встречи: 
            <?php    
            $row = mysqli_fetch_assoc($rows); 
            $time = $row['datetime'];
            echo $time 
          ?>              
          </label>
        <?php  } ?>
		</div>
</div>
<div class="col-1"> </div>
<div class="modal fade" id="order_interview" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        С вами в течение дня свяжется оператор.
      </div>
      <div class="modal-footer">
        <form action="" method="POST">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
          <button type="submit" name="do_interview" class="btn btn-primary">Хорошо</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include "moduls/footer.php" ?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
	<script src="jquery-3.6.0.min.js"></script>
	<script src="jq.js"></script>
</body>
</html>