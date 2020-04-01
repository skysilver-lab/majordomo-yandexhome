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
      cleanup_mode
      coffee_mode
      heat
      input_source
      program
      swing
      work_speed

   range
      brightness
      temperature
      humidity
      volume
      channel
      open

   toggle
      mute
      backlight
      controls_locked
      ionization
      keep_warm
      oscillation
      pause
*/

$this->devices_type = [
   'media_device' => [
      'device_name' => 'media_device',
      'description' => 'Аудио и видеотехника'
   ],
   'openable' => [
      'device_name' => 'openable',
      'description' => 'Дверь, ворота, окно'
   ],
   'thermostat.ac' => [
      'device_name' => 'thermostat.ac',
      'description' => 'Кондиционер'
   ],
   'cooking.coffee_maker' => [
      'device_name' => 'cooking.coffee_maker',
      'description' => 'Кофеварка'
   ],
   'cooking' => [
      'device_name' => 'cooking',
      'description' => 'Кухонная техника'
   ],
   'other' => [
      'device_name' => 'other',
      'description' => 'Остальные устройства'
   ],
   'purifier' => [
      'device_name' => 'purifier',
      'description' => 'Очиститель воздуха'
   ],
   'switch' => [
      'device_name' => 'switch',
      'description' => 'Переключатель'
   ],
   'vacuum_cleaner' => [
      'device_name' => 'vacuum_cleaner',
      'description' => 'Пылесос'
   ],
   'socket' => [
      'device_name' => 'socket',
      'description' => 'Розетка'
   ],
   'light' => [
      'device_name' => 'light',
      'description' => 'Свет'
   ],
   'washing_machine' => [
      'device_name' => 'washing_machine',
      'description' => 'Стиральная машина'
   ],
   'media_device.tv' => [
      'device_name' => 'media_device.tv',
      'description' => 'Телевизор'
   ],
   'thermostat' => [
      'device_name' => 'thermostat',
      'description' => 'Термостат'
   ],
   'humidifier' => [
      'device_name' => 'humidifier',
      'description' => 'Увлажнитель воздуха'
   ],
   'cooking.kettle' => [
      'device_name' => 'cooking.kettle',
      'description' => 'Чайник'
   ],
   'openable.curtain' => [
      'device_name' => 'openable.curtain',
      'description' => 'Шторы, жалюзи'
   ]
];

$this->devices_instance = [
   'on' => [
      'instance_name' => 'on',
      'description' => 'Включить/выключить',
      'capability' => 'on_off',
      'default_value' => 0
   ],
   'volume' => [
      'instance_name' => 'volume',
      'description' => 'Громкость',
      'capability' => 'range',
      'default_value' => 1,
      'parameters' => [
         'range' => [
            'min' => 1,
            'max' => 100,
            'precision' => 1
         ]
      ]
   ],
   'channel' => [
      'instance_name' => 'channel',
      'description' => 'ТВ-канал',
      'capability' => 'range',
      'default_value' => 1,
      'retrievable' => false,
      'parameters' => [
         'range' => [
            'min' => 0,
            'max' => 999,
            'precision' => 1
         ]
      ]
   ],
   'temperature' => [
      'instance_name' => 'temperature',
      'description' => 'Температура',
      'capability' => 'range',
      'default_value' => 20,
      'parameters' => [
         'unit' => 'unit.temperature.celsius',
         'range' => [
            'min' => 1,
            'max' => 100,
            'precision' => 1
         ]
      ]
   ],
   'temperature_k' => [
      'instance_name' => 'temperature_k',
      'description' => 'Цветовая температура',
      'capability' => 'color_setting',
      'default_value' => 4500,
      'parameters' => [
         'temperature_k' => [
            'min' => 2700,
            'max' => 9000,
            'precision' => 1
         ]
      ]
   ],
   'thermostat' => [
      'instance_name' => 'thermostat',
      'description' => 'Температурный режим',
      'capability' => 'mode',
      'parameters' => [
         'modes' => [
            ['value' => 'auto'],
            ['value' => 'heat'],
            ['value' => 'cool'],
            ['value' => 'eco'],
            ['value' => 'dry'],
            ['value' => 'fan_only']
         ],
         'ordered' => true
      ]
   ],
   'input_source' => [
      'instance_name' => 'input_source',
      'description' => 'Источник сигнала',
      'capability' => 'mode',
      'parameters' => [
         'modes' => [
            ['value' => 'one'],
            ['value' => 'two'],
            ['value' => 'three'],
            ['value' => 'four'],
            ['value' => 'five']
         ],
         'ordered' => false
      ]
   ],
   'mute' => [
      'instance_name' => 'mute',
      'description' => 'Режим без звука',
      'capability' => 'toggle',
      'default_value' => false
   ],
   'pause' => [
      'instance_name' => 'pause',
      'description' => 'Пауза',
      'capability' => 'toggle',
      'default_value' => false
   ],
   'fan_speed' => [
      'instance_name' => 'fan_speed',
      'description' => 'Скорость вентиляции',
      'capability' => 'mode',
      'parameters' => [
         'modes' => [
            ['value' => 'auto'],
            ['value' => 'low'],
            ['value' => 'medium'],
            ['value' => 'high']
         ],
         'ordered' => true
      ]
   ],/*
   'hsv' => [
      'instance_name' => 'hsv',
      'description' => 'Цвет в формате HSV',
      'capability' => 'color_setting'
   ],*/
   'rgb' => [
      'instance_name' => 'rgb',
      'description' => 'Цвет в формате RGB',
      'capability' => 'color_setting',
      'default_value' => '000000',
      'parameters' => [
         'color_model' => 'rgb'
      ]
   ],
   'brightness' => [
      'instance_name' => 'brightness',
      'description' => 'Яркость',
      'capability' => 'range',
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