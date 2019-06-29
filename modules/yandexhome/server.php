<?php

chdir (dirname (__FILE__) . '/../../');

include_once ('./config.php');
include_once ('./lib/loader.php');

$timezone = SQLSelectOne('SELECT NAME, VALUE FROM settings WHERE NAME="SITE_TIMEZONE"')['VALUE'];

if ($timezone != null && $timezone != '') {
   date_default_timezone_set ($timezone);
} else {
   date_default_timezone_set ('Europe/Moscow');
}

include_once (DIR_MODULES . '/yandexhome/yandexhome.class.php');

$yandexhome = new yandexhome();

session_start ();

$dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;

require_once (DIR_MODULES . '/yandexhome/lib/OAuth2/Autoloader.php');

OAuth2\Autoloader::register();

$storage = new OAuth2\Storage\MajordomoPdo(array('dsn' => $dsn, 'username' => DB_USER, 'password' => DB_PASSWORD));

$server = new OAuth2\Server($storage, array('access_lifetime' => 7*24*3600));

$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

$server->addGrantType(new OAuth2\GrantType\RefreshToken($storage, array('always_issue_new_refresh_token' => true)));
