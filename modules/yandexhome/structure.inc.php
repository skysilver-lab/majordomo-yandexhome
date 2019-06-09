<?php

/*
   on_off
      on

   color_setting
      hsv
      rgb
      temperature_k
   
   mode
      thermostat
      fan_speed
   
   range
      brightness
      temperature
      volume
      channel
   
   toggle
      mute 
*/

$this->devices_type = [
   'media_device' => [
      'device_name' => 'media_device',
      'description' => 'Аудио и видеотехника'
   ],
   'thermostat.ac' => [
      'device_name' => 'thermostat.ac',
      'description' => 'Кондиционер'
   ],
   'cooking' => [
      'device_name' => 'cooking',
      'description' => 'Кухонная техника'
   ],
   'other' => [
      'device_name' => 'other',
      'description' => 'Остальные устройства'
   ],
   'switch' => [
      'device_name' => 'switch',
      'description' => 'Переключатель'
   ],
   'socket' => [
      'device_name' => 'socket',
      'description' => 'Розетка'
   ],
   'light' => [
      'device_name' => 'light',
      'description' => 'Свет'
   ],
   'media_device.tv' => [
      'device_name' => 'media_device.tv',
      'description' => 'Телевизор'
   ],
   'thermostat' => [
      'device_name' => 'thermostat',
      'description' => 'Термостат'
   ],
   'cooking.kettle' => [
      'device_name' => 'cooking.kettle',
      'description' => 'Чайник'
   ],

];

$this->devices_instance = [
   'on' => [
      'instance_name' => 'on',
      'description' => 'Включить/выключить',
      'capabilitie' => 'on_off',
      'default_value' => 0
   ],/*
   'volume' => [
      'instance_name' => 'volume',
      'description' => 'Громкость',
      'capabilitie' => 'range',
      'default_value' => 10,
      'parameters' => [
         'unit' => 'unit.percent',
         'range' => [
            'min' => 0,
            'max' => 100,
            'precision' => 1
         ]
      ]
   ],
   'channel' => [
      'instance_name' => 'channel',
      'description' => 'Канал',
      'capabilitie' => 'range'
   ],
   'temperature' => [
      'instance_name' => 'temperature',
      'description' => 'Температура',
      'capabilitie' => 'range'
   ],*/
   'temperature_k' => [
      'instance_name' => 'temperature_k',
      'description' => 'Цветовая температура',
      'capabilitie' => 'color_setting',
      'default_value' => 4500,
      'parameters' => [
         'temperature_k' => [
            'min' => 2700,
            'max' => 9000,
            'precision' => 1
         ]
      ]
   ],/*
   'thermostat' => [
      'instance_name' => 'thermostat',
      'description' => 'Режим работы',
      'capabilitie' => 'mode'
   ],
   'mute' => [
      'instance_name' => 'mute',
      'description' => 'Режим без звука',
      'capabilitie' => 'toggle'
   ],
   'fan_speed' => [
      'instance_name' => 'fan_speed',
      'description' => 'Скорость вентиляции',
      'capabilitie' => 'mode'
   ],
   'hsv' => [
      'instance_name' => 'hsv',
      'description' => 'Цвет в формате HSV',
      'capabilitie' => 'color_setting'
   ],*/
   'rgb' => [
      'instance_name' => 'rgb',
      'description' => 'Цвет в формате RGB',
      'capabilitie' => 'color_setting',
      'default_value' => 16777215,
      'parameters' => [
         'color_model' => 'rgb'
      ]
   ],
   'brightness' => [
      'instance_name' => 'brightness',
      'description' => 'Яркость',
      'capabilitie' => 'range',
      'default_value' => 50,
      'parameters' => [
         'unit' => 'unit.percent',
         'range' => [
            'min' => 1,
            'max' => 100,
            'precision' => 1
         ]
      ]
   ]
];