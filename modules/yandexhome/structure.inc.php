<?php

$this->devices_type = [
   'media_device' => [
      'device_name' => 'media_device',
      'description' => 'Аудио и видеотехника'
   ],
   'media_device.receiver' => [
      'device_name' => 'media_device.receiver',
      'description' => 'AV-ресивер'
   ],
   'sensor' => [
      'device_name' => 'sensor',
      'description' => 'Датчик'
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
   'cooking.multicooker' => [
      'device_name' => 'cooking.multicooker',
      'description' => 'Мультиварка'
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
   'dishwasher' => [
      'device_name' => 'dishwasher',
      'description' => 'Посудомоечная машина'
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
   'media_device.tv_box' => [
      'device_name' => 'media_device.tv_box',
      'description' => 'ТВ-приставка'
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
   ],
   'iron' => [
      'device_name' => 'iron',
      'description' => 'Утюг, парогенератор'
   ]
];

$this->devices_instance = [
   'controls_locked' => [
      'instance_name' => 'controls_locked',
      'description' => 'Блокировка управления',
      'capability' => 'toggle',
      'default_value' => false
   ],
   'on' => [
      'instance_name' => 'on',
      'description' => 'Включить/выключить',
      'capability' => 'on_off',
      'default_value' => 0,
      'parameters' => [
         'split' => false,
      ]
   ],
   'humidity' => [
      'instance_name' => 'humidity',
      'description' => 'Влажность',
      'capability' => 'range',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.percent',
         'range' => [
            'min' => 0,
            'max' => 100,
            'precision' => 5
         ]
      ]
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
   'input_source' => [
      'instance_name' => 'input_source',
      'description' => 'Источник сигнала',
      'capability' => 'mode',
      'default_value' => 'one',
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
   'pause' => [
      'instance_name' => 'pause',
      'description' => 'Пауза',
      'capability' => 'toggle',
      'default_value' => false
   ],
   'backlight' => [
      'instance_name' => 'backlight',
      'description' => 'Подсветка',
      'capability' => 'toggle',
      'default_value' => false
   ],
   'mute' => [
      'instance_name' => 'mute',
      'description' => 'Режим без звука',
      'capability' => 'toggle',
      'default_value' => false
   ],
   'oscillation' => [
      'instance_name' => 'oscillation',
      'description' => 'Режим вращения',
      'capability' => 'toggle',
      'default_value' => false
   ],
   'ionization' => [
      'instance_name' => 'ionization',
      'description' => 'Режим ионизации',
      'capability' => 'toggle',
      'default_value' => false
   ],
   'keep_warm' => [
      'instance_name' => 'keep_warm',
      'description' => 'Режим поддержания тепла',
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
   ],
   'open' => [
      'instance_name' => 'open',
      'description' => 'Степень открытия',
      'capability' => 'range',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.percent',
         'range' => [
            'min' => 0,
            'max' => 100,
            'precision' => 10
         ]
      ]
   ],
   'channel' => [
      'instance_name' => 'channel',
      'description' => 'ТВ-канал',
      'capability' => 'range',
      'default_value' => 1,
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
            ['value' => 'fan_only'],
            ['value' => 'turbo'],
         ],
         'ordered' => true
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
   ],
   'amperage_sensor' => [
      'instance_name' => 'amperage_sensor',
      'description' => 'Сила тока',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.ampere'
      ]
   ],
   'battery_level_sensor' => [
      'instance_name' => 'battery_level_sensor',
      'description' => 'Уровень заряда',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.percent'
      ]
   ],
   'co2_level_sensor' => [
      'instance_name' => 'co2_level_sensor',
      'description' => 'Углекислый газ',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.ppm'
      ]
   ],
   'humidity_sensor' => [
      'instance_name' => 'humidity_sensor',
      'description' => 'Влажность',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.percent'
      ]
   ],
   'illumination_sensor' => [
      'instance_name' => 'illumination_sensor',
      'description' => 'Освещенность',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.illumination.lux'
      ]
   ],
   'pm1_density_sensor' => [
      'instance_name' => 'pm1_density_sensor',
      'description' => 'Загрязнение воздуха частицами PM1',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.density.mcg_m3'
      ]
   ],
   'pm2.5_density_sensor' => [
      'instance_name' => 'pm2.5_density_sensor',
      'description' => 'Загрязнение воздуха частицами PM2.5',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.density.mcg_m3'
      ]
   ],
   'pm10_density_sensor' => [
      'instance_name' => 'pm10_density_sensor',
      'description' => 'Загрязнение воздуха частицами PM10',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.density.mcg_m3'
      ]
   ],
   'power_sensor' => [
      'instance_name' => 'power_sensor',
      'description' => 'Мощность',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.watt'
      ]
   ],
   'pressure_sensor' => [
      'instance_name' => 'pressure_sensor',
      'description' => 'Давление мм. рт. ст.',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.pressure.mmhg'
      ]
   ],
   'temperature_sensor' => [
      'instance_name' => 'temperature_sensor',
      'description' => 'Температура',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.temperature.celsius'
      ]
   ],
   'tvoc_sensor' => [
      'instance_name' => 'tvoc_sensor',
      'description' => 'Загрязнение воздуха ограническими веществами',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.density.mcg_m3'
      ]
   ],
   'voltage_sensor' => [
      'instance_name' => 'voltage_sensor',
      'description' => 'Напряжение',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.volt'
      ]
   ],
   'water_level_sensor' => [
      'instance_name' => 'water_level_sensor',
      'description' => 'Уровень воды',
      'capability' => 'float',
      'default_value' => 0,
      'parameters' => [
         'unit' => 'unit.percent'
      ]
   ],
   'vibration_sensor' => [
      'instance_name' => 'vibration_sensor',
      'description' => 'Датчик вибрации/падения/переворачивания',
      'capability' => 'event',
      'default_value' => 0,
      'parameters' => [
         'events' => [
            ['value' => 'tilt'],
            ['value' => 'fall'],
         ]
      ]
   ],
   'open_sensor' => [
      'instance_name' => 'open_sensor',
      'description' => 'Датчик открытия/закрытия',
      'capability' => 'event',
      'default_value' => 0,
      'parameters' => [
         'events' => [
            ['value' => 'opened'],
            ['value' => 'closed'],
         ]
      ]
   ],
   'button_sensor' => [
      'instance_name' => 'button_sensor',
      'description' => 'Событие нажатия кнопки',
      'capability' => 'event',
      'default_value' => 0,
      'parameters' => [
         'events' => [
            ['value' => 'click'],
            ['value' => 'double_click'],
            ['value' => 'long_press'],
         ]
      ]
   ],
   'motion_sensor' => [
      'instance_name' => 'motion_sensor',
      'description' => 'Датчик движения',
      'capability' => 'event',
      'default_value' => 0,
      'parameters' => [
         'events' => [
            ['value' => 'detected'],
            ['value' => 'not_detected'],
         ]
      ]
   ],
   'smoke_sensor' => [
      'instance_name' => 'smoke_sensor',
      'description' => 'Датчик дыма',
      'capability' => 'event',
      'default_value' => 0,
      'parameters' => [
         'events' => [
            ['value' => 'detected'],
            ['value' => 'not_detected'],
            ['value' => 'high'],
         ]
      ]
   ],
   'gas_sensor' => [
      'instance_name' => 'gas_sensor',
      'description' => 'Датчик наличия газа в помещении',
      'capability' => 'event',
      'default_value' => 0,
      'parameters' => [
         'events' => [
            ['value' => 'detected'],
            ['value' => 'not_detected'],
            ['value' => 'high'],
         ]
      ]
   ],
   'water_leak_sensor' => [
      'instance_name' => 'water_leak_sensor',
      'description' => 'Датчик протечки',
      'capability' => 'event',
      'default_value' => 0,
      'parameters' => [
         'events' => [
            ['value' => 'dry'],
            ['value' => 'leak'],
         ]
      ]
   ],
];
