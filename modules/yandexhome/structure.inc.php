<?php

$this->devices_type = [
   'light' => ['device_name' => 'light', 'description' => 'Свет'],
   'light.strip' => ['device_name' => 'light.strip', 'description' => 'Светодиодная лента'],
   'light.ceiling' => ['device_name' => 'light.ceiling', 'description' => 'Люстра'],
   'light.lamp' => ['device_name' => 'light.lamp', 'description' => 'Лампа'],
   'light.garland' => ['device_name' => 'light.garland', 'description' => 'Гирлянда'],
   'light.sconce' => ['device_name' => 'light.sconce', 'description' => 'Бра'],
   'light.torchere' => ['device_name' => 'light.torchere', 'description' => 'Торшер'],
   'light.dimmable' => ['device_name' => 'light.dimmable', 'description' => 'Диммируемый свет'],
   'socket' => ['device_name' => 'socket', 'description' => 'Розетка'],
   'switch' => ['device_name' => 'switch', 'description' => 'Выключатель'],
   'switch.relay' => ['device_name' => 'switch.relay', 'description' => 'Реле'],
   'thermostat' => ['device_name' => 'thermostat', 'description' => 'Термостат'],
   'thermostat.ac' => ['device_name' => 'thermostat.ac', 'description' => 'Кондиционер'],
   'thermostat.heater' => ['device_name' => 'thermostat.heater', 'description' => 'Обогреватель'],
   'media_device' => ['device_name' => 'media_device', 'description' => 'Медиаустройство'],
   'media_device.tv' => ['device_name' => 'media_device.tv', 'description' => 'Телевизор'],
   'media_device.tv_box' => ['device_name' => 'media_device.tv_box', 'description' => 'ТВ-приставка'],
   'media_device.receiver' => ['device_name' => 'media_device.receiver', 'description' => 'Ресивер'],
   'camera' => ['device_name' => 'camera', 'description' => 'Камера'],
   'cooking' => ['device_name' => 'cooking', 'description' => 'Кухонная техника'],
   'cooking.coffee_maker' => ['device_name' => 'cooking.coffee_maker', 'description' => 'Кофеварка'],
   'cooking.kettle' => ['device_name' => 'cooking.kettle', 'description' => 'Чайник'],
   'cooking.multicooker' => ['device_name' => 'cooking.multicooker', 'description' => 'Мультиварка'],
   'refrigerator' => ['device_name' => 'refrigerator', 'description' => 'Холодильник'],
   'remote_car' => ['device_name' => 'remote_car', 'description' => 'Автомобиль'],
   'openable' => ['device_name' => 'openable', 'description' => 'Открываемое устройство'],
   'openable.barrier' => ['device_name' => 'openable.barrier', 'description' => 'Шлагбаум/ворота'],
   'openable.curtain' => ['device_name' => 'openable.curtain', 'description' => 'Шторы/жалюзи'],
   'openable.door_lock' => ['device_name' => 'openable.door_lock', 'description' => 'Замок двери'],
   'openable.intercom' => ['device_name' => 'openable.intercom', 'description' => 'Домофон'],
   'openable.valve' => ['device_name' => 'openable.valve', 'description' => 'Клапан'],
   'humidifier' => ['device_name' => 'humidifier', 'description' => 'Увлажнитель'],
   'purifier' => ['device_name' => 'purifier', 'description' => 'Очиститель воздуха'],
   'vacuum_cleaner' => ['device_name' => 'vacuum_cleaner', 'description' => 'Пылесос'],
   'washing_machine' => ['device_name' => 'washing_machine', 'description' => 'Стиральная машина'],
   'dishwasher' => ['device_name' => 'dishwasher', 'description' => 'Посудомоечная машина'],
   'iron' => ['device_name' => 'iron', 'description' => 'Утюг/парогенератор'],
   'sensor' => ['device_name' => 'sensor', 'description' => 'Датчик'],
   'sensor.motion' => ['device_name' => 'sensor.motion', 'description' => 'Датчик движения'],
   'sensor.vibration' => ['device_name' => 'sensor.vibration', 'description' => 'Датчик вибрации'],
   'sensor.illumination' => ['device_name' => 'sensor.illumination', 'description' => 'Датчик освещенности'],
   'sensor.open' => ['device_name' => 'sensor.open', 'description' => 'Датчик открытия'],
   'sensor.climate' => ['device_name' => 'sensor.climate', 'description' => 'Климатический датчик'],
   'sensor.water_leak' => ['device_name' => 'sensor.water_leak', 'description' => 'Датчик протечки'],
   'sensor.button' => ['device_name' => 'sensor.button', 'description' => 'Кнопка/событие кнопки'],
   'sensor.gas' => ['device_name' => 'sensor.gas', 'description' => 'Датчик газа'],
   'sensor.smoke' => ['device_name' => 'sensor.smoke', 'description' => 'Датчик дыма'],
   'smart_meter' => ['device_name' => 'smart_meter', 'description' => 'Счетчик'],
   'smart_meter.cold_water' => ['device_name' => 'smart_meter.cold_water', 'description' => 'Счетчик холодной воды'],
   'smart_meter.electricity' => ['device_name' => 'smart_meter.electricity', 'description' => 'Счетчик электричества'],
   'smart_meter.gas' => ['device_name' => 'smart_meter.gas', 'description' => 'Счетчик газа'],
   'smart_meter.heat' => ['device_name' => 'smart_meter.heat', 'description' => 'Счетчик тепла'],
   'smart_meter.hot_water' => ['device_name' => 'smart_meter.hot_water', 'description' => 'Счетчик горячей воды'],
   'pet_drinking_fountain' => ['device_name' => 'pet_drinking_fountain', 'description' => 'Поилка для животных'],
   'pet_feeder' => ['device_name' => 'pet_feeder', 'description' => 'Кормушка для животных'],
   'ventilation' => ['device_name' => 'ventilation', 'description' => 'Вентиляция'],
   'ventilation.fan' => ['device_name' => 'ventilation.fan', 'description' => 'Вентилятор'],
   'other' => ['device_name' => 'other', 'description' => 'Другое устройство']
];

// Icon mapping for device types without dedicated local SVG.
$icon_map = [
   'light.strip' => 'light',
   'light.ceiling' => 'light',
   'light.lamp' => 'light',
   'light.garland' => 'light',
   'light.sconce' => 'light',
   'light.torchere' => 'light',
   'light.dimmable' => 'light',
   'switch.relay' => 'switch',
   'thermostat.heater' => 'thermostat',
   'camera' => 'sensor',
   'refrigerator' => 'other',
   'remote_car' => 'other',
   'openable.barrier' => 'openable',
   'openable.door_lock' => 'openable',
   'openable.intercom' => 'openable',
   'openable.valve' => 'openable',
   'sensor.motion' => 'sensor',
   'sensor.vibration' => 'sensor',
   'sensor.illumination' => 'sensor',
   'sensor.open' => 'sensor',
   'sensor.climate' => 'sensor',
   'sensor.water_leak' => 'sensor',
   'sensor.button' => 'sensor',
   'sensor.gas' => 'sensor',
   'sensor.smoke' => 'sensor',
   'smart_meter' => 'sensor',
   'smart_meter.cold_water' => 'sensor',
   'smart_meter.electricity' => 'sensor',
   'smart_meter.gas' => 'sensor',
   'smart_meter.heat' => 'sensor',
   'smart_meter.hot_water' => 'sensor',
   'pet_drinking_fountain' => 'other',
   'pet_feeder' => 'other',
   'ventilation' => 'thermostat',
   'ventilation.fan' => 'thermostat'
];

foreach ($this->devices_type as $type_name => &$type_data) {
   $icon_name = $type_name;
   if (isset($icon_map[$type_name])) {
      $icon_name = $icon_map[$type_name];
   }
   $type_data['icon_name'] = $icon_name;
   $type_data['icon_webp_name'] = str_replace(['.', '_'], '-', $type_name);
}
unset($type_data);

$this->devices_instance = [
   // capabilities: on_off
   'on' => [
      'instance_name' => 'on',
      'instance' => 'on',
      'description' => 'Включить/выключить',
      'capability' => 'on_off',
      'default_value' => 0,
      'value_type' => 'bool',
      'parameters' => ['split' => false]
   ],

   // capabilities: toggle
   'backlight' => ['instance_name' => 'backlight', 'instance' => 'backlight', 'description' => 'Подсветка', 'capability' => 'toggle', 'default_value' => 0, 'value_type' => 'bool'],
   'controls_locked' => ['instance_name' => 'controls_locked', 'instance' => 'controls_locked', 'description' => 'Блокировка управления', 'capability' => 'toggle', 'default_value' => 0, 'value_type' => 'bool'],
   'ionization' => ['instance_name' => 'ionization', 'instance' => 'ionization', 'description' => 'Ионизация', 'capability' => 'toggle', 'default_value' => 0, 'value_type' => 'bool'],
   'keep_warm' => ['instance_name' => 'keep_warm', 'instance' => 'keep_warm', 'description' => 'Поддержание тепла', 'capability' => 'toggle', 'default_value' => 0, 'value_type' => 'bool'],
   'mute' => ['instance_name' => 'mute', 'instance' => 'mute', 'description' => 'Без звука', 'capability' => 'toggle', 'default_value' => 0, 'value_type' => 'bool'],
   'oscillation' => ['instance_name' => 'oscillation', 'instance' => 'oscillation', 'description' => 'Поворот/качание', 'capability' => 'toggle', 'default_value' => 0, 'value_type' => 'bool'],
   'pause' => ['instance_name' => 'pause', 'instance' => 'pause', 'description' => 'Пауза', 'capability' => 'toggle', 'default_value' => 0, 'value_type' => 'bool'],

   // capabilities: range
   'brightness' => [
      'instance_name' => 'brightness',
      'instance' => 'brightness',
      'description' => 'Яркость',
      'capability' => 'range',
      'default_value' => 50,
      'value_type' => 'int',
      'parameters' => [
         'unit' => 'unit.percent',
         'random_access' => true,
         'range' => ['min' => 1, 'max' => 100, 'precision' => 1]
      ]
   ],
   'channel' => [
      'instance_name' => 'channel',
      'instance' => 'channel',
      'description' => 'Канал',
      'capability' => 'range',
      'default_value' => 1,
      'value_type' => 'int',
      'parameters' => [
         'random_access' => true,
         'range' => ['min' => 0, 'max' => 999, 'precision' => 1]
      ]
   ],
   'humidity' => [
      'instance_name' => 'humidity',
      'instance' => 'humidity',
      'description' => 'Влажность',
      'capability' => 'range',
      'default_value' => 40,
      'value_type' => 'int',
      'parameters' => [
         'unit' => 'unit.percent',
         'random_access' => true,
         'range' => ['min' => 0, 'max' => 100, 'precision' => 1]
      ]
   ],
   'open' => [
      'instance_name' => 'open',
      'instance' => 'open',
      'description' => 'Степень открытия',
      'capability' => 'range',
      'default_value' => 0,
      'value_type' => 'int',
      'parameters' => [
         'unit' => 'unit.percent',
         'random_access' => true,
         'range' => ['min' => 0, 'max' => 100, 'precision' => 1]
      ]
   ],
   'temperature' => [
      'instance_name' => 'temperature',
      'instance' => 'temperature',
      'description' => 'Температура',
      'capability' => 'range',
      'default_value' => 22,
      'value_type' => 'int',
      'parameters' => [
         'unit' => 'unit.temperature.celsius',
         'random_access' => true,
         'range' => ['min' => 1, 'max' => 100, 'precision' => 1]
      ]
   ],
   'volume' => [
      'instance_name' => 'volume',
      'instance' => 'volume',
      'description' => 'Громкость',
      'capability' => 'range',
      'default_value' => 20,
      'value_type' => 'int',
      'parameters' => [
         'random_access' => true,
         'range' => ['min' => 1, 'max' => 100, 'precision' => 1]
      ]
   ],

   // capabilities: mode
   'cleanup_mode' => [
      'instance_name' => 'cleanup_mode',
      'instance' => 'cleanup_mode',
      'description' => 'Режим уборки',
      'capability' => 'mode',
      'default_value' => 'auto',
      'value_type' => 'string',
      'parameters' => ['modes' => [['value' => 'wet_cleaning'], ['value' => 'dry_cleaning'], ['value' => 'mixed_cleaning'], ['value' => 'auto'], ['value' => 'eco'], ['value' => 'smart'], ['value' => 'turbo'], ['value' => 'quiet'], ['value' => 'normal'], ['value' => 'fast'], ['value' => 'max'], ['value' => 'low']]]
   ],
   'coffee_mode' => [
      'instance_name' => 'coffee_mode',
      'instance' => 'coffee_mode',
      'description' => 'Режим кофеварки',
      'capability' => 'mode',
      'default_value' => 'americano',
      'value_type' => 'string',
      'parameters' => ['modes' => [['value' => 'americano'], ['value' => 'cappuccino'], ['value' => 'double'], ['value' => 'espresso'], ['value' => 'double_espresso'], ['value' => 'latte']]]
   ],
   'dishwashing' => [
      'instance_name' => 'dishwashing',
      'instance' => 'dishwashing',
      'description' => 'Режим мойки посуды',
      'capability' => 'mode',
      'default_value' => 'auto',
      'value_type' => 'string',
      'parameters' => ['modes' => [['value' => 'auto'], ['value' => 'eco'], ['value' => 'normal'], ['value' => 'intensive'], ['value' => 'express'], ['value' => 'glass'], ['value' => 'pre_rinse'], ['value' => 'fast']]]
   ],
   'fan_speed' => [
      'instance_name' => 'fan_speed',
      'instance' => 'fan_speed',
      'description' => 'Скорость вентиляции',
      'capability' => 'mode',
      'default_value' => 'auto',
      'value_type' => 'string',
      'parameters' => ['modes' => [['value' => 'auto'], ['value' => 'eco'], ['value' => 'quiet'], ['value' => 'low'], ['value' => 'medium'], ['value' => 'normal'], ['value' => 'high'], ['value' => 'turbo'], ['value' => 'max'], ['value' => 'min']]]
   ],
   'heat' => [
      'instance_name' => 'heat',
      'instance' => 'heat',
      'description' => 'Режим нагрева',
      'capability' => 'mode',
      'default_value' => 'one',
      'value_type' => 'string',
      'parameters' => ['modes' => [['value' => 'one'], ['value' => 'two'], ['value' => 'three'], ['value' => 'four'], ['value' => 'five'], ['value' => 'six'], ['value' => 'seven'], ['value' => 'eight'], ['value' => 'nine'], ['value' => 'ten']]]
   ],
   'input_source' => [
      'instance_name' => 'input_source',
      'instance' => 'input_source',
      'description' => 'Источник сигнала',
      'capability' => 'mode',
      'default_value' => 'one',
      'value_type' => 'string',
      'parameters' => ['modes' => [['value' => 'one'], ['value' => 'two'], ['value' => 'three'], ['value' => 'four'], ['value' => 'five'], ['value' => 'six'], ['value' => 'seven'], ['value' => 'eight'], ['value' => 'nine'], ['value' => 'ten']]]
   ],
   'program' => [
      'instance_name' => 'program',
      'instance' => 'program',
      'description' => 'Программа',
      'capability' => 'mode',
      'default_value' => 'auto',
      'value_type' => 'string',
      'parameters' => ['modes' => [['value' => 'auto'], ['value' => 'eco'], ['value' => 'quiet'], ['value' => 'normal'], ['value' => 'turbo'], ['value' => 'min'], ['value' => 'medium'], ['value' => 'max'], ['value' => 'express'], ['value' => 'fast'], ['value' => 'slow'], ['value' => 'smart'], ['value' => 'high'], ['value' => 'low'], ['value' => 'aspic'], ['value' => 'baby_food'], ['value' => 'baking'], ['value' => 'bread'], ['value' => 'boiling'], ['value' => 'cereals'], ['value' => 'cheesecake'], ['value' => 'deep_fryer'], ['value' => 'dessert'], ['value' => 'fowl'], ['value' => 'frying'], ['value' => 'macaroni'], ['value' => 'milk_porridge'], ['value' => 'multicooker'], ['value' => 'pasta'], ['value' => 'pilaf'], ['value' => 'pizza'], ['value' => 'sauce'], ['value' => 'slow_cook'], ['value' => 'soup'], ['value' => 'steam'], ['value' => 'stewing'], ['value' => 'vacuum'], ['value' => 'yogurt']]]
   ],
   'swing' => [
      'instance_name' => 'swing',
      'instance' => 'swing',
      'description' => 'Направление потока',
      'capability' => 'mode',
      'default_value' => 'stationary',
      'value_type' => 'string',
      'parameters' => ['modes' => [['value' => 'horizontal'], ['value' => 'vertical'], ['value' => 'stationary'], ['value' => 'auto'], ['value' => 'turbo']]]
   ],
   'tea_mode' => [
      'instance_name' => 'tea_mode',
      'instance' => 'tea_mode',
      'description' => 'Режим приготовления чая',
      'capability' => 'mode',
      'default_value' => 'black_tea',
      'value_type' => 'string',
      'parameters' => ['modes' => [['value' => 'black_tea'], ['value' => 'flower_tea'], ['value' => 'green_tea'], ['value' => 'herbal_tea'], ['value' => 'oolong_tea'], ['value' => 'puerh_tea'], ['value' => 'red_tea'], ['value' => 'white_tea'], ['value' => 'express']]]
   ],
   'thermostat' => [
      'instance_name' => 'thermostat',
      'instance' => 'thermostat',
      'description' => 'Температурный режим',
      'capability' => 'mode',
      'default_value' => 'auto',
      'value_type' => 'string',
      'parameters' => ['modes' => [['value' => 'auto'], ['value' => 'cool'], ['value' => 'dry'], ['value' => 'fan_only'], ['value' => 'heat'], ['value' => 'preheat'], ['value' => 'eco']]]
   ],
   'ventilation_mode' => [
      'instance_name' => 'ventilation_mode',
      'instance' => 'ventilation_mode',
      'description' => 'Режим вентиляции',
      'capability' => 'mode',
      'default_value' => 'auto',
      'value_type' => 'string',
      'parameters' => ['modes' => [['value' => 'auto'], ['value' => 'supply_air'], ['value' => 'extraction_air']]]
   ],
   'work_speed' => [
      'instance_name' => 'work_speed',
      'instance' => 'work_speed',
      'description' => 'Скорость работы',
      'capability' => 'mode',
      'default_value' => 'normal',
      'value_type' => 'string',
      'parameters' => ['modes' => [['value' => 'auto'], ['value' => 'eco'], ['value' => 'quiet'], ['value' => 'low'], ['value' => 'medium'], ['value' => 'normal'], ['value' => 'high'], ['value' => 'turbo'], ['value' => 'max'], ['value' => 'min'], ['value' => 'fast'], ['value' => 'slow']]]
   ],

   // capabilities: color_setting
   'rgb' => [
      'instance_name' => 'rgb',
      'instance' => 'rgb',
      'description' => 'Цвет RGB',
      'capability' => 'color_setting',
      'default_value' => '000000',
      'value_type' => 'rgb',
      'parameters' => ['color_model' => 'rgb']
   ],
   'temperature_k' => [
      'instance_name' => 'temperature_k',
      'instance' => 'temperature_k',
      'description' => 'Цветовая температура',
      'capability' => 'color_setting',
      'default_value' => 4500,
      'value_type' => 'int',
      'parameters' => ['temperature_k' => ['min' => 1500, 'max' => 9000]]
   ],
   'scene' => [
      'instance_name' => 'scene',
      'instance' => 'scene',
      'description' => 'Цветовая сцена',
      'capability' => 'color_setting',
      'default_value' => 'alarm',
      'value_type' => 'string',
      'parameters' => [
         'color_scene' => [
            'scenes' => [
               ['id' => 'alarm'], ['id' => 'alice'], ['id' => 'candle'], ['id' => 'dinner'], ['id' => 'fantasy'],
               ['id' => 'garland'], ['id' => 'jungle'], ['id' => 'movie'], ['id' => 'neon'], ['id' => 'night'],
               ['id' => 'ocean'], ['id' => 'party'], ['id' => 'reading'], ['id' => 'rest'], ['id' => 'romance'],
               ['id' => 'siren'], ['id' => 'sunrise'], ['id' => 'sunset']
            ]
         ]
      ]
   ],

   // capabilities: video_stream
   'get_stream' => [
      'instance_name' => 'get_stream',
      'instance' => 'get_stream',
      'description' => 'Видеопоток камеры (HLS URL в привязанном свойстве)',
      'capability' => 'video_stream',
      'default_value' => '',
      'value_type' => 'stream_url',
      'retrievable' => false,
      'reportable_fixed' => false,
      'parameters' => ['protocols' => ['hls']]
   ],

   // properties: float
   'amperage_sensor' => ['instance_name' => 'amperage_sensor', 'instance' => 'amperage', 'description' => 'Ток (A)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.ampere']],
   'battery_level_sensor' => ['instance_name' => 'battery_level_sensor', 'instance' => 'battery_level', 'description' => 'Уровень заряда (%)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.percent']],
   'co2_level_sensor' => ['instance_name' => 'co2_level_sensor', 'instance' => 'co2_level', 'description' => 'CO2 (ppm)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.ppm']],
   'electricity_meter_sensor' => ['instance_name' => 'electricity_meter_sensor', 'instance' => 'electricity_meter', 'description' => 'Счетчик электроэнергии (кВт⋅ч)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.kilowatt_hour']],
   'food_level_sensor' => ['instance_name' => 'food_level_sensor', 'instance' => 'food_level', 'description' => 'Уровень корма (%)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.percent']],
   'gas_meter_sensor' => ['instance_name' => 'gas_meter_sensor', 'instance' => 'gas_meter', 'description' => 'Счетчик газа (м³)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.cubic_meter']],
   'heat_meter_sensor' => ['instance_name' => 'heat_meter_sensor', 'instance' => 'heat_meter', 'description' => 'Счетчик тепла (Гкал)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.gigacalorie']],
   'humidity_sensor' => ['instance_name' => 'humidity_sensor', 'instance' => 'humidity', 'description' => 'Влажность (%)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.percent']],
   'illumination_sensor' => ['instance_name' => 'illumination_sensor', 'instance' => 'illumination', 'description' => 'Освещенность (lux)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.illumination.lux']],
   'meter_sensor' => ['instance_name' => 'meter_sensor', 'instance' => 'meter', 'description' => 'Универсальный счетчик', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float'],
   'pm1_density_sensor' => ['instance_name' => 'pm1_density_sensor', 'instance' => 'pm1_density', 'description' => 'PM1 (мкг/м³)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.density.mcg_m3']],
   'pm2_5_density_sensor' => ['instance_name' => 'pm2_5_density_sensor', 'instance' => 'pm2.5_density', 'description' => 'PM2.5 (мкг/м³)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.density.mcg_m3']],
   'pm10_density_sensor' => ['instance_name' => 'pm10_density_sensor', 'instance' => 'pm10_density', 'description' => 'PM10 (мкг/м³)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.density.mcg_m3']],
   'power_sensor' => ['instance_name' => 'power_sensor', 'instance' => 'power', 'description' => 'Мощность (W)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.watt']],
   'pressure_sensor' => ['instance_name' => 'pressure_sensor', 'instance' => 'pressure', 'description' => 'Давление (мм рт. ст.)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.pressure.mmhg']],
   'temperature_sensor' => ['instance_name' => 'temperature_sensor', 'instance' => 'temperature', 'description' => 'Температура (°C)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.temperature.celsius']],
   'tvoc_sensor' => ['instance_name' => 'tvoc_sensor', 'instance' => 'tvoc', 'description' => 'TVOC (мкг/м³)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.density.mcg_m3']],
   'voltage_sensor' => ['instance_name' => 'voltage_sensor', 'instance' => 'voltage', 'description' => 'Напряжение (V)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.volt']],
   'water_level_sensor' => ['instance_name' => 'water_level_sensor', 'instance' => 'water_level', 'description' => 'Уровень воды (%)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.percent']],
   'water_meter_sensor' => ['instance_name' => 'water_meter_sensor', 'instance' => 'water_meter', 'description' => 'Счетчик воды (м³)', 'capability' => 'float', 'default_value' => 0, 'value_type' => 'float', 'parameters' => ['unit' => 'unit.cubic_meter']],

   // properties: event
   'vibration_sensor' => [
      'instance_name' => 'vibration_sensor',
      'instance' => 'vibration',
      'description' => 'Датчик вибрации',
      'capability' => 'event',
      'default_value' => 'tilt',
      'value_type' => 'event',
      'parameters' => ['events' => [['value' => 'tilt'], ['value' => 'fall'], ['value' => 'vibration']]]
   ],
   'open_sensor' => [
      'instance_name' => 'open_sensor',
      'instance' => 'open',
      'description' => 'Датчик открытия',
      'capability' => 'event',
      'default_value' => 'closed',
      'value_type' => 'event',
      'parameters' => ['events' => [['value' => 'opened'], ['value' => 'closed']]]
   ],
   'button_sensor' => [
      'instance_name' => 'button_sensor',
      'instance' => 'button',
      'description' => 'Событие кнопки',
      'capability' => 'event',
      'default_value' => 'click',
      'value_type' => 'event',
      'parameters' => ['events' => [['value' => 'click'], ['value' => 'double_click'], ['value' => 'long_press']]]
   ],
   'motion_sensor' => [
      'instance_name' => 'motion_sensor',
      'instance' => 'motion',
      'description' => 'Датчик движения',
      'capability' => 'event',
      'default_value' => 'not_detected',
      'value_type' => 'event',
      'parameters' => ['events' => [['value' => 'detected'], ['value' => 'not_detected']]]
   ],
   'smoke_sensor' => [
      'instance_name' => 'smoke_sensor',
      'instance' => 'smoke',
      'description' => 'Датчик дыма',
      'capability' => 'event',
      'default_value' => 'not_detected',
      'value_type' => 'event',
      'parameters' => ['events' => [['value' => 'detected'], ['value' => 'not_detected'], ['value' => 'high']]]
   ],
   'gas_sensor' => [
      'instance_name' => 'gas_sensor',
      'instance' => 'gas',
      'description' => 'Датчик газа',
      'capability' => 'event',
      'default_value' => 'not_detected',
      'value_type' => 'event',
      'parameters' => ['events' => [['value' => 'detected'], ['value' => 'not_detected'], ['value' => 'high']]]
   ],
   'water_leak_sensor' => [
      'instance_name' => 'water_leak_sensor',
      'instance' => 'water_leak',
      'description' => 'Датчик протечки',
      'capability' => 'event',
      'default_value' => 'dry',
      'value_type' => 'event',
      'parameters' => ['events' => [['value' => 'dry'], ['value' => 'leak']]]
   ],
   'battery_level_event_sensor' => [
      'instance_name' => 'battery_level_event_sensor',
      'instance' => 'battery_level',
      'description' => 'Событие уровня батареи',
      'capability' => 'event',
      'default_value' => 'normal',
      'value_type' => 'event',
      'parameters' => ['events' => [['value' => 'low'], ['value' => 'normal']]]
   ],
   'food_level_event_sensor' => [
      'instance_name' => 'food_level_event_sensor',
      'instance' => 'food_level',
      'description' => 'Событие уровня корма',
      'capability' => 'event',
      'default_value' => 'normal',
      'value_type' => 'event',
      'parameters' => ['events' => [['value' => 'empty'], ['value' => 'low'], ['value' => 'normal']]]
   ],
   'water_level_event_sensor' => [
      'instance_name' => 'water_level_event_sensor',
      'instance' => 'water_level',
      'description' => 'Событие уровня воды',
      'capability' => 'event',
      'default_value' => 'normal',
      'value_type' => 'event',
      'parameters' => ['events' => [['value' => 'empty'], ['value' => 'low'], ['value' => 'normal']]]
   ]
];

$this->legacy_instance_aliases = [
   'pm2.5_density_sensor' => 'pm2_5_density_sensor',
   'pm2_5_density_sensor' => 'pm2_5_density_sensor',
];

$this->value_map_presets = [
   'none' => [
      'label' => 'Без преобразования',
      'map' => []
   ],
   'custom' => [
      'label' => 'Пользовательский',
      'map' => []
   ],
   'bool_onoff_10' => [
      'label' => '1/0 -> on/off',
      'map' => ['1' => 'on', '0' => 'off', 'true' => 'on', 'false' => 'off']
   ],
   'bool_open_closed_10' => [
      'label' => '1/0 -> opened/closed',
      'map' => ['1' => 'opened', '0' => 'closed', 'true' => 'opened', 'false' => 'closed']
   ],
   'bool_detected_10' => [
      'label' => '1/0 -> detected/not_detected',
      'map' => ['1' => 'detected', '0' => 'not_detected', 'true' => 'detected', 'false' => 'not_detected']
   ],
   'bool_leak_10' => [
      'label' => '1/0 -> leak/dry',
      'map' => ['1' => 'leak', '0' => 'dry', 'true' => 'leak', 'false' => 'dry']
   ],
   'level_012_food_water' => [
      'label' => '0/1/2 -> empty/low/normal',
      'map' => ['0' => 'empty', '1' => 'low', '2' => 'normal']
   ],
   'button_123' => [
      'label' => '1/2/3 -> click/double_click/long_press',
      'map' => ['1' => 'click', '2' => 'double_click', '3' => 'long_press']
   ]
];
