<html> 
<head> 
<title>Поиск авиабилетов</title> 
<meta charset="UTF-8">
        <link rel='stylesheet prefetch' href='http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css'/>
        <link rel="stylesheet" href="css/style_index.css"/>
</head> 
<body> 
<?php
    include 'DB.php';
    $db = mysqli_connect ($host, $bd_user, $pass , $bd_name);
    mysqli_select_db ($db, $bd_name);
    
    if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
    if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }
    //заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
if (empty($login) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
    {
    exit ("Введите свой логин и пароль или <a href='reg.php'>зарегестрируйтесь</a>");
    }
    //если логин и пароль введены,то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);
//удаляем лишние пробелы
    $login = trim($login);
    $password = trim($password);
//шифруем пароль    
    $password    = md5($password);
    $password    = strrev($password);

  
 
$result = mysqli_query($db, "SELECT * FROM users WHERE login='$login'"); //извлекаем из базы все данные о пользователе с введенным логином
    $myrow = mysqli_fetch_array($result);
    if (empty($myrow['password']))
    {
    //если пользователя с введенным логином не существует
    exit ("Извините, введённый вами login или пароль неверный.");
    }
    else {
    //если существует, то сверяем пароли
    if ($myrow['password']==$password) {
    //если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!
    $_SESSION['login']=$myrow['login']; 
    $_SESSION['id']=$myrow['id'];
    echo "Здравтсвуйте, ".$_SESSION['login']."<br>"; 
    }
 else {
    //если пароли не сошлись

    exit ("Извините, введённый вами login или пароль неверный.");
    }
    }
 
    ?>
    <div class="wrapper">
    <form class="form-signin" action="aviaticket.php" method="post" id="form">       
    <h2 class="form-signin-heading">Поиск билетов</h2>
    <select name="serviceClass" class="form-control" >
    <option value="ECONOM">Эконом</option>
    <option  value="BUSINESS">Бизнесс</option>
    </select>
    <select name="beginLocation" class="form-control" >
    <option value="LED">Санкт-Петербург</option>
    <option  value="MOW">Москва</option>
    </select>
    <select name="endLocation" class="form-control" >
    <option  value="MOW">Москва</option>
    <option value="LED">Санкт-Петербург</option>
    </select>
    Взрослых
    <select name="value1" class="form-control" >
    <option  value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    </select>
    Детей
    <select name="value2" class="form-control" >
    <option  value="0">0</option>
    <option  value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    </select>
    Младенцев
    <select name="value3" class="form-control" >
     <option  value="0">0</option>
    <option  value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    </select>
   
       <input type="submit" value="Поиск" name="submit" class="btn btn-lg btn-primary btn-block"/>
    </form>
         </div>
</body> 
</html> 
