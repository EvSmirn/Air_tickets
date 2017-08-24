<html>
    <head>
    <title>Регистрация</title>
     <meta charset="UTF-8">
        <link rel='stylesheet prefetch' href='http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css'>
        <link rel="stylesheet" href="css/style_index.css">
       
    </head>
    <body>
    <h2 align="center">Регистрация</h2>
  <?php 
include 'DB.php';
$db_server = mysqli_connect($host, $bd_user, $pass, $bd_name);
if (!$db_server) {
    die("Невозможно подключиться к MySQL: " . mysqli_error());
}
mysqli_select_db($db_server, 'user')
or die("Невозможно выбрать базу данных: " . mysqli_error());


?>
    <div class="wrapper">
    <form class="form-signin" action="reg.php" method="post" id="form">       
      <h2 class="form-signin-heading">Заполните поля</h2>
      
      <input type="text" class="form-control" name="login" placeholder="Логин" required="" autofocus="" id="login"/>
      <input type="password" class="form-control" name="password" placeholder="Пароль" required id="password"/>
       <input type="surename" class="form-control" name=surename placeholder="Фамилия" required="" autofocus="" id="surename"/>
       <input type="name" class="form-control" name="name" placeholder="Имя" required="" autofocus="" id="name"/>
       <input type="middle_name" class="form-control" name="middle_name" placeholder="Отчество" required="" autofocus="" id="middle_name"/>
      <input type="passport_ID" class="form-control" name="passport_ID" placeholder="Номер паспорта" required="" autofocus="" id="passport_ID"/>
      <input type="issued_by" class="form-control" name="issued_by" placeholder="Кем выдан" required="" autofocus="" id="issued_by"/> 
      <input type="phone_number" class="form-control" name="phone_number" placeholder="Номер телефона" required="" autofocus="" id="phone_number"/>
      <input type="address" class="form-control" name="address" placeholder="Адрес" required="" autofocus="" id="address"/>
      <input type="e_mail" class="form-control" name="e_mail" placeholder="Е-mail" required="" autofocus="" id="e_mail"/>
      <input type="submit" name="submit" value="Зарегистрироваться" class="btn btn-lg btn-primary btn-block"/>
        
    </form>
  </div>
  
    <?php

    if (isset($_POST['login']) &&
isset($_POST['password']) &&
isset($_POST['surename']) &&
isset($_POST['name']) &&
isset($_POST['middle_name']) &&
isset($_POST['issued_by']) &&
isset($_POST['phone_number']) &&
isset($_POST['address']) &&
isset($_POST['e_mail']) &&
isset($_POST['passport_ID']))
{
$login = get_post($db_server, 'login');
$password = get_post($db_server, 'password');
$surename = get_post($db_server,'surename');
$name = get_post($db_server,'name');
$middle_name = get_post($db_server,'middle_name');
$issued_by = get_post($db_server,'issued_by');
$phone_number = get_post($db_server,'phone_number');
$address = get_post($db_server,'address');
$e_mail = get_post($db_server,'e_mail');
$passport_ID = get_post($db_server,'passport_ID');

$login = stripslashes($login);
$login = htmlspecialchars($login);
$password = stripslashes($password);
$password = htmlspecialchars($password);
 //удаляем лишние пробелы
 $login = trim($login);
 $password = trim($password);
 $password    = md5($password);//шифруем пароль          
$password    = strrev($password);// для надежности добавим реверс       

   
    $result = mysqli_query($db_server, "SELECT id FROM users WHERE login='$login'");
    $myrow = mysqli_fetch_array($result);
    if (!empty($myrow['id'])) {
    exit ("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");
    }

    // если такого нет, то сохраняем данные
 
   $result2 = mysqli_query ($db_server, "INSERT INTO users (login,password,surename,name,middle_name,issued_by,phone_number,address,e_mail, passport_ID) VALUES('$login','$password','$surename','$name','$middle_name','$issued_by','$phone_number','$address','$e_mail','$passport_ID')");
    // Проверяем, есть ли ошибки
  if ($result2=='TRUE')
    {
    echo "Вы успешно зарегистрированы! Теперь вы можете зайти на сайт. <a href='index.php'>Главная страница</a>";
    }
 else {
    echo "Ошибка! Вы не зарегистрированы.";
    }
}
mysqli_close($db_server);
function get_post($db_server, $var)
{
return mysqli_real_escape_string($db_server, trim($_POST[$var]));
}
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
           <script src="js/jquery.validate.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.validate.js"></script>
        <script src="js/jVal.js"></script>
        <script src="js/jPas.js"></script>
         <script src="js/jquery.inputmask.js"></script>
        

    </body>
    </html>