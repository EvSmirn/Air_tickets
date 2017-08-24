<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel='stylesheet prefetch' href='http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css'>
        <link rel="stylesheet" href="css/style_index.css">
        <title>Главная страница</title>
    </head>
    <body>
        
     <div class="wrapper">
         <form class="form-signin" action="ticket.php" method="post">       
      <h2 class="form-signin-heading">Войдите</h2>
      
      <input type="text" class="form-control" name="login" placeholder="Login" required="" autofocus="" />
      <input type="password" class="form-control" name="password" placeholder="Password" required=""/>  
      <input type="submit" name="submit" value="Войти" class="btn btn-lg btn-primary btn-block"/>
      <a href="reg.php" class="btn btn-lg btn-primary btn-block">Зарегистрироваться</a>  
    </form>
  </div>
        
    </body>
</html>
