<?php

require_once ('server.php');

$request = OAuth2\Request::createFromGlobals();

/*
   Перечень API Endpoint URL:
      /smarthome/ - пинги, проверка доступности навыка (по факту).
      /smarthome/v1.0 - пинги, проверка доступности навыка (в теории, по документации).
      /smarthome/v1.0/user/devices/action - выполнить действие с устройством.
      /smarthome/v1.0/user/devices/query  - получить статус устройства.
      /smarthome/v1.0/user/devices - получить список всех устройств и их описание.
      /smarthome/v1.0/user/unlink - событие отвязывания аккаунтов.
      /auth/token - запрос OAuth2 токена и его обновление.
      /auth/authorization - форма авторизации пользователя.
*/

if (!isset($request->server['PATH_INFO'])) {

   // Периодическая проверка доступности Endpoint URL нашего провайдера (PING).

   if ($yandexhome->debug_ping) {
      $yandexhome->WriteLog($yandexhome->IncomingRequestFormat($request));
      $yandexhome->WriteLog('smarthome.php <<< PING');
   }

   if (!empty($request->request)) {

      $content = $request->request;

      // Если действительно команда PING.
      if ($content['request']['command'] == 'ping' || $content['request']['original_utterance'] == 'ping') {
         // Отвечаем PONG (HTTP/1.1 200 OK).
         $response = json_encode([
            'version' => API_VERSION,
            'session' => [
               'session_id' => $content['session']['session_id'],
               'message_id' => $content['session']['message_id'],
               'user_id'    => $content['session']['user_id']
            ],
            'response' => [
               'text' => 'pong'
            ]
         ]);

         header('HTTP/1.1 200 OK');
         header('Content-Type: application/json');
         echo $response;

         if ($yandexhome->debug_ping) {
            $yandexhome->WriteLog('smarthome.php >>> PONG');
            $yandexhome->WriteLog('smarthome.php >>> ' . $response);
         }
      }
   }
   header('HTTP/1.1 400 Bad Request');
   die;
} else if (isset($request->server['PATH_INFO']) && $request->server['PATH_INFO'] != '') {

   // Входящий API-запрос на Endpoint URL нашего провайдера.

   $yandexhome->WriteLog($yandexhome->IncomingRequestFormat($request));

   // Обработка запроса к нашему API и аутентификация токена доступа.
   if (!$server->verifyResourceRequest($request)) {

      // Если запрос не аутентифицирован, отвечаем 401 Unauthorized и завершаем работу.

      $server->getResponse()->send();

      $yandexhome->WriteLog('smarthome.php >>> ' . $server->getResponse());

      echo json_encode(array('success' => false, 'message' => 'Access is denied.'));

      $yandexhome->WriteLog('smarthome.php >>> ' . json_encode(array('success' => false, 'message' => 'Access is denied.')));

      die;

   } else {

      // Если аутентификация успешна, то обрабатываем запрос.

      $content = $request->request;
      $content['request_id'] = $request->headers['X_REQUEST_ID'];
      $path = $request->server['PATH_INFO'];
      $response = '';

      $api_query = explode('/', trim($path, '/'));

      // Если верные версия API и формат запроса.
      if (array_shift($api_query) == 'v'.API_VERSION && array_shift($api_query) == 'user') {
         if ($api_query[0] == 'devices') {
            if (isset($api_query[1])) {
               if ($api_query[1] == 'query') {
                  // Обработка запроса информации о состоянии устройств.
                  $response = $yandexhome->HandleQueryRequest($content);
               } else if ($api_query[1] == 'action') {
                  // Обработка запроса на изменение состояния устройств.
                  $response = $yandexhome->HandleExecuteRequest($content);
               }
            } else {
               // Обработка запроса информации о перечне устройств.
               $response = $yandexhome->HandleSyncRequest($content);
            }
         } else if ($api_query[0] == 'unlink') {
            // Обработка запроса на разъединение аккаунтов.
            $response = $yandexhome->HandleUnlinkRequest($content);
         } else {
            $yandexhome->WriteLog('smarthome.php === Unsupported API command!');
            header('HTTP/1.1 400 Bad Request');
         }
      } else {
         $yandexhome->WriteLog('smarthome.php === Unsupported API version!');
         header('HTTP/1.1 400 Bad Request');
      }

      header('HTTP/1.1 200 OK');
      header('Content-Type: application/json');
      echo $response;

      $yandexhome->WriteLog('smarthome.php >>> ' . $response);

      die;
   }
} else {
   header('HTTP/1.1 400 Bad Request');
}
