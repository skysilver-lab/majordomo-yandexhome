<?php

require_once ('server.php');

$request = OAuth2\Request::createFromGlobals();

// Пишем в лог входящий запрос.
$yandexhome->WriteLog($yandexhome->IncomingRequestFormat($request));

$response = new OAuth2\Response();

// Проверяем валидность запроса на авторизацию.
if (!$server->validateAuthorizeRequest($request, $response)) {
   // Если не валиден, то отправляем ответ с ошибкой и завершаем работу.
   $response->send();
   $yandexhome->WriteLog('authorize.php >>> ' . $server->getResponse());
   die;
}

$_SESSION['REQUEST_URI'] = $request->server['REQUEST_URI'];

$user_name = $yandexhome->config['USER_NAME'];
$user_pass = $yandexhome->config['USER_PASS'];

if (isset($_SESSION['user']) && $_SESSION['user'] == $user_name) {
   $_SESSION['AUTH'] = true;
} else {
   $_SESSION['AUTH'] = false;
}

$user = $_SESSION['AUTH'] ? $user_name : null;

$message = '';

if (!empty($_SESSION['message'])) {
   $message = $_SESSION['message'];
   unset($_SESSION['message']);
}

if ($_SESSION['AUTH'] && isset($_POST['authorized'])) {
   $is_authorized = ($_POST['authorized'] === 'yes'); // Если yes, то true, иначе false.
   $log = $server->handleAuthorizeRequest($request, $response, $is_authorized);
   $response->send();
   unset($_SESSION['user']);
   session_destroy();
   $yandexhome->WriteLog('authorize.php >>> ' . $log);
} else if (!$_SESSION['AUTH'] && isset($_POST['login']) && isset($_POST['password'])) {
   // Если не авторизованы.
   if(!empty($_POST['login']) && !empty($_POST['password']) && $user_name == $_POST['login']) {
      // Если переданы логин/пароль, и пользователь с таким логином имеется в списке пользователей,
      // то проверим корректность введенного пароля.
      if($user_pass == $_POST['password']) {
         // Пароль совпадает. Инициируем сессию.
         $_SESSION['user'] = $_POST['login'];
         $_SESSION['AUTH'] = true;
         $user = $_SESSION['AUTH'] ? $user_name : null;
      }
   }
   if(!isset($_SESSION['user']) || $_SESSION['user'] != $_POST['login']) {
      // Авторизация не прошла. Сохраним ошибку.
      $message = 'Неверный логин или пароль. Повторите попытку.';
   }
}

?>

<html lang = "ru">

<head>
   <meta charset = "UTF-8">
   <title>MajorDoMo | Connect</title>
   <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
   <script src = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <link rel = "stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel = "stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel = "stylesheet" href="css/style.css">
   <meta name = "viewport" content="width=device-width, initial-scale=1">
</head>

<body class="login-page">

<?php 

   if($_SESSION['AUTH']) { //Если авторизованы ?>

   <main>
      <div class="login-block">
         <div><img src="img/ylogo.png" alt="logo" width="100%"></div>
         <h1>Здравствуйте, <?php echo $user; ?>!</h1>
         <form method = "post">
            <div>
               Приложение <b>Yandex Home</b> запрашивает доступ к вашему аккаунту <b>MajorDoMo</b>, чтобы контролировать привязанные к нему устройства.
            </div>
            <br>
            <div>
               Предоставить доступ для <b>Yandex Home</b>?
            </div>
            <button class="btn btn-primary btn-block" type="submit" name="authorized" value="yes"><b>Предоставить</b></button>
            <br>
            <button class="btn btn-link" type="submit" name="authorized" value="no"><b>Отказать</b></button>
         </form>
         <h6>OAuth2 Client ID: <?php echo $request->query['client_id']; ?></h6>
      </div>
   </main>
   
   
<?php } else { //Если не авторизованы ?>

   <main>
      <div class="login-block">
         <img src="img/mlogo.png" alt="logo">
         <h1>Вход в аккаунт MajorDoMo</h1>
         <form method = "post">
            <div class="form-group">
               <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user ti-user"></i></span>
                  <input type="text" name = "login" class="form-control" placeholder="Логин">
               </div>
            </div>
            <hr class="hr-xs">
            <div class="form-group">
               <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-lock ti-unlock"></i></span>
                  <input type="password" name = "password" class="form-control" placeholder="Пароль">
               </div>
            </div>
            <?php if (!empty($message)) { ?>
               <p><?php echo $message; ?></p>
            <?php } ?>
            <button class="btn btn-primary btn-block" type="submit"><b>Войти</b></button>
         </form>
      </div>
   </main>

<?php } ?>

</body>
</html>
