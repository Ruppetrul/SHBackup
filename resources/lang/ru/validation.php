<?php
return [
    'accepted'             => 'Вы должны принять :attribute.',
    'active_url'           => 'Поле :attribute содержит недействительный URL.',
    'after'                => 'В поле :attribute должна быть дата после :date.',
    'after_or_equal'       => 'В поле :attribute должна быть дата после или равная :date.',
    'alpha'                => 'Поле :attribute может содержать только буквы.',
    'alpha_dash'           => 'Поле :attribute может содержать только буквы, цифры, дефисы и подчеркивания.',
    'alpha_num'            => 'Поле :attribute может содержать только буквы и цифры.',
    'array'                => 'Поле :attribute должно быть массивом.',
    'before'               => 'В поле :attribute должна быть дата до :date.',
    'before_or_equal'      => 'В поле :attribute должна быть дата до или равная :date.',
    'between'              => [
        'numeric' => 'Значение :attribute должно быть между :min и :max.',
        'file'    => 'Размер :attribute должен быть между :min и :max килобайт.',
        'string'  => 'Длина :attribute должна быть между :min и :max символами.',
        'array'   => 'Количество элементов :attribute должно быть между :min и :max.',
    ],
    'boolean'              => 'Поле :attribute должно быть true или false.',
    'confirmed'            => 'Поле :attribute не совпадает с подтверждением.',
    'date'                 => 'Значение :attribute не является датой.',
    'date_equals'          => 'Поле :attribute должно быть датой равной :date.',
    'date_format'          => 'Значение :attribute не соответствует формату :format.',
    'different'            => 'Поля :attribute и :other должны различаться.',
    'digits'               => 'Длина :attribute должна быть :digits цифр.',
    'digits_between'       => 'Длина :attribute должна быть между :min и :max цифрами.',
    'dimensions'           => 'Изображение :attribute имеет недопустимые размеры.',
    'distinct'             => 'Поле :attribute имеет повторяющееся значение.',
    'email'                => 'Поле :attribute должно быть действительным электронным адресом.',
    'ends_with'            => 'Поле :attribute должно заканчиваться одним из следующих значений: :values.',
    'exists'               => 'Выбранное значение для :attribute некорректно.',
    'file'                 => 'Поле :attribute должно быть файлом.',
    'filled'               => 'Поле :attribute обязательно для заполнения.',
    'gt'                   => [
        'numeric' => 'Значение :attribute должно быть больше :value.',
        'file'    => 'Размер :attribute должен быть больше :value килобайт.',
        'string'  => 'Длина :attribute должна быть больше :value символов.',
        'array'   => 'Количество элементов :attribute должно быть больше :value.',
    ],
    'gte'                  => [
        'numeric' => 'Значение :attribute должно быть больше или равно :value.',
        'file'    => 'Размер :attribute должен быть больше или равен :value килобайт.',
        'string'  => 'Длина :attribute должна быть больше или равна :value символам.',
        'array'   => 'Количество элементов :attribute должно быть больше или равно :value.',
    ],
    'image'                => 'Поле :attribute должно быть изображением.',
    'in'                   => 'Выбранное значение для :attribute ошибочно.',
    'in_array'             => 'Поле :attribute не существует в :other.',
    'integer'              => 'Значение :attribute должно быть целым числом.',
    'ip'                   => 'Значение :attribute должно быть действительным IP-адресом.',
    'ipv4'                 => 'Значение :attribute должно быть действительным IPv4-адресом.',
    'ipv6'                 => 'Значение :attribute должно быть действительным IPv6-адресом.',
    'json'                 => 'Значение :attribute должно быть JSON строкой.',
    'lt'                   => [
        'numeric' => 'Значение :attribute должно быть меньше :value.',
        'file'    => 'Размер :attribute должен быть меньше :value килобайт.',
        'string'  => 'Длина :attribute должна быть меньше :value символов.',
        'array'   => 'Количество элементов :attribute должно быть меньше :value.',
    ],
    'lte'                  => [
        'numeric' => 'Значение :attribute должно быть меньше или равно :value.',
        'file'    => 'Размер :attribute должен быть меньше или равен :value килобайт.',
        'string'  => 'Длина :attribute должна быть меньше или равна :value символам.',
        'array'   => 'Количество элементов :attribute должно быть меньше или равно :value.',
    ],
    'max'                  => [
        'numeric' => 'Значение :attribute не может быть больше :max.',
        'file'    => 'Размер :attribute не может быть больше :max килобайт.',
        'string'  => 'Длина :attribute не может превышать :max символов.',
        'array'   => 'Количество элементов :attribute не может превышать :max.',
    ],
    'mimes'                => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'mimetypes'            => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'min'                  => [
        'numeric' => 'Значение :attribute должно быть не менее :min.',
        'file'    => 'Размер :attribute должен быть не менее :min килобайт.',
        'string'  => 'Длина :attribute должна быть не менее :min символов.',
        'array'   => 'Количество элементов :attribute должно быть не менее :min.',
    ],
    'not_in'               => 'Выбранное значение для :attribute ошибочно.',
    'not_regex'            => 'Формат :attribute неверный.',
    'numeric'              => 'Поле :attribute должно быть числом.',
    'password'             => 'Неверный пароль.',
    'present'              => 'Поле :attribute должно присутствовать.',
    'regex'                => 'Поле :attribute имеет ошибочный формат.',
    'required'             => 'Поле :attribute обязательно для заполнения.',
    'required_if'          => 'Поле :attribute обязательно, когда :other равно :value.',
    'required_unless'      => 'Поле :attribute обязательно, если :other не находится в :values.',
    'required_with'        => 'Поле :attribute обязательно, когда :values указано.',
    'required_with_all'    => 'Поле :attribute обязательно, когда указано хотя бы одно из :values.',
    'required_without'     => 'Поле :attribute обязательно, когда :values не указано.',
    'required_without_all' => 'Поле :attribute обязательно, когда ни одно из :values не указано.',
    'same'                 => 'Значения :attribute и :other должны совпадать.',
    'size'                 => [
        'numeric' => 'Поле :attribute должно быть :size.',
        'file'    => 'Размер :attribute должен быть :size килобайт.',
        'string'  => 'Длина :attribute должна быть :size символов.',
        'array'   => 'Количество элементов :attribute должно быть :size.',
    ],
    'starts_with'          => 'Поле :attribute должно начинаться с одного из следующих значений: :values.',
    'string'               => 'Поле :attribute должно быть строкой.',
    'timezone'             => 'Поле :attribute должно быть действительным часовым поясом.',
    'unique'               => 'Это значение уже используется.',
    'uploaded'             => 'Ошибка загрузки :attribute.',
    'url'                  => 'Формат :attribute неверный.',
    'uuid'                 => 'Поле :attribute должно быть корректным UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],
];
