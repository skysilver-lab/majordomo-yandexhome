<?php
/**
* Главный класс модуля Yandex Home
* @author <skysilver.da@gmail.com>
* @copyright 2021 Agaphonov Dmitri aka skysilver <skysilver.da@gmail.com> (c)
* @version 1.4b 2021/02/08
*/

const PREFIX_CAPABILITIES = 'devices.capabilities.';
const PREFIX_PROPERTIES = 'devices.properties.';
const PREFIX_TYPES = 'devices.types.';
const API_VERSION = '1.0';
const YANDEX_JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE;
const FAKE_DEVICE_TITLE_PREFIX = '[YH_FAKE]';
const FAKE_DEVICE_ROOM = '__YH_FAKE__';

class yandexhome extends module
{
   public $devices_type = [];
   public $devices_instance = [];
   public $legacy_instance_aliases = [];

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
      $this->migrateDeviceConfigs();
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
      $out['SKILL_ID'] = isset($this->config['SKILL_ID']) ? $this->config['SKILL_ID'] : '';
      $out['SKILL_ACCESS_TOKEN'] =  $this->config['SKILL_ACCESS_TOKEN'];
      $out['REPORTABLE_CONFIGURED'] = $this->isReportableConfigured() ? 1 : 0;
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
         $this->config['SKILL_ID'] = gr('skill_id');
         $this->config['SKILL_ACCESS_TOKEN'] = gr('skill_access_token');
         $this->config['LOG_DEBMES'] = gr('log_debmes');
         $this->config['LOG_PING']   = gr('log_ping');
         $this->config['VIEW_STYLE'] = gr('view_style');
         $this->config['READONLY_MODE'] = gr('readonly_mode');

         $this->saveConfig();

         $res = SQLSelectOne('SELECT * FROM yandexhome_oauth');

         if (is_array($res) && isset($res['CLIENT_ID']) && $res['CLIENT_ID']) {
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
         if (is_array($oauth) && isset($oauth['CLIENT_ID']) && $oauth['CLIENT_ID']) {
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

      if ($this->view_mode == 'create_fake_devices') {
         $created = $this->createFakeDevicesForIconSync();
         $this->redirect('?fake_created=' . $created);
      }

      if ($this->view_mode == 'delete_fake_devices') {
         $deleted = $this->deleteFakeDevicesForIconSync();
         $this->redirect('?fake_deleted=' . $deleted);
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
      $out['FAKE_CREATED'] = gr('fake_created', 'int');
      $out['FAKE_DELETED'] = gr('fake_deleted', 'int');
      $res = SQLSelect("SELECT * FROM yandexhome_devices ORDER BY ROOM,TITLE");
      $loc_title = '';
      if (is_array($res) && isset($res[0]['ID']) && $res[0]['ID']) {
         $total = count($res);
         for($i = 0; $i < $total; $i++) {
            $res[$i]['ICON'] = strtolower($res[$i]['TYPE']);
            $res[$i]['ICON_WEBP'] = str_replace(array('.', '_'), '-', $res[$i]['ICON']);
            $res[$i]['TYPE_TITLE'] = $this->getDeviceTypeDescription($res[$i]['TYPE']);
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
               $res[$i]['TRAITS_LIST'] = "";
               foreach ($traits as $trait) {
                  $trait_type = isset($trait['type']) ? $trait['type'] : '';
                  $trait_def = $this->getInstanceDefinition($trait_type);
                  $trait_label = $trait_type;
                  if ($trait_def && isset($trait_def['description'])) {
                     $trait_label = $trait_def['description'] . ' (' . $trait_type . ')';
                  }
                  $res[$i]['TRAITS_LIST'] .= $trait_label . '<br>';
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
            $this->sendDiscoveryCallback();
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
      $out['VALUE_MAP_PRESETS_JSON'] = json_encode($this->value_map_presets, JSON_UNESCAPED_UNICODE);

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
         if (is_array($old_dev_traits)) {
            $normalized_old_traits = [];
            foreach ($old_dev_traits as $old_key => $old_trait) {
               if (!is_array($old_trait)) {
                  continue;
               }
               $old_type = isset($old_trait['type']) ? $old_trait['type'] : $old_key;
               $old_type = $this->normalizeTraitType($old_type);
               $old_trait['type'] = $old_type;
               $normalized_old_traits[$old_type] = $old_trait;
            }
            $old_dev_traits = $normalized_old_traits;
         }
         // Новые (в формате json).
         $rec['TRAITS'] = gr('traits_json');
         // Новые (массив).
         $new_dev_traits = json_decode($rec['TRAITS'], true);
         if ($rec['TRAITS'] == '' || !is_array($new_dev_traits) || count($new_dev_traits) == 0) {
            $out['ERR_TRAITS'] = 1;
            $ok = 0;
         }

         // Конфигурация умений.
         $devices_instance = json_decode(gr('instance_json'), true);
         if (!is_array($devices_instance)) {
            $devices_instance = [];
         }
         if (is_array($new_dev_traits)) {
            $normalized_traits = [];
            foreach ($new_dev_traits as $trait_key => $trait_data) {
               if (!is_array($trait_data)) {
                  continue;
               }
               $trait_type = isset($trait_data['type']) ? $trait_data['type'] : $trait_key;
               $trait_type = $this->normalizeTraitType($trait_type);
               $trait_data['type'] = $trait_type;
               if (!isset($trait_data['reportable'])) {
                  $trait_data['reportable'] = false;
               }
               if (!isset($trait_data['description'])) {
                  $instance_def = $this->getInstanceDefinition($trait_type);
                  if ($instance_def && isset($instance_def['description'])) {
                     $trait_data['description'] = $instance_def['description'];
                  }
               }
               if (!isset($trait_data['value_map_preset'])) {
                  $trait_data['value_map_preset'] = ($trait_type === 'on') ? 'bool_onoff_10' : 'none';
               }
               if (!isset($trait_data['value_map']) || !is_array($trait_data['value_map'])) {
                  if ($trait_type === 'on') {
                     $trait_data['value_map'] = ['1' => 'on', '0' => 'off', 'true' => 'on', 'false' => 'off'];
                  } else {
                     $trait_data['value_map'] = [];
                  }
               }
               $normalized_traits[$trait_type] = $trait_data;
            }
            $new_dev_traits = $normalized_traits;
         }

         // Если обязательные поля заполнены, то сохраняем конфигурацию устройства.
         if ($ok) {
            if ($rec['ID']) {
               // Собираем JSON-конфиг устройства согласно актуальному формату API Yandex Home.
               $rec['CONFIG'] = $this->buildDeviceConfig($rec, $new_dev_traits, $devices_instance);
               $config_check = json_decode($rec['CONFIG'], true);
               $this->WriteLog('Device saved: ID=' . $rec['ID'] . '; TITLE=' . $rec['TITLE'] . '; TYPE=' . $rec['TYPE'] . '; CONFIG.type=' . (is_array($config_check) && isset($config_check['type']) ? $config_check['type'] : ''));

               // Обрабатываем набор метрик и привязанные к ним объекты и свойства.
               if (is_array($new_dev_traits)) {
                  if (is_array($old_dev_traits)) {
                     // Если удалили метрику, у которой были привязанные объект и свойство, то удаляем линк.
                     $removed_keys = array_diff(array_keys($old_dev_traits), array_keys($new_dev_traits));
                     if (!empty($removed_keys)) {
                        foreach ($removed_keys as $removed_key) {
                           $trait = isset($old_dev_traits[$removed_key]) ? $old_dev_traits[$removed_key] : [];
                           $linked_object = isset($trait['linked_object']) ? $trait['linked_object'] : '';
                           $linked_property = isset($trait['linked_property']) ? $trait['linked_property'] : '';
                           if ($linked_object != '' && $linked_property != '') {
                              removeLinkedProperty($linked_object, $linked_property, $this->name);
                              $this->WriteLog("removeLinkedProperty for $linked_object and $linked_property");
                           }
                        }
                     }
                  }

                  foreach ($new_dev_traits as $trait) {
                     // Новые объект и свойство метрики.
                     $trait_type = isset($trait['type']) ? $trait['type'] : '';
                     if ($trait_type == '') {
                        continue;
                     }
                     $linked_object = isset($trait['linked_object']) ? $trait['linked_object'] : '';
                     $linked_property = isset($trait['linked_property']) ? $trait['linked_property'] : '';

                     // Предыдущие объект и свойство метрики.
                     if (isset($old_dev_traits[$trait_type])) {
                        $old_linked_object = isset($old_dev_traits[$trait_type]['linked_object']) ? $old_dev_traits[$trait_type]['linked_object'] : '';
                        $old_linked_property = isset($old_dev_traits[$trait_type]['linked_property']) ? $old_dev_traits[$trait_type]['linked_property'] : '';
                     } else {
                        $old_linked_object = '';
                        $old_linked_property = '';
                     }

                     // Если юзер удалил привязанное свойство, но забыл про объект, то очищаем его.
                     if ($linked_object != '' && $linked_property == '') {
                        $linked_object = '';
                        $new_dev_traits[$trait_type]['linked_object'] = '';
                     }

                     // Если юзер удалил только привязанный объект, то свойство тоже очищаем.
                     if ($linked_object == '' && $linked_property != '') {
                        $linked_property = '';
                        $new_dev_traits[$trait_type]['linked_property'] = '';
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
               $this->sendDiscoveryCallback();
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
      $this->sendDiscoveryCallback();
   }

   function getFakeTraitsForType($type)
   {
      if ($type == 'camera') {
         return [
            'video_stream' => [
               'type' => 'video_stream',
               'linked_object' => '',
               'linked_property' => '',
               'reportable' => false,
               'description' => 'Видеопоток камеры'
            ]
         ];
      }

      if (strpos($type, 'sensor') === 0 || strpos($type, 'smart_meter') === 0) {
         return [
            'temperature_sensor' => [
               'type' => 'temperature_sensor',
               'linked_object' => '',
               'linked_property' => '',
               'reportable' => false,
               'description' => 'Температура'
            ]
         ];
      }

      return [
         'on' => [
            'type' => 'on',
            'linked_object' => '',
            'linked_property' => '',
            'reportable' => false,
            'description' => 'Включить/выключить'
         ]
      ];
   }

   function createFakeDevicesForIconSync()
   {
      $created = 0;
      foreach ($this->devices_type as $type_name => $type_data) {
         $title = FAKE_DEVICE_TITLE_PREFIX . ' ' . $type_name;
         $existing = SQLSelectOne("SELECT ID FROM yandexhome_devices WHERE TITLE='" . DBSafe($title) . "' AND ROOM='" . FAKE_DEVICE_ROOM . "'");
         if (is_array($existing) && !empty($existing['ID'])) {
            continue;
         }

         $rec = [
            'TITLE' => $title,
            'TYPE' => $type_name,
            'ROOM' => FAKE_DEVICE_ROOM,
            'DESCRIPTION' => 'Auto fake device for Yandex icon sync',
            'MANUFACTURER' => 'MajorDoMo',
            'MODEL' => 'YH Fake',
            'SW_VERSION' => '1',
            'HW_VERSION' => '1'
         ];
         $rec['ID'] = SQLInsert('yandexhome_devices', $rec);
         $traits = $this->getFakeTraitsForType($type_name);
         $rec['TRAITS'] = json_encode($traits, JSON_UNESCAPED_UNICODE);
         $rec['CONFIG'] = $this->buildDeviceConfig($rec, $traits, $this->devices_instance);
         SQLUpdate('yandexhome_devices', $rec);
         $created++;
      }
      if ($created > 0) {
         $this->sendDiscoveryCallback();
      }
      return $created;
   }

   function deleteFakeDevicesForIconSync()
   {
      $deleted = 0;
      $rows = SQLSelect("SELECT ID,TRAITS FROM yandexhome_devices WHERE TITLE LIKE '" . DBSafe(FAKE_DEVICE_TITLE_PREFIX) . "%' AND ROOM='" . FAKE_DEVICE_ROOM . "'");
      if (!is_array($rows) || empty($rows)) {
         return 0;
      }

      foreach ($rows as $row) {
         $traits = json_decode(isset($row['TRAITS']) ? $row['TRAITS'] : '[]', true);
         $this->DeleteLinkedProperties($row['ID'], $traits);
         SQLExec("DELETE FROM yandexhome_devices WHERE ID=" . (int)$row['ID']);
         $deleted++;
      }
      if ($deleted > 0) {
         $this->sendDiscoveryCallback();
      }
      return $deleted;
   }

   function sendDiscoveryCallback()
   {
      $skill_id = $this->getReportableSkillId();
      if (empty($this->config['SKILL_ACCESS_TOKEN']) || $skill_id == '') {
         return false;
      }

      $payload = [
         'ts' => microtime(true),
         'payload' => [
            'user_id' => md5($this->config['USER_NAME'])
         ]
      ];
      $body = $this->encodeYandexJson($payload);
      $url = "https://dialogs.yandex.net/api/v1/skills/" . urlencode($skill_id) . "/callback/discovery";

      $crl = curl_init($url);
      curl_setopt($crl, CURLOPT_URL, $url);
      curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($crl, CURLOPT_POST, 1);
      curl_setopt($crl, CURLOPT_POSTFIELDS, $body);
      curl_setopt($crl, CURLOPT_HTTPHEADER, [
         'Content-type: application/json',
         'Authorization: OAuth ' . $this->config['SKILL_ACCESS_TOKEN']
      ]);

      if (defined('USE_PROXY') && USE_PROXY != '') {
         curl_setopt($crl, CURLOPT_PROXY, USE_PROXY);
         if (defined('USE_PROXY_AUTH') && USE_PROXY_AUTH != '') {
            curl_setopt($crl, CURLOPT_PROXYUSERPWD, USE_PROXY_AUTH);
         }
      }

      $rest = curl_exec($crl);
      $http_code = curl_getinfo($crl, CURLINFO_HTTP_CODE);
      curl_close($crl);

      $this->WriteLog("Discovery callback HTTP $http_code: " . $rest);
      return ($http_code == 202);
   }

   /**
   *
   * Обработка событий смены значений привязанных к метрикам свойств объектов.
   *
   */

   function PropertySetHandle($object, $property, $value)
   {
      $this->WriteLog("PropertySetHandle for object '$object' and property '$property' and value=$value");
      $is_domofon_now_calling = ($object === 'Domofon' && $property === 'nowCalling');
      if ($is_domofon_now_calling) {
         $this->WriteLog("DOMOFON DEBUG trigger: object='$object'; property='$property'; value=" . json_encode($value));
      }
      $skill_id = $this->getReportableSkillId();
      if (empty($this->config['SKILL_ACCESS_TOKEN']) || $skill_id == '') {
         if ($is_domofon_now_calling) {
            $this->WriteLog("DOMOFON DEBUG skip: callback credentials are not configured");
         }
         return;
      }

      $devices = SQLSelect("SELECT * FROM yandexhome_devices WHERE TRAITS LIKE '%$object%' AND  TRAITS LIKE '%$property%'" );
      foreach($devices as $device) {
         $traits = json_decode($device['TRAITS'], true);
         if (!is_array($traits)) {
            continue;
         }

         foreach ($traits as $trait) {
            if (!is_array($trait)) {
               continue;
            }

            $linked_object = isset($trait['linked_object']) ? $trait['linked_object'] : '';
            $linked_property = isset($trait['linked_property']) ? $trait['linked_property'] : '';
            if ($linked_object != $object || $linked_property != $property) {
               continue;
            }

            if (empty($trait['reportable'])) {
               continue;
            }

            $trait_type = isset($trait['type']) ? $trait['type'] : '';
            $instance_def = $this->getInstanceDefinition($trait_type);
            if (!$instance_def) {
               continue;
            }

            if ($instance_def['capability'] == 'video_stream') {
               continue;
            }

            $state = [
               'instance' => $this->getInstanceName($trait_type, $instance_def),
               'value' => $this->normalizeValueForState($trait_type, $instance_def, $value, $trait)
            ];

            $device_state = ["id" => $device["ID"]];
            if ($this->isPropertyCapability($instance_def['capability'])) {
               $device_state['properties'] = [[
                  'type' => PREFIX_PROPERTIES . $instance_def['capability'],
                  'state' => $state
               ]];
            } else {
               $device_state['capabilities'] = [[
                  'type' => PREFIX_CAPABILITIES . $instance_def['capability'],
                  'state' => $state
               ]];
            }

            $send = [
               'ts' => microtime(true),
               'payload' => [
                  "user_id" => md5($this->config['USER_NAME']),
                  "devices" => [$device_state]
               ]
            ];

            $body = $this->encodeYandexJson($send);
            $this->WriteLog("PropertySetHandle send: " . $body);
            if ($is_domofon_now_calling) {
               $this->WriteLog("DOMOFON DEBUG payload: " . $body);
            }
            $url = "https://dialogs.yandex.net/api/v1/skills/" . urlencode($skill_id) . "/callback/state";
            $crl = curl_init($url);
            curl_setopt($crl, CURLOPT_URL, $url);
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

            $headr = [];
            $headr[] = 'Content-type: application/json';
            $headr[] = 'Authorization: OAuth ' . $this->config['SKILL_ACCESS_TOKEN'];

            if (defined('USE_PROXY') && USE_PROXY != '') {
               curl_setopt($crl, CURLOPT_PROXY, USE_PROXY);
               if (defined('USE_PROXY_AUTH') && USE_PROXY_AUTH != '') {
                  curl_setopt($crl, CURLOPT_PROXYUSERPWD, USE_PROXY_AUTH);
               }
            }

            curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);
            curl_setopt($crl, CURLOPT_POST, 1);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $body);

            $rest = curl_exec($crl);
            $http_code = curl_getinfo($crl, CURLINFO_HTTP_CODE);
            curl_close($crl);
            $this->WriteLog("PropertySetHandle send result HTTP $http_code: " . $rest);
            if ($is_domofon_now_calling) {
               $this->WriteLog("DOMOFON DEBUG result: HTTP $http_code; body=" . $rest);
            }
         }
      }
   }

   /**
   *
   * Обработка запроса информации о перечне устройств.
   *
   */
   function HandleSyncRequest($content)
   {
      $this->WriteLog('Incoming sync request');

      $res = SQLSelect("SELECT * FROM yandexhome_devices ORDER BY ID");
      $devices = [];

      foreach ($res as $device) {
         $traits = json_decode(isset($device['TRAITS']) ? $device['TRAITS'] : '', true);
         if (!is_array($traits)) {
            $traits = [];
         }
         $rebuilt_config = $this->buildDeviceConfig($device, $traits, $this->devices_instance);
         if ($rebuilt_config === '') {
            continue;
         }
         if (!isset($device['CONFIG']) || $device['CONFIG'] !== $rebuilt_config) {
            $device['CONFIG'] = $rebuilt_config;
            SQLUpdate('yandexhome_devices', $device);
         }
         $decoded = json_decode($rebuilt_config, true);
         if ($this->isValidDiscoveryDevice($decoded)) {
            $this->WriteLog('Sync device send: ID=' . (isset($device['ID']) ? $device['ID'] : '') . '; TITLE=' . (isset($device['TITLE']) ? $device['TITLE'] : '') . '; TYPE=' . (isset($decoded['type']) ? $decoded['type'] : ''));
            $devices[] = $decoded;
         }
      }

      $response = $this->encodeYandexJson([
         'request_id' => isset($content['request_id']) ? $content['request_id'] : '',
         'payload' => [
            'user_id' => md5($this->config['USER_NAME']),
            'devices' => $devices
         ]
      ]);

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

      $request_devices = isset($content['devices']) && is_array($content['devices']) ? $content['devices'] : [];
      foreach ($request_devices as $device) {
         $device_id = isset($device['id']) ? $device['id'] : '';

         $this->WriteLog("Incoming query request for device ID$device_id");

         $rec = SQLSelectOne("SELECT * FROM yandexhome_devices WHERE ID='{$device_id}'");

         if (is_array($rec) && !empty($rec['TRAITS'])) {
            $capabilities = [];
            $properties = [];
            $traits = json_decode($rec['TRAITS'], true);
            if (is_array($traits)) {
               foreach ($traits as $trait) {
                  $trait_type = isset($trait['type']) ? $trait['type'] : '';
                  $instance_def = $this->getInstanceDefinition($trait_type);
                  if (!$instance_def) {
                     continue;
                  }

                  // video_stream не имеет текущего состояния в query.
                  if ($instance_def['capability'] == 'video_stream') {
                     continue;
                  }

                  $linked_object = isset($trait['linked_object']) ? $trait['linked_object'] : '';
                  $linked_property = isset($trait['linked_property']) ? $trait['linked_property'] : '';
                  if ($linked_object != '' && $linked_property != '') {
                     $value = getGlobal("$linked_object.$linked_property");
                     $this->WriteLog("Object '$linked_object', property '$linked_property', get value=$value");
                  } else {
                     $value = isset($instance_def['default_value']) ? $instance_def['default_value'] : null;
                  }

                  $state = [
                     'instance' => $this->getInstanceName($trait_type, $instance_def),
                     'value' => $this->normalizeValueForState($trait_type, $instance_def, $value, $trait)
                  ];

                  if (($instance_def['capability'] == 'float') || ($instance_def['capability'] == 'event')) {
                     $properties[] = [
                        'type' => PREFIX_PROPERTIES . $instance_def['capability'],
                        'state' => $state
                     ];
                  } else {
                     $capabilities[] = [
                        'type' => PREFIX_CAPABILITIES . $instance_def['capability'],
                        'state' => $state
                     ];
                  }
               }
               $device_state = [
                  'id' => $device_id,
               ];
               if (!empty($capabilities)) {
                  $device_state['capabilities'] = $capabilities;
               }
               if (!empty($properties)) {
                  $device_state['properties'] = $properties;
               }
               $devices[] = $device_state;
            } else {
               $devices[] = [
                  'id' => $device_id,
                  'error_code' => 'DEVICE_NOT_FOUND',
                  'error_message' => 'Invalid device traits',
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

      $response = $this->encodeYandexJson([
                  'request_id' => isset($content['request_id']) ? $content['request_id'] : '',
                  'payload' => [
                     'devices' => $devices
                  ]
               ]);

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

      $request_devices = isset($content['payload']['devices']) && is_array($content['payload']['devices']) ? $content['payload']['devices'] : [];
      foreach ($request_devices as $device) {
         $device_id = isset($device['id']) ? $device['id'] : '';

         $this->WriteLog("Incoming action request for device ID$device_id");

         $rec = SQLSelectOne("SELECT * FROM yandexhome_devices WHERE ID='{$device_id}'");
         $traits = [];
         if (is_array($rec) && !empty($rec['TRAITS'])) {
            $decoded_traits = json_decode($rec['TRAITS'], true);
            if (is_array($decoded_traits)) {
               $traits = $decoded_traits;
            }
         }

         $capabilities = [];

         $request_capabilities = isset($device['capabilities']) && is_array($device['capabilities']) ? $device['capabilities'] : [];
         foreach ($request_capabilities as $capability) {

            $type = isset($capability['type']) ? $capability['type'] : '';
            $state_req = isset($capability['state']) && is_array($capability['state']) ? $capability['state'] : [];
            $value = isset($state_req['value']) ? $state_req['value'] : null;
            $instance = isset($state_req['instance']) ? $state_req['instance'] : '';

            $relative = isset($state_req['relative']) && $state_req['relative'] === true;

            $this->WriteLog("Capabilities type '$type', instance '$instance', relative=" . (($relative === true) ? 1 : 0) . ", value=" . json_encode($value));

            $state = ['instance' => $instance];
            $error_code = false;
            $error_message = '';

            if (!is_array($rec) || !$rec) {
               $error_code = 'DEVICE_NOT_FOUND';
               $error_message = 'DEVICE_NOT_FOUND_MSG';
            } else {
               $trait_type = $this->findTraitTypeByInstance($traits, $instance, $type);
               $trait = ($trait_type !== null && isset($traits[$trait_type])) ? $traits[$trait_type] : null;
               $instance_def = $trait_type !== null ? $this->getInstanceDefinition($trait_type) : null;

               if (!$trait || !$instance_def) {
                  $error_code = 'INVALID_ACTION';
                  $error_message = 'INVALID_ACTION_MSG';
               } else {
                  if ($instance_def['capability'] == 'video_stream' && $instance == 'get_stream') {
                     $stream_url = $this->getTraitLinkedValue($trait, $instance_def);
                     if (!$stream_url) {
                        $error_code = 'DEVICE_UNREACHABLE';
                        $error_message = 'Stream URL is empty';
                     } else {
                        $state['value'] = [
                           'stream_url' => (string)$stream_url,
                           'protocol' => 'hls'
                        ];
                     }
                  } else {
                     $linked_object = isset($trait['linked_object']) ? $trait['linked_object'] : '';
                     $linked_property = isset($trait['linked_property']) ? $trait['linked_property'] : '';
                     if ($linked_object == '' || $linked_property == '') {
                        $error_code = 'INVALID_ACTION';
                        $error_message = 'Linked object/property not configured';
                     } else {
                        if ($relative && $instance_def['capability'] == 'range') {
                           $cur_val = getGlobal("$linked_object.$linked_property");
                           $value = floatval($cur_val) + floatval($value);
                        }
                        $converted_value = $this->normalizeValueForWrite($trait_type, $instance_def, $value, $trait);
                        if ($this->config['READONLY_MODE'] != 1) {
                           setGlobal("$linked_object.$linked_property", $converted_value, array($this->name => '0'));
                           $this->WriteLog("Object '$linked_object', property '$linked_property', set value=" . json_encode($converted_value));
                        } else {
                           $this->WriteLog('The property of the object has not been set. The module is in read-only mode.');
                           $error_code = 'NOT_SUPPORTED_IN_CURRENT_MODE';
                           $error_message = 'The device is not controlled in this mode. The module is in read-only mode.';
                        }
                     }
                  }
               }
            }

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

         $device_result = ['id' => $device_id];
         if (!empty($capabilities)) {
            $device_result['capabilities'] = $capabilities;
         } else {
            $device_result['action_result'] = [
               'status' => 'ERROR',
               'error_code' => is_array($rec) && $rec ? 'INVALID_ACTION' : 'DEVICE_NOT_FOUND',
               'error_message' => is_array($rec) && $rec ? 'No capabilities in request' : 'DEVICE_NOT_FOUND_MSG'
            ];
         }
         $devices[] = $device_result;
      }

      $response = $this->encodeYandexJson([
                  'request_id' => isset($content['request_id']) ? $content['request_id'] : '',
                  'payload' => [
                     'devices' => $devices
                  ]
               ]);

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

   function normalizeTraitType($trait_type)
   {
      if (isset($this->legacy_instance_aliases[$trait_type])) {
         return $this->legacy_instance_aliases[$trait_type];
      }
      return $trait_type;
   }

   function getInstanceDefinition($trait_type)
   {
      $normalized = $this->normalizeTraitType($trait_type);
      if (isset($this->devices_instance[$normalized])) {
         return $this->devices_instance[$normalized];
      }
      return null;
   }

   function getInstanceName($trait_type, $instance_def = null)
   {
      if (!$instance_def) {
         $instance_def = $this->getInstanceDefinition($trait_type);
      }
      if (!$instance_def) {
         return $trait_type;
      }
      if (isset($instance_def['instance']) && $instance_def['instance'] != '') {
         return $instance_def['instance'];
      }
      return $trait_type;
   }

   function getDeviceTypeDescription($type)
   {
      if (isset($this->devices_type[$type]['description'])) {
         return $this->devices_type[$type]['description'];
      }
      return $type;
   }

   function isPropertyCapability($capability)
   {
      return ($capability == 'float' || $capability == 'event');
   }

   function normalizeEventValueForState($trait_type, $value, $trait = [])
   {
      if (is_string($value) && $value !== '') {
         $known_values = [
            'opened', 'closed', 'detected', 'not_detected', 'click', 'double_click', 'long_press',
            'leak', 'dry', 'high', 'low', 'normal', 'empty'
         ];
         if (in_array($value, $known_values, true)) {
            return $value;
         }
      }

      switch ($trait_type) {
         case 'motion_sensor':
         case 'smoke_sensor':
         case 'gas_sensor':
            return $value ? 'detected' : 'not_detected';
         case 'water_leak_sensor':
            return $value ? 'leak' : 'dry';
         case 'open_sensor':
            $linked_object = isset($trait['linked_object']) ? $trait['linked_object'] : '';
            if ($linked_object != '') {
               return (gg($linked_object . '.ncno') == 'nc' ? ($value ? 'closed' : 'opened') : ($value ? 'opened' : 'closed'));
            }
            return $value ? 'opened' : 'closed';
         case 'battery_level_event_sensor':
            return $value ? 'low' : 'normal';
         case 'food_level_event_sensor':
         case 'water_level_event_sensor':
            if ((string)$value === '0') {
               return 'empty';
            }
            if ((string)$value === '1') {
               return 'low';
            }
            if ((string)$value === '2') {
               return 'normal';
            }
            return $value ? 'low' : 'normal';
         default:
            $normalized = strtolower((string)$value);
            if ($normalized === '1' || $normalized === 'true' || $normalized === 'on') {
               return 'detected';
            }
            if ($normalized === '0' || $normalized === 'false' || $normalized === 'off') {
               return 'not_detected';
            }
            return $value;
      }
   }

   function normalizeValueForState($trait_type, $instance_def, $value, $trait = [])
   {
      if (isset($trait['value_map']) && is_array($trait['value_map']) && !empty($trait['value_map'])) {
         $map_key = strtolower(trim((string)$value));
         if (isset($trait['value_map'][$map_key])) {
            $value = $trait['value_map'][$map_key];
         }
      }

      $value_type = isset($instance_def['value_type']) ? $instance_def['value_type'] : 'string';

      switch ($value_type) {
         case 'bool':
            if (is_bool($value)) {
               return $value;
            }
            $normalized = strtolower(trim((string)$value));
            if (in_array($normalized, ['1', 'true', 'on', 'yes'], true)) {
               return true;
            }
            if (in_array($normalized, ['0', 'false', 'off', 'no', ''], true)) {
               return false;
            }
            return ((float)$value != 0.0);
         case 'float':
            return floatval($value);
         case 'int':
            return intval($value);
         case 'rgb':
            $rgb = preg_replace('/^#/', '', (string)$value);
            return hexdec($rgb);
         case 'event':
            return $this->normalizeEventValueForState($trait_type, $value, $trait);
         default:
            return $value;
      }
   }

   function normalizeValueForWrite($trait_type, $instance_def, $value, $trait = [])
   {
      $value_type = isset($instance_def['value_type']) ? $instance_def['value_type'] : 'string';

      switch ($value_type) {
         case 'bool':
            return ($value === true || $value === 1 || $value === '1') ? 1 : 0;
         case 'int':
            return intval($value);
         case 'float':
            return floatval($value);
         case 'rgb':
            if (is_numeric($value)) {
               return str_pad(dechex((int)$value), 6, '0', STR_PAD_LEFT);
            }
            return preg_replace('/^#/', '', (string)$value);
         default:
            return $value;
      }
   }

   function getTraitLinkedValue($trait, $instance_def)
   {
      $linked_object = isset($trait['linked_object']) ? $trait['linked_object'] : '';
      $linked_property = isset($trait['linked_property']) ? $trait['linked_property'] : '';
      if ($linked_object != '' && $linked_property != '') {
         return getGlobal("$linked_object.$linked_property");
      }
      if (isset($instance_def['default_value'])) {
         return $instance_def['default_value'];
      }
      return null;
   }

   function encodeYandexJson($data)
   {
      $json = json_encode($data, YANDEX_JSON_FLAGS);
      if ($json === false) {
         $this->WriteLog('JSON encode error: ' . json_last_error_msg());
         return '{"request_id":"","payload":{"devices":[]}}';
      }
      return $json;
   }

   function isValidDiscoveryDevice($device)
   {
      if (!is_array($device)) {
         return false;
      }

      if (empty($device['id']) || empty($device['name']) || empty($device['type'])) {
         return false;
      }

      if (!isset($device['status_info']) || !is_array($device['status_info']) || !isset($device['status_info']['reportable'])) {
         return false;
      }

      $has_capabilities = isset($device['capabilities']) && is_array($device['capabilities']) && count($device['capabilities']) > 0;
      $has_properties = isset($device['properties']) && is_array($device['properties']) && count($device['properties']) > 0;

      return $has_capabilities || $has_properties;
   }

   function getReportableSkillId()
   {
      if (!empty($this->config['SKILL_ID'])) {
         return trim($this->config['SKILL_ID']);
      }

      // Backward compatibility: older settings used CLIENT_KEY as dialog/skill ID for callbacks.
      if (!empty($this->config['CLIENT_KEY'])) {
         return trim($this->config['CLIENT_KEY']);
      }

      return '';
   }

   function isReportableConfigured()
   {
      return !empty($this->config['SKILL_ACCESS_TOKEN']) && $this->getReportableSkillId() != '';
   }

   function hasReportableState($new_dev_traits)
   {
      if (!$this->isReportableConfigured()) {
         return false;
      }

      if (!is_array($new_dev_traits)) {
         return false;
      }

      foreach ($new_dev_traits as $trait) {
         if (is_array($trait) && !empty($trait['reportable'])) {
            return true;
         }
      }

      return false;
   }

   function findTraitTypeByInstance($traits, $instance, $request_type)
   {
      if (!is_array($traits)) {
         return null;
      }

      foreach ($traits as $trait_type => $trait) {
         if (!is_array($trait)) {
            continue;
         }
         $raw_type = isset($trait['type']) ? $trait['type'] : $trait_type;
         $instance_def = $this->getInstanceDefinition($raw_type);
         if (!$instance_def) {
            continue;
         }

         $instance_name = $this->getInstanceName($raw_type, $instance_def);
         if ($instance_name != $instance) {
            continue;
         }

         $expected_type = $this->isPropertyCapability($instance_def['capability'])
            ? PREFIX_PROPERTIES . $instance_def['capability']
            : PREFIX_CAPABILITIES . $instance_def['capability'];
         if ($request_type == $expected_type) {
            return $raw_type;
         }
      }

      return null;
   }

   function buildDeviceConfig($rec, $new_dev_traits, $devices_instance = [])
   {
      $capabilities = [];
      $properties = [];
      $color_capability_index = null;
      $raw_type = isset($rec['TYPE']) ? trim((string)$rec['TYPE']) : 'other';
      if (strpos($raw_type, PREFIX_TYPES) === 0) {
         $raw_type = substr($raw_type, strlen(PREFIX_TYPES));
      }
      if ($raw_type === '') {
         $raw_type = 'other';
      }

      if (!is_array($new_dev_traits)) {
         $new_dev_traits = [];
      }

      foreach ($new_dev_traits as $trait) {
         if (!is_array($trait) || !isset($trait['type'])) {
            continue;
         }

         $trait_type = $this->normalizeTraitType($trait['type']);
         $instance_def = $this->getInstanceDefinition($trait_type);
         if (!$instance_def) {
            continue;
         }

         $reportable = ($this->isReportableConfigured() && isset($trait['reportable'])) ? (bool)$trait['reportable'] : false;
         if (isset($instance_def['reportable_fixed'])) {
            $reportable = $this->isReportableConfigured() && (bool)$instance_def['reportable_fixed'];
         }

         $retrievable = isset($instance_def['retrievable']) ? (bool)$instance_def['retrievable'] : true;

         $parameters = [];
         if (isset($devices_instance[$trait_type]['parameters']) && is_array($devices_instance[$trait_type]['parameters'])) {
            $parameters = $devices_instance[$trait_type]['parameters'];
         } else if (isset($instance_def['parameters']) && is_array($instance_def['parameters'])) {
            $parameters = $instance_def['parameters'];
         }

         $instance_name = $this->getInstanceName($trait_type, $instance_def);
         if ($this->isPropertyCapability($instance_def['capability'])) {
            $parameters['instance'] = $instance_name;
         } else if ($instance_def['capability'] != 'color_setting' && $instance_def['capability'] != 'video_stream') {
            $parameters['instance'] = $instance_name;
         }

         $entry = [
            'type' => ($this->isPropertyCapability($instance_def['capability']) ? PREFIX_PROPERTIES : PREFIX_CAPABILITIES) . $instance_def['capability'],
            'parameters' => $parameters,
            'retrievable' => $retrievable,
            'reportable' => $reportable
         ];

         if ($instance_def['capability'] == 'video_stream') {
            $entry['retrievable'] = false;
            $entry['reportable'] = false;
         }

         if ($instance_def['capability'] == 'color_setting') {
            if ($color_capability_index === null) {
               $capabilities[] = $entry;
               $color_capability_index = count($capabilities) - 1;
            } else {
               $existing = isset($capabilities[$color_capability_index]['parameters']) ? $capabilities[$color_capability_index]['parameters'] : [];
               $capabilities[$color_capability_index]['parameters'] = array_merge($existing, $parameters);
               if (!isset($capabilities[$color_capability_index]['reportable']) || !$capabilities[$color_capability_index]['reportable']) {
                  $capabilities[$color_capability_index]['reportable'] = $reportable;
               }
            }
            continue;
         }

         if ($this->isPropertyCapability($instance_def['capability'])) {
            $properties[] = $entry;
         } else {
            $capabilities[] = $entry;
         }
      }

      $model = trim(isset($rec['MODEL']) ? $rec['MODEL'] : '');
      if ($model == '') {
         $model = 'MajorDoMo';
      } else {
         $model .= ' | MajorDoMo';
      }

      $device = [
         'id' => isset($rec['ID']) ? $rec['ID'] : '',
         'name' => isset($rec['TITLE']) ? $rec['TITLE'] : '',
         'status_info' => [
            'reportable' => $this->hasReportableState($new_dev_traits)
         ],
         'type' => PREFIX_TYPES . $raw_type,
         'device_info' => [
            'manufacturer' => isset($rec['MANUFACTURER']) && $rec['MANUFACTURER'] != '' ? $rec['MANUFACTURER'] : 'MajorDoMo',
            'model' => $model,
            'hw_version' => isset($rec['HW_VERSION']) ? $rec['HW_VERSION'] : '',
            'sw_version' => isset($rec['SW_VERSION']) ? $rec['SW_VERSION'] : ''
         ]
      ];

      if (isset($rec['ROOM']) && $rec['ROOM'] != '') {
         $device['room'] = $rec['ROOM'];
      }
      if (isset($rec['DESCRIPTION']) && $rec['DESCRIPTION'] != '') {
         $device['description'] = $rec['DESCRIPTION'];
      }
      if (!empty($capabilities)) {
         $device['capabilities'] = $capabilities;
      }
      if (!empty($properties)) {
         $device['properties'] = $properties;
      }

      if (!$this->isValidDiscoveryDevice($device)) {
         return '';
      }

      return $this->encodeYandexJson($device);
   }

   function migrateDeviceConfigs()
   {
      $tables = SQLSelect("SHOW TABLES LIKE 'yandexhome_devices'");
      if (!is_array($tables) || empty($tables)) {
         return;
      }

      $rows = SQLSelect('SELECT * FROM yandexhome_devices');
      if (!is_array($rows) || empty($rows)) {
         return;
      }

      foreach ($rows as $rec) {
         $changed = false;
         $traits = json_decode($rec['TRAITS'], true);
         if (!is_array($traits)) {
            continue;
         }

         $normalized_traits = [];
         foreach ($traits as $key => $trait) {
            if (!is_array($trait)) {
               continue;
            }

            $type = isset($trait['type']) ? $trait['type'] : $key;
            $normalized_type = $this->normalizeTraitType($type);
            if ($normalized_type != $type) {
               $changed = true;
            }

            $trait['type'] = $normalized_type;
            if (!isset($trait['reportable'])) {
               $trait['reportable'] = false;
               $changed = true;
            }

            if (!isset($trait['description'])) {
               $instance_def = $this->getInstanceDefinition($normalized_type);
               if ($instance_def && isset($instance_def['description'])) {
                  $trait['description'] = $instance_def['description'];
                  $changed = true;
               }
            }

            if (!isset($trait['value_map_preset'])) {
               $trait['value_map_preset'] = ($normalized_type === 'on') ? 'bool_onoff_10' : 'none';
               $changed = true;
            }
            if (!isset($trait['value_map']) || !is_array($trait['value_map'])) {
               if ($normalized_type === 'on') {
                  $trait['value_map'] = ['1' => 'on', '0' => 'off', 'true' => 'on', 'false' => 'off'];
               } else {
                  $trait['value_map'] = [];
               }
               $changed = true;
            }

            $normalized_traits[$normalized_type] = $trait;
         }

         $new_config = $this->buildDeviceConfig($rec, $normalized_traits, $this->devices_instance);
         if ($new_config !== $rec['CONFIG']) {
            $rec['CONFIG'] = $new_config;
            $changed = true;
         }

         if ($changed) {
            $rec['TRAITS'] = json_encode($normalized_traits, JSON_UNESCAPED_UNICODE);
            SQLUpdate('yandexhome_devices', $rec);
         }
      }
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
         $properties = is_array($properties) && isset($properties['TRAITS']) ? json_decode($properties['TRAITS'], true) : [];
      }

      if (is_array($properties) && !empty($properties)) {
         foreach ($properties as $prop) {
            $linked_object = isset($prop['linked_object']) ? $prop['linked_object'] : '';
            $linked_property = isset($prop['linked_property']) ? $prop['linked_property'] : '';
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
      $method = isset($request->server['REQUEST_METHOD']) ? $request->server['REQUEST_METHOD'] : '';

      if (isset($request->server['HTTP_X_FORWARDED_FOR'])) {
         $remoteip = $request->server['HTTP_X_FORWARDED_FOR'];
      } else {
         $remoteip = isset($request->server['REMOTE_ADDR']) ? $request->server['REMOTE_ADDR'] : '';
      }

      $script = isset($request->server['REQUEST_URI']) ? $request->server['REQUEST_URI'] : '';

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
