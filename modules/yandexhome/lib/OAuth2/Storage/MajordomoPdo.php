<?php

namespace OAuth2\Storage;

use InvalidArgumentException;

class MajordomoPdo implements
   AuthorizationCodeInterface,
   AccessTokenInterface,
   ClientCredentialsInterface,
   RefreshTokenInterface
{
   /**
    * @var \PDO
    */
   protected $db;

   /**
    * @var array
    */
   protected $config;

   /**
    * @param mixed $connection
    * @param array $config
    *
    * @throws InvalidArgumentException
    */
   public function __construct($connection, $config = array())
   {
      if (!$connection instanceof \PDO) {
         if (is_string($connection)) {
             $connection = array('dsn' => $connection);
         }
         if (!is_array($connection)) {
             throw new \InvalidArgumentException('First argument to OAuth2\Storage\Pdo must be an instance of PDO, a DSN string, or a configuration array');
         }
         if (!isset($connection['dsn'])) {
             throw new \InvalidArgumentException('configuration array must contain "dsn"');
         }
         // merge optional parameters
         $connection = array_merge(array(
             'username' => null,
             'password' => null,
             'options' => array(),
         ), $connection);
         $connection = new \PDO($connection['dsn'], $connection['username'], $connection['password'], $connection['options']);
      }

      $this->db = $connection;

      // debugging
      $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

      $this->config = array_merge(array(
            'client_table' => 'yandexhome_oauth',
            'access_token_table' => 'yandexhome_oauth',
            'refresh_token_table' => 'yandexhome_oauth',
            'code_table' => 'yandexhome_oauth',
            'user_table' => 'oauth_users',
            'jwt_table'  => 'oauth_jwt',
            'jti_table'  => 'oauth_jti',
            'scope_table'  => 'oauth_scopes',
            'public_key_table'  => 'oauth_public_keys',
      ), $config);
   }

   /**
    * @param string $client_id
    * @param null|string $client_secret
    * @return bool
    */
   public function checkClientCredentials($client_id, $client_secret = null)
   {
      //writeLog(date('H:i:s') . " PDO Storage === checkClientCredentials ($client_id, $client_secret)" . PHP_EOL);

      $stmt = $this->db->prepare(sprintf('SELECT * from %s where client_id = :client_id', $this->config['client_table']));
      $stmt->execute(compact('client_id'));
      $result = $stmt->fetch(\PDO::FETCH_ASSOC);

      //writeLog(date('H:i:s') . " PDO Storage === checkClientCredentials () CLIENT_SECRET=" . $result['CLIENT_SECRET'] . PHP_EOL);
      ////writeLog(date('H:i:s') . " PDO Storage === checkClientCredentials () return=" . json_encode($result && $result['CLIENT_SECRET'] == $client_secret) . PHP_EOL);

      return $result && $result['CLIENT_SECRET'] == $client_secret;
   }

   /**
    * @param string $client_id
    * @return array|mixed
    */
   public function getClientDetails($client_id)
   {
      //writeLog(date('H:i:s') . " PDO Storage === getClientDetails ($client_id) " . PHP_EOL);

      $stmt = $this->db->prepare(sprintf('SELECT client_id,client_secret from %s where client_id = :client_id', $this->config['client_table']));
      $stmt->execute(compact('client_id'));
      $res = $stmt->fetch(\PDO::FETCH_ASSOC);

      //writeLog(date('H:i:s') . " PDO Storage === getClientDetails () return " . json_encode($res) . PHP_EOL);

      return $res;
   }

   /**
    * @param $client_id
    * @param $grant_type
    * @return bool
    */
   public function checkRestrictedGrantType($client_id, $grant_type)
   {
      //writeLog(date('H:i:s') . " PDO Storage === checkRestrictedGrantType ($client_id, $grant_type)" . PHP_EOL);

      $res = $grant_type == 'refresh_token' || $grant_type == 'authorization_code';

      return $grant_type == 'refresh_token' || $grant_type == 'authorization_code';
   }

   /**
    * @param string $access_token
    * @return array|bool|mixed|null
    */
   public function getAccessToken($access_token)
   {
      //writeLog(date('H:i:s') . " PDO Storage === getAccessToken ($access_token)" . PHP_EOL);

      $stmt = $this->db->prepare(sprintf('SELECT client_id,access_token,access_token_expires from %s where access_token = :access_token', $this->config['access_token_table']));

      $token = $stmt->execute(compact('access_token'));

      
      if ($token = $stmt->fetch(\PDO::FETCH_ASSOC)) {
         // convert date string back to timestamp
         $token['expires'] = strtotime($token['access_token_expires']);
      }
      ////writeLog(date('H:i:s') . " PDO Storage === getAccessToken () ACCESS_TOKEN_EXPIRES=".$token['ACCESS_TOKEN_EXPIRES'] . PHP_EOL);
      //writeLog(date('H:i:s') . " PDO Storage === getAccessToken () access_token_expires=".$token['access_token_expires'] . PHP_EOL);
      //writeLog(date('H:i:s') . " PDO Storage === getAccessToken () return=".json_encode($token) . PHP_EOL);

      return $token;
   }

   /**
    * @param string $access_token
    * @param mixed  $client_id
    * @param mixed  $user_id
    * @param int    $expires
    * @param string $scope
    * @return bool
    */
   public function setAccessToken($access_token, $client_id, $user_id, $expires, $scope = null)
   {
      //writeLog(date('H:i:s') . " PDO Storage === setAccessToken ($access_token, $client_id, $user_id, $expires, $scope) " . PHP_EOL);

      // convert expires to datestring
      $expires = date('Y-m-d H:i:s', $expires);

      $stmt = $this->db->prepare(sprintf('SELECT * from %s where client_id = :client_id', $this->config['access_token_table']));

      $res = $stmt->execute(compact('client_id'));

      if ($res = $stmt->fetch(\PDO::FETCH_ASSOC)) {
         $stmt = $this->db->prepare(sprintf('UPDATE %s SET access_token=:access_token, access_token_expires=:expires where client_id = :client_id', $this->config['access_token_table']));
      } else {
           $stmt = $this->db->prepare(sprintf('INSERT INTO %s (access_token, client_id, access_token_expires) VALUES (:access_token, :client_id, :expires)', $this->config['access_token_table']));
      }

      $res = $stmt->execute(compact('access_token', 'client_id', 'expires'));

      //writeLog(date('H:i:s') . " PDO Storage === setAccessToken () return=" . json_encode($res) . PHP_EOL);

      return $res;
   }

   /* OAuth2\Storage\AuthorizationCodeInterface */
   /**
    * @param string $code
    * @return mixed
    */
   public function getAuthorizationCode($code)
   {
      //writeLog(date('H:i:s') . " PDO Storage === getAuthorizationCode ($code) " . PHP_EOL);

      $stmt = $this->db->prepare(sprintf('SELECT authorization_code,authorization_code_expires,redirect_uri,client_id from %s where authorization_code = :code', $this->config['code_table']));

      $code = $stmt->execute(compact('code'));

      if ($code = $stmt->fetch(\PDO::FETCH_ASSOC)) {
         // convert date string back to timestamp
         $code['expires'] = strtotime($code['authorization_code_expires']);
      }

      //writeLog(date('H:i:s') . " PDO Storage === getAuthorizationCode () return=" . json_encode($code) . PHP_EOL);

      return $code;
   }

   /**
    * @param string $code
    * @param mixed  $client_id
    * @param mixed  $user_id
    * @param string $redirect_uri
    * @param int    $expires
    * @param string $scope
    * @param string $id_token
    * @return bool|mixed
    */
   public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null, $id_token = null)
   {
      //writeLog(date('H:i:s') . " PDO Storage === setAuthorizationCode ($code, $client_id, $user_id, $redirect_uri, $expires, $scope, $id_token) " . PHP_EOL);

      $expires = date('Y-m-d H:i:s', $expires);

      $stmt = $this->db->prepare(sprintf('SELECT * from %s where client_id = :client_id', $this->config['code_table']));

      $res = $stmt->execute(compact('client_id'));

      if ($res = $stmt->fetch(\PDO::FETCH_ASSOC)) {
         $stmt = $this->db->prepare($sql = sprintf('UPDATE %s SET authorization_code=:code,redirect_uri=:redirect_uri, authorization_code_expires=:expires where client_id=:client_id', $this->config['code_table']));
      } else {
         $stmt = $this->db->prepare(sprintf('INSERT INTO %s (authorization_code, client_id, redirect_uri, authorization_code_expires) VALUES (:code, :client_id, :redirect_uri, :expires)', $this->config['code_table']));
      }

      $res = $stmt->execute(compact('code', 'client_id', 'redirect_uri', 'expires'));

      //writeLog(date('H:i:s') . " PDO Storage === setAuthorizationCode () return=" . json_encode($res) . PHP_EOL);

      return $res;
   }

   /**
    * @param string $code
    * @return bool
    */
   public function expireAuthorizationCode($code)
   {
      //writeLog(date('H:i:s') . " PDO Storage === expireAuthorizationCode ($code) " . PHP_EOL);

      $stmt = $this->db->prepare(sprintf('SELECT authorization_code,authorization_code_expires,redirect_uri from %s where authorization_code = :code', $this->config['code_table']));

      $res = $stmt->execute(compact('code'));

      if ($res = $stmt->fetch(\PDO::FETCH_ASSOC)) {
         $stmt = $this->db->prepare($sql = sprintf('UPDATE %s SET authorization_code=:code_new,redirect_uri=:redirect_uri, authorization_code_expires=:expires where authorization_code = :code', $this->config['code_table']));
         $code_new = null;
         $expires = null;
         $redirect_uri = null;
         $res = $stmt->execute(compact('code', 'code_new', 'redirect_uri', 'expires'));
      }

      //writeLog(date('H:i:s') . " PDO Storage === expireAuthorizationCode () return=" . json_encode($res) . PHP_EOL);

      return $res;
   }

   /**
    * @param string $refresh_token
    * @return bool|mixed
    */
   public function getRefreshToken($refresh_token)
   {
      //writeLog(date('H:i:s') . " PDO Storage === getRefreshToken ($refresh_token)" . PHP_EOL);

      $stmt = $this->db->prepare(sprintf('SELECT refresh_token,refresh_token_expires,client_id FROM %s WHERE refresh_token = :refresh_token', $this->config['refresh_token_table']));

      $token = $stmt->execute(compact('refresh_token'));

      if ($token = $stmt->fetch(\PDO::FETCH_ASSOC)) {
         // convert expires to epoch time
         $token['expires'] = strtotime($token['refresh_token_expires']);
      }

      //writeLog(date('H:i:s') . " PDO Storage === getRefreshToken () return=" . json_encode($token) . PHP_EOL);

      return $token;
   }

   /**
    * @param string $refresh_token
    * @param mixed  $client_id
    * @param mixed  $user_id
    * @param string $expires
    * @param string $scope
    * @return bool
    */
   public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
   {
      //writeLog(date('H:i:s') . " PDO Storage === setRefreshToken ($refresh_token, $client_id, $user_id, $expires, $scope)" . PHP_EOL);

      // convert expires to datestring
      $expires = date('Y-m-d H:i:s', $expires);

      $stmt = $this->db->prepare(sprintf('SELECT * from %s where client_id = :client_id', $this->config['refresh_token_table']));

      $res = $stmt->execute(compact('client_id'));

      if ($res = $stmt->fetch(\PDO::FETCH_ASSOC)) {
         $stmt = $this->db->prepare(sprintf('UPDATE %s SET refresh_token=:refresh_token, refresh_token_expires=:expires where client_id = :client_id', $this->config['refresh_token_table']));
      } else {
         $stmt = $this->db->prepare(sprintf('INSERT INTO %s (refresh_token, client_id, refresh_token_expires) VALUES (:refresh_token, :client_id, :expires)', $this->config['refresh_token_table']));
      }

      $res = $stmt->execute(compact('refresh_token', 'client_id', 'expires'));

      //writeLog(date('H:i:s') . " PDO Storage === setRefreshToken () return=" . json_encode($res) . PHP_EOL);

      return $res;
   }

   /**
    * @param string $refresh_token
    * @return bool
    */
   public function unsetRefreshToken($refresh_token)
   {
      //
      return true;
   }

   /**
    * @param string $client_id
    * @return bool
    */
   public function isPublicClient($client_id)
   {
      //
      return true;
   }

   /**
    * @param mixed $client_id
    * @return bool|null
    */
   public function getClientScope($client_id)
   {
      //
      return null;
   }
}