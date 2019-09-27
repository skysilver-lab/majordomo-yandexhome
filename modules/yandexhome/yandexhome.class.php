<?php
/**
* Главный класс модуля Yandex Home
* @author <skysilver.da@gmail.com>
* @copyright 2019 Agaphonov Dmitri aka skysilver <skysilver.da@gmail.com> (c)
* @version 0.7b 2019/09/17
*/

const PREFIX_CAPABILITIES = 'devices.capabilities.';
const PREFIX_TYPES = 'devices.types.';
const API_VERSION = '1.0';

class yandexhome extends module
{
   /**
   *
   * Конструктор класса модуля.
   *
   */
   function __construct()
   {
      $this->name = 'yandexhome';
      $this->title = 'Yandex Home';
      $this->module_category = '<#LANG_SECTION_DEVICES#>';
      $this->checkInstalled();

      $this->getConfig();
      $this->debug = ($this->config['LOG_DEBMES'] == 1) ? true : false;
      $this->debug_ping = ($this->config['LOG_PING'] == 1) ? true : false;

      require ('structure.inc.php');
   }

   /**
   *
   * Сохранение параметров модуля.
   *
   */
   function saveParams($data = 1)
   {
      $p = array();

      if (isset($this->id)) {
         $p['id'] = $this->id;
      }

      if (isset($this->view_mode)) {
         $p['view_mode'] = $this->view_mode;
      }

      if (isset($this->edit_mode)) {
         $p['edit_mode'] = $this->edit_mode;
      }

      if (isset($this->tab)) {
         $p['tab'] = $this->tab;
      }

      return parent::saveParams($p);
   }

   /**
   *
   * Получение параметров модуля.
   *
   */
   function getParams()
   {
      global $id;
      global $mode;
      global $view_mode;
      global $edit_mode;
      global $tab;

      if (isset($id)) {
         $this->id = $id;
      }

      if (isset($mode)) {
         $this->mode = $mode;
      }

      if (isset($view_mode)) {
         $this->view_mode = $view_mode;
      }

      if (isset($edit_mode)) {
         $this->edit_mode = $edit_mode;
      }

      if (isset($tab)) {
         $this->tab = $tab;
      }
   }

   /**
   *
   * Запуск модуля.
   *
   */
   function run()
   {
      global $session;

      $out = array();

      if ($this->action == 'admin') {
         $this->admin($out);
      } else {
         $this->usual($out);
      }

      if (isset($this->owner->action)) {
         $out['PARENT_ACTION'] = $this->owner->action;
      }

      if (isset($this->owner->name)) {
         $out['PARENT_NAME'] = $this->owner->name;
      }

      $out['VIEW_MODE'] = $this->view_mode;
      $out['EDIT_MODE'] = $this->edit_mode;
      $out['ACTION'] = $this->action;
      $out['MODE'] = $this->mode;
      $out['TAB'] = $this->tab;

      $this->data = $out;

      $p = new parser(DIR_TEMPLATES . $this->name . '/' . $this->name . '.html', $this->data, $this);
      $this->result = $p->result;
   }

   /**
   *
   * Админка модуля.
   *
   */
   function admin(&$out)
   {
      $this->getConfig();

      $out['USER_NAME']  =  $this->config['USER_NAME'];
      $out['USER_PASS']  =  $this->config['USER_PASS'];
      $out['CLIENT_ID']  =  $this->config['CLIENT_ID'];
      $out['CLIENT_KEY'] =  $this->config['CLIENT_KEY'];
      $out['LOG_DEBMES'] =  $this->config['LOG_DEBMES'];
      $out['LOG_PING']   =  $this->config['LOG_PING'];
      $out['VIEW_STYLE'] =  $this->config['VIEW_STYLE'];
      $out['READONLY_MODE'] =  $this->config['READONLY_MODE'];
      $out['USER_ID']    =  md5($this->config['USER_NAME']);

      if ($this->view_mode == 'update_settings') {
         $this->config['USER_NAME']  = gr('user_name');
         $this->config['USER_PASS']  = gr('user_pass');
         $this->config['CLIENT_ID']  = gr('client_id');
         $this->config['CLIENT_KEY'] = gr('client_key');
         $this->config['LOG_DEBMES'] = gr('log_debmes');
         $this->config['LOG_PING']   = gr('log_ping');
         $this->config['VIEW_STYLE'] = gr('view_style');
         $this->config['READONLY_MODE'] = gr('readonly_mode');

         $this->saveConfig();

         $res = SQLSelectOne('SELECT * FROM yandexhome_oauth');

         if ($res['CLIENT_ID']) {
            $res['CLIENT_ID'] = $this->config['CLIENT_ID'];
            $res['CLIENT_SECRET'] = $this->config['CLIENT_KEY'];
            SQLUpdate('yandexhome_oauth', $res);
         } else {
            $res = array();
            $res['CLIENT_ID'] = $this->config['CLIENT_ID'];
            $res['CLIENT_SECRET'] = $this->config['CLIENT_KEY'];
            SQLInsert('yandexhome_oauth', $res);
         }

         $this->redirect('?');
      }

      if ($this->view_mode == '' || $this->view_mode == 'search_yandexhome_devices') {
         $oauth = SQLSelectOne('SELECT * FROM yandexhome_oauth');
         if ($oauth['CLIENT_ID']) {
            $out['ACCESS_TOKEN'] = $oauth['ACCESS_TOKEN'];
            $out['ACCESS_TOKEN_EXPIRES'] = $oauth['ACCESS_TOKEN_EXPIRES'];
            $out['REFRESH_TOKEN'] = $oauth['REFRESH_TOKEN'];
            $out['REFRESH_TOKEN_EXPIRES'] = $oauth['REFRESH_TOKEN_EXPIRES'];
            $out['AUTHORIZATION_CODE'] = $oauth['AUTHORIZATION_CODE'];
            $out['AUTHORIZATION_CODE_EXPIRES'] = $oauth['AUTHORIZATION_CODE_EXPIRES'];
            $out['REDIRECT_URI'] = $oauth['REDIRECT_URI'];
         }
         $this->search_yandexhome_devices($out);
      }

      if ($this->view_mode == 'addnew_yandexhome_devices') {
         $this->addnew_yandexhome_devices($out);
      }

      if ($this->view_mode == 'edit_yandexhome_devices') {
         $this->edit_yandexhome_devices($out, $this->id);
      }

      if ($this->view_mode == 'delete_yandexhome_devices') {
         $this->delete_yandexhome_devices($this->id);
         $this->redirect('?');
      }
   }

   /**
   *
   * Фронтенд модуля (http api).
   *
   */
   function usual(&$out)
   {
      if ($this->ajax) {

         $op = gr('op');

         if ($op == 'generateClientId') {
            $client_id = sprintf('%04X%04X%04X-%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151));
            $this->WriteLog("Generate new Client ID {$client_id}");
            exit (strtolower($client_id));
         } else if ($op == 'generateClientKey') {
            $client_key = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
            $this->WriteLog("Generate new Client KEY {$client_key}");
            exit (strtolower($client_key));
         } else if ($op == 'sendSyncRequest') {
            // TODO
            exit ('OK');
         }

         echo 'OK';
      }
   }

   /**
   *
   * Список устройств на главной странице модуля.
   *
   */
   function search_yandexhome_devices(&$out)
   {
      $res = SQLSelect("SELECT * FROM yandexhome_devices ORDER BY ROOM,TITLE");
      $loc_title = '';
      if ($res[0]['ID']) {
         $total = count($res);
         for($i = 0; $i < $total; $i++) {
            $res[$i]['ICON'] = strtolower($res[$i]['TYPE']);
            $res[$i]['TYPE_TITLE'] = $this->devices_type[$res[$i]['TYPE']]['description'];
            $res[$i]['VIEW_STYLE'] = $this->config['VIEW_STYLE'];
            if ($res[$i]['ROOM'] != $loc_title) {
               $res[$i]['NEW_ROOM'] = 1;
               $loc_title = $res[$i]['ROOM'];
            }
            $res[$i]['LAST_DEV'] = 0;
            if (isset($res[$i]['NEW_ROOM'])) {
               if ($i == $total-1) {
                  $res[$i]['LAST_DEV'] = 1;
               }
               if ($i > 0) {
                  $res[$i-1]['LAST_DEV'] = 1;
               }
            } else if (!isset($res[$i]['NEW_ROOM']) && ($i == $total-1)) {
               $res[$i]['LAST_DEV'] = 1;
            }
            $traits = json_decode($res[$i]['TRAITS'], true);
            if (is_array($traits) && count($traits) > 0) {
               foreach ($traits as $trait) {
                  $res[$i]['TRAITS_LIST'] .= $trait['type'] . '<br>';
               }
            }
         }
         $out['RESULT'] = $res;
      }
   }

   /**
   *
   * Интерфейс добавления устройства.
   *
   */
   function addnew_yandexhome_devices(&$out)
   {
      $out['DEVICES_TYPE'] = array_values($this->devices_type);
      $out['LOCATIONS'] = SQLSelect('SELECT ID, TITLE FROM locations ORDER BY TITLE');

      if ($this->mode == 'addnew') {
         $ok = 1;

         $rec['TITLE'] = gr('title');
         if ($rec['TITLE'] == '') {
            $out['ERR_TITLE'] = 1;
            $ok = 0;
         }

         $rec['TYPE'] = gr('type');
         if ($rec['TYPE'] == '') {
            $out['ERR_TYPE'] = 1;
            $ok = 0;
         }

         $rec['ROOM'] = gr('location');

         if ($ok) {
            $new_rec = 1;
            $rec['ID'] = SQLInsert('yandexhome_devices', $rec);
            $out['OK'] = 1;
         } else {
            $out['ERR'] = 1;
         }

         if (is_array($rec)) {
            foreach($rec as $k=>$v) {
               if (!is_array($v)) {
                  $rec[$k] = htmlspecialchars($v);
               }
            }
         }

         if ($ok) {
            $this->redirect('?');
         }
      }
      outHash($rec, $out);
   }

   /**
   *
   * Интерфейс редактирования настроек устройства и просмотра его данных.
   *
   */
   function edit_yandexhome_devices(&$out, $id)
   {
      // ID, TITLE, TYPE, ROOM, TRAITS (json), CONFIG (json)
      $rec = SQLSelectOne("SELECT * FROM yandexhome_devices WHERE ID='{$id}'");

      // Поддерживаемые типы устройств.
      $out['DEVICES_TYPE'] = array_values($this->devices_type);

      // Поддерживаемые метрики (возможности) устройств.
      $out['DEVICES_INSTANCE'] = array_values($this->devices_instance);
      $out['DEVICES_INSTANCE_JSON'] = json_encode($this->devices_instance, JSON_UNESCAPED_UNICODE);

      // Список местоположений (комнат) в системе.
      $out['LOCATIONS'] = SQLSelect('SELECT ID, TITLE FROM locations ORDER BY TITLE');

      // Список объектов в системе.
      $objs = SQLSelect('SELECT TITLE, DESCRIPTION FROM objects ORDER BY TITLE'); // CLASS_ID
      $out['OBJECTS'] = json_encode($objs, JSON_UNESCAPED_UNICODE);

      // Сохранение конфигурации устройства.
      if ($this->mode == 'update') {

         $ok = 1;

         // Название устройства (обязательное поле).
         $rec['TITLE'] = gr('title');
         if ($rec['TITLE'] == '') {
            $out['ERR_TITLE'] = 1;
            $ok = 0;
         }

         // Тип устройства (обязательное поле).
         $rec['TYPE'] = gr('type');
         if ($rec['TYPE'] == '') {
            $out['ERR_TYPE'] = 1;
            $ok = 0;
         }

         // Местоположение устройства (опционально).
         $rec['ROOM'] = gr('location');

         // Описание устройства (опционально).
         $rec['DESCRIPTION'] = gr('description');

         // Производитель устройства (опционально).
         $rec['MANUFACTURER'] = gr('manufacturer');

         // Модель устройства (опционально).
         $rec['MODEL'] = gr('model');

         // Версия ПО устройства (опционально).
         $rec['SW_VERSION'] = gr('sw_version');

         // Версия АО устройства (опционально).
         $rec['HW_VERSION'] = gr('hw_version');

         // Метрики (в т.ч. привязанные к ним объекты и свойства) устройства (обязательное поле).
         // Старые (в формате массива).
         $old_dev_traits = json_decode($rec['TRAITS'], true);
         // Новые (в формате json).
         $rec['TRAITS'] = gr('traits_json');
         // Новые (массив).
         $new_dev_traits = json_decode($rec['TRAITS'], true);
         if ($rec['TRAITS'] == '' || count($new_dev_traits) == 0) {
            $out['ERR_TRAITS'] = 1;
            $ok = 0;
         }

         // Конфигурация умений.
         $devices_instance = json_decode(gr('instance_json'), true);

         // Если обязательные поля заполнены, то сохраняем конфигурацию устройства.
         if ($ok) {
            if ($rec['ID']) {
               // Собираем JSON-конфиг устройства согласно формату API Yandex Home.
               $traits = [];
               if (is_array($new_dev_traits)) {
                  foreach ($new_dev_traits as $trait) {
                     $parameters = [];
                     $trait_type = PREFIX_CAPABILITIES . $this->devices_instance[$trait['type']]['capability'];
                     if (isset($devices_instance[$trait['type']]['parameters'])) {
                        $parameters = $devices_instance[$trait['type']]['parameters'];
                        if ($trait['type'] != 'rgb' && $trait['type'] != 'temperature_k') {
                           $parameters['instance'] = $trait['type'];
                        }
                     } else {
                        $parameters['instance'] = $trait['type'];
                     }
                     $check = false;
                     foreach ($traits as $key => $item) {
                        if ($item['type'] == $trait_type) {
                           $check = $key;
                           break;
                        }
                     }
                     if ($check && $trait_type == PREFIX_CAPABILITIES.'color_setting') {
                        $traits[$check]['parameters'] = array_merge ($traits[$check]['parameters'], $parameters);
                     } else {
                        if (isset($this->devices_instance[$trait['type']]['retrievable'])) {
                           $retrievable = $this->devices_instance[$trait['type']]['retrievable'];
                        } else {
                           $retrievable = true;
                        }
                        $traits[] = [
                           'type' => $trait_type,
                           'parameters' => $parameters,
                           'retrievable' => $retrievable
                        ];
                     }
                  }
               }
               $rec['CONFIG'] =  json_encode([
                                       'id' => $rec['ID'],
                                       'name' => $rec['TITLE'],
                                       'type' => PREFIX_TYPES . $rec['TYPE'],
                                       'room' => $rec['ROOM'],
                                       'description' => $rec['DESCRIPTION'],
                                       'capabilities' => $traits,
                                       'device_info' => [
                                          'manufacturer' => $rec['MANUFACTURER'],
                                          'model' => $rec['MODEL'] . ' via MajorDoMo',
                                          'hw_version' => $rec['HW_VERSION'],
                                          'sw_version' => $rec['SW_VERSION']
                                       ]
                                 ], JSON_UNESCAPED_UNICODE);

               // Обрабатываем набор метрик и привязанные к ним объекты и свойства.
               if (is_array($new_dev_traits)) {
                  if (is_array($old_dev_traits)) {
                     // Если удалили метрику, у которой были привязанные объект и свойство, то удаляем линк.
                     $del_dev_traits = array_diff_assoc($old_dev_traits, $new_dev_traits);
                     if (!empty($del_dev_traits)) {
                        foreach ($del_dev_traits as $trait) {
                           $linked_object = $trait['linked_object'];
                           $linked_property = $trait['linked_property'];
                           if ($linked_object != '' && $linked_property != '') {
                              removeLinkedProperty($linked_object, $linked_property, $this->name);
                              $this->WriteLog("removeLinkedProperty for $linked_object and $linked_property");
                           }
                        }
                     }
                  }

                  foreach ($new_dev_traits as $trait) {
                     // Новые объект и свойство метрики.
                     $linked_object = $trait['linked_object'];
                     $linked_property = $trait['linked_property'];

                     // Предыдущие объект и свойство метрики.
                     if (isset($old_dev_traits[$trait['type']])) {
                        $old_linked_object = $old_dev_traits[$trait['type']]['linked_object'];
                        $old_linked_property = $old_dev_traits[$trait['type']]['linked_property'];
                     } else {
                        $old_linked_object = '';
                        $old_linked_property = '';
                     }

                     // Если юзер удалил привязанное свойство, но забыл про объект, то очищаем его.
                     if ($linked_object != '' && $linked_property == '') {
                        $linked_object = '';
                        $new_dev_traits[$trait['type']]['linked_object'] = '';
                     }

                     // Если юзер удалил только привязанный объект, то свойство тоже очищаем.
                     if ($linked_object == '' && $linked_property != '') {
                        $linked_property = '';
                        $new_dev_traits[$trait['type']]['linked_property'] = '';
                     }

                     // Если предыдущие привязанные объект и свойство не пустые и не совпадают с новыми, то удаляем линк.
                     if ($old_linked_object !='' && $old_linked_property != '' && ($linked_object.$linked_property != $old_linked_object.$old_linked_property)) {
                        removeLinkedProperty($old_linked_object, $old_linked_property, $this->name);
                        $this->WriteLog("removeLinkedProperty for $old_linked_object and $old_linked_property");
                     }
                     
                     // Если поля привязанного объекта и свойства не пустые  и не совпадают с предыдущими, то проставляем линк.
                     if ($linked_object != '' && $linked_property != '' && ($linked_object.$linked_property != $old_linked_object.$old_linked_property)) {
                        addLinkedProperty($linked_object, $linked_property, $this->name);
                        $this->WriteLog("addLinkedProperty for $linked_object and $linked_property");
                     }
                  }
                  $rec['TRAITS'] = json_encode($new_dev_traits, JSON_UNESCAPED_UNICODE);
               }
               // Обновляем запись об устройстве в БД.
               SQLUpdate('yandexhome_devices', $rec);
            }
            $out['OK'] = 1;
         } else {
            $out['ERR'] = 1;
         }
      }
      outHash($rec, $out);
   }

   /**
   *
   * Удаление устройства из модуля.
   *
   */
   function delete_yandexhome_devices($id)
   {
      $this->DeleteLinkedProperties($id);

      SQLExec("DELETE FROM yandexhome_devices WHERE ID='{$id}'");
   }

   /**
   *
   * Обработка событий смены значений привязанных к метрикам свойств объектов.
   *
   */
   function PropertySetHandle($object, $property, $value)
   {
      // TODO: отправка статусов в Yandex по факту изменения свойства объекта (если появится поддержка в API).

      $this->WriteLog("PropertySetHandle for object '$object' and property '$property' and value=$value");
   }

   /**
   *
   * Обработка запроса информации о перечне устройств.
   *
   */
   function HandleSyncRequest($content)
   {
      $this->WriteLog('Incoming sync request');

      $res = SQLSelect("SELECT CONFIG FROM yandexhome_devices WHERE CONFIG!='' ORDER BY ID");

      foreach ($res as $device) {
         $devices[] = json_decode($device['CONFIG']);
      }
      
      $response = json_encode([
         'request_id' => $content['request_id'],
         'payload' => [
            'user_id' => md5($this->config['USER_NAME']),
            'devices' => $devices
         ]
      ], JSON_UNESCAPED_UNICODE);

      return $response;
   }

   /**
   *
   * Обработка запроса информации о состоянии устройств.
   *
   */
   function HandleQueryRequest($content)
   {
      $devices = [];

      foreach ($content['devices'] as $device) {
         $device_id = $device['id'];

         $this->WriteLog("Incoming query request for device ID$device_id");

         $rec = SQLSelectOne("SELECT * FROM yandexhome_devices WHERE ID='{$device_id}'");

         if (is_array($rec) && !empty($rec['TRAITS'])) {
            $capabilities = [];
            $traits = json_decode($rec['TRAITS'], true);
            if (is_array($traits)) {
               foreach ($traits as $trait) {
                  $state['instance'] = $trait['type'];
                  if ($trait['linked_object'] != '' && $trait['linked_property'] != '') {
                     $linked_object = $trait['linked_object'];
                     $linked_property = $trait['linked_property'];
                     $value = getGlobal("$linked_object.$linked_property");
                     $this->WriteLog("Object '$linked_object', property '$linked_property', get value=$value");
                  } else {
                     $value = $this->devices_instance[$trait['type']]['default_value'];
                     $this->WriteLog('Linked object and property not defined');
                  }
                  switch ($trait['type']) {
                     case 'on':
                        $state['value'] = $value ? true : false;
                        break;
                     case 'brightness':
                        $state['value'] = (int)$value;
                        break;
                     case 'rgb':
                        $value = preg_replace('/^#/', '', $value);
                        $state['value'] = hexdec($value);
                        break;
                     case 'temperature_k':
                        $state['value'] = (int)$value;
                        break;
                     case 'channel':
                        $state['value'] = (int)$value;
                        break;
                     case 'temperature':
                        $state['value'] = (int)$value;
                        break;
                     case 'volume':
                        $state['value'] = (int)$value;
                        break;
                     case 'mute':
                        $state['value'] = $value ? true : false;
                        break;
                     default:
                        $state['value'] = $value;
                        break;
                  }
                  $capabilities[] = [
                     'type' => PREFIX_CAPABILITIES . $this->devices_instance[$trait['type']]['capability'],
                     'state' => $state
                  ];
               }
               $devices[] = [
                  'id' => $device_id,
                  'capabilities' => $capabilities
               ];
            }
         } else {
            $devices[] = [
               'id' => $device_id,
               'error_code' => 'DEVICE_NOT_FOUND',
               'error_message' => 'DEVICE_NOT_FOUND_MSG',
            ];
         }
      }

      $response = json_encode([
                  'request_id' => $content['request_id'],
                  'payload' => [
                     'devices' => $devices
                  ]
               ], JSON_UNESCAPED_UNICODE);

      return $response;
   }

   /**
   *
   * Обработка запросов на управление устройствами.
   *
   */
   function HandleExecuteRequest($content)
   {
      $devices = [];

      foreach ($content['payload']['devices'] as $device) {
         $device_id = $device['id'];

         $this->WriteLog("Incoming action request for device ID$device_id");

         $rec = SQLSelectOne("SELECT * FROM yandexhome_devices WHERE ID='{$device_id}'");

         $capabilities = [];
         $state = [];

         foreach ($device['capabilities'] as $capability) {

            $type = $capability['type'];
            $value = $capability['state']['value'];
            $instance = $capability['state']['instance'];

            $this->WriteLog("Capabilities type '$type', instance '$instance', value=" . json_encode($value));

            $linked_object = '';
            $linked_property = '';

            $error_code = false;

            if (!empty($rec['TRAITS'])) {
               $traits = json_decode($rec['TRAITS'], true);
               if (is_array($traits) && isset($traits[$instance]) && $traits[$instance]['linked_object'] != '' && $traits[$instance]['linked_property'] != '') {
                  $linked_object = $traits[$instance]['linked_object'];
                  $linked_property = $traits[$instance]['linked_property'];
                  switch (true) {
                     case ($instance == 'on' || $instance == 'mute') :
                        // Конвертируем true/false в 1/0.
                        $value = ($value === true) ? 1 : 0;
                        break;
                     case ($instance == 'volume' || $instance == 'channel') :
                        if (isset($capability['state']['relative']) && $capability['state']['relative'] === true) {
                           $cur_val = getGlobal("$linked_object.$linked_property");
                           $value = $cur_val + $value;
                           if ($value < 0) $value = 0;
                        }
                        break;
                     case ($instance == 'rgb') :
                        $value = str_pad(dechex($value), 6, '0', STR_PAD_LEFT);
                        break;
                  }
                  if (!$error_code) {
                     if ($this->config['READONLY_MODE'] != 1) {
                        setGlobal("$linked_object.$linked_property", $value, array($this->name => '0'));
                        $this->WriteLog("Object '$linked_object', property '$linked_property', set value=$value");
                     } else {
                        $this->WriteLog('The property of the object has not been set. The module is in read-only mode.');
                        $error_code = 'NOT_SUPPORTED_IN_CURRENT_MODE';
                        $error_message = 'The device is not controlled in this mode. The module is in read-only mode.';
                     }
                  }
               } else {
                  $error_code = 'INVALID_ACTION';
                  $error_message = 'INVALID_ACTION_MSG';
               }
            } else {
               $error_code = 'INVALID_ACTION';
               $error_message = 'INVALID_ACTION_MSG';
            }

            $state['instance'] = $instance;

            if (!$error_code) {
               $state['action_result'] = ['status' => 'DONE'];
            } else {
               $state['action_result'] = [
                  'status' => 'ERROR',
                  'error_code' => $error_code,
                  'error_message' => $error_message
               ];
            }

            $capabilities[] = [
               'type' => $type,
               'state' => $state
            ];
         }

         $devices[] = [
            'id' => $device_id,
            'capabilities' => $capabilities
         ];
      }

      $response = json_encode([
                  'request_id' => $content['request_id'],
                  'payload' => [
                     'devices' => $devices
                  ]
               ], JSON_UNESCAPED_UNICODE);

      return $response;
   }

   /**
   *
   * Обработка запроса на разъединение аккаунтов.
   *
   */
   function HandleUnlinkRequest($content)
   {
      $this->WriteLog('Incoming unlink request');
   }

   /**
   *
   * Удаление всех линков на привязанные к метрикам устройства свойства.
   *
   */
   function DeleteLinkedProperties($id, $properties = false)
   {
      if (!$properties) {
         $properties = SQLSelectOne("SELECT TRAITS FROM yandexhome_devices WHERE ID='{$id}'");
         $properties = json_decode($properties['TRAITS'], true);
      }

      if (is_array($properties) && !empty($properties)) {
         foreach ($properties as $prop) {
            $linked_object = $prop['linked_object'];
            $linked_property = $prop['linked_property'];
            if ($linked_object != '' && $linked_property != '') {
               removeLinkedProperty($linked_object, $linked_property, $this->name);
               $this->WriteLog("removeLinkedProperty for $linked_object and $linked_property");
            }
         }
      }
   }

   /**
   *
   * Запись отладочной информации в DebMes-лог модуля.
   *
   */
   function WriteLog($msg)
   {
      if ($this->debug) {
         DebMes($msg, $this->name);
      }
   }

   /**
   *
   * Форматирование отладочной информации для лога.
   *
   */
   function IncomingRequestFormat($request)
   {
      $method = $request->server['REQUEST_METHOD'];

      if (isset($request->server['HTTP_X_FORWARDED_FOR'])) {
         $remoteip = $request->server['HTTP_X_FORWARDED_FOR'];
      } else {
         $remoteip = $request->server['REMOTE_ADDR'];
      }

      $script = $request->server['REQUEST_URI'];

      $content = json_encode($request->request);

      $message = "{$method} {$script} {$remoteip} <<< {$content}";

      return $message;
   }

   /**
   *
   * Активация автономного режима.
   *
   */
   function ReadonlyModeEnable()
   {
      $this->getConfig();

      $this->config['READONLY_MODE'] = 1;

      $this->saveConfig();
   }

   /**
   *
   * Деактивация автономного режима.
   *
   */
   function ReadonlyModeDisable()
   {
      $this->getConfig();

      $this->config['READONLY_MODE'] = 0;

      $this->saveConfig();
   }

   /**
   *
   * Процедура установки модуля.
   *
   */
   function install($data = '')
   {
      parent::install();
   }

   /**
   *
   * Процедура удаления модуля.
   *
   */
   function uninstall()
   {
      echo '<br>' . date('H:i:s') . " Uninstall module {$this->name}.<br>";

      // Удалим слинкованные свойства объектов у метрик каждого устройства.
      echo '<br>' . date('H:i:s') . ' Delete linked properties.<br>';
      $devices = SQLSelect("SELECT ID,TRAITS FROM yandexhome_devices");
      if (!empty($devices)) {
         foreach ($devices as $device) {
            $this->DeleteLinkedProperties($device['ID'], json_decode($device['TRAITS'], true));
         }
      }

      // Удаляем таблицы модуля из БД.
      echo date('H:i:s') . ' Delete DB tables.<br>';
      SQLExec('DROP TABLE IF EXISTS yandexhome_devices');
      SQLExec('DROP TABLE IF EXISTS yandexhome_oauth');

      // Удаляем модуль с помощью "родительской" функции ядра.
      echo date('H:i:s') . ' Delete files and remove frome system.<br>';
      parent::uninstall();
   }

   /**
   *
   * Процедура создания таблиц модуля в базе данных.
   *
   */
   function dbInstall($data = '')
   {
      $data = <<<EOD
         yandexhome_devices: ID int(10) unsigned NOT NULL auto_increment
         yandexhome_devices: TITLE varchar(255) NOT NULL DEFAULT ''
         yandexhome_devices: TYPE varchar(100) NOT NULL DEFAULT ''
         yandexhome_devices: ROOM varchar(100) NOT NULL DEFAULT ''
         yandexhome_devices: DESCRIPTION varchar(100) NOT NULL DEFAULT ''
         yandexhome_devices: MANUFACTURER varchar(100) NOT NULL DEFAULT ''
         yandexhome_devices: MODEL varchar(100) NOT NULL DEFAULT ''
         yandexhome_devices: SW_VERSION varchar(100) NOT NULL DEFAULT ''
         yandexhome_devices: HW_VERSION varchar(100) NOT NULL DEFAULT ''
         yandexhome_devices: TRAITS text
         yandexhome_devices: CONFIG text

         yandexhome_oauth: ID int(10) unsigned NOT NULL auto_increment
         yandexhome_oauth: CLIENT_ID varchar(80) NOT NULL
         yandexhome_oauth: CLIENT_SECRET varchar(80) DEFAULT NULL
         yandexhome_oauth: ACCESS_TOKEN varchar(40) DEFAULT NULL
         yandexhome_oauth: ACCESS_TOKEN_EXPIRES timestamp NULL DEFAULT NULL
         yandexhome_oauth: REFRESH_TOKEN varchar(40) DEFAULT NULL
         yandexhome_oauth: REFRESH_TOKEN_EXPIRES timestamp NULL DEFAULT NULL
         yandexhome_oauth: AUTHORIZATION_CODE varchar(40) DEFAULT NULL
         yandexhome_oauth: AUTHORIZATION_CODE_EXPIRES timestamp NULL DEFAULT NULL
         yandexhome_oauth: REDIRECT_URI varchar(2000) DEFAULT NULL
EOD;

      parent::dbInstall($data);
   }

}
