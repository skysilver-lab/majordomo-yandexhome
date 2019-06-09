<?php

require_once ('server.php');

$request = OAuth2\Request::createFromGlobals();

// Пишем в лог входящий запрос.
$yandexhome->WriteLog($yandexhome->IncomingRequestFormat($request));

// Обработка запроса на токен доступа OAuth2.0 и отправка ответа клиенту.
$server->handleTokenRequest($request)->send();

// Пишем в лог ответ.
$yandexhome->WriteLog('token.php >>> ' . $server->getResponse());
