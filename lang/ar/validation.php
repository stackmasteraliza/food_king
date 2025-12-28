<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'يجب قبول :attribute.',
    'accepted_if'          => 'يجب قبول :attribute عندما :other يكون :value.',
    'active_url'           => ':attribute ليس عنوان URL صالحًا.',
    'after'                => ':attribute يجب أن يكون تاريخًا بعد :date.',
    'after_or_equal'       => ':attribute يجب أن يكون تاريخًا بعد أو يساوي :date.',
    'alpha'                => ':attribute يجب أن يحتوي فقط على أحرف.',
    'alpha_dash'           => ':attribute يجب أن يحتوي فقط على أحرف وأرقام وشرطات وشرطات سفلية.',
    'alpha_num'            => ':attribute يجب أن يحتوي فقط على أحرف وأرقام.',
    'array'                => ':attribute يجب أن يكون مصفوفة.',
    'before'               => ':attribute يجب أن يكون تاريخًا قبل :date.',
    'before_or_equal'      => ':attribute يجب أن يكون تاريخًا قبل أو يساوي :date.',
    'between'              => [
        'array'   => ':attribute يجب أن يكون بين :min و :max عنصرًا.',
        'file'    => ':attribute يجب أن يكون بين :min و :max كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون بين :min و :max.',
        'string'  => ':attribute يجب أن يكون بين :min و :max حرفًا.',
    ],
    'boolean'              => 'حقل :attribute يجب أن يكون صحيحًا أو خاطئًا.',
    'confirmed'            => 'تأكيد :attribute غير متطابق.',
    'current_password'     => 'كلمة المرور غير صحيحة.',
    'date'                 => ':attribute ليس تاريخًا صالحًا.',
    'date_equals'          => ':attribute يجب أن يكون تاريخًا يساوي :date.',
    'date_format'          => ':attribute لا يتطابق مع الشكل :format.',
    'declined'             => 'يجب رفض :attribute.',
    'declined_if'          => 'يجب رفض :attribute عندما يكون :other هو :value.',
    'different'            => ':attribute و :other يجب أن يكونا مختلفين.',
    'digits'               => ':attribute يجب أن يكون :digits أرقام.',
    'digits_between'       => ':attribute يجب أن يكون بين :min و :max أرقام.',
    'dimensions'           => ':attribute له أبعاد صورة غير صالحة.',
    'distinct'             => 'حقل :attribute يحتوي على قيمة مكررة.',
    'doesnt_end_with'      => ':attribute قد لا ينتهي بأحد القيم التالية: :values.',
    'doesnt_start_with'    => ':attribute قد لا يبدأ بأحد القيم التالية: :values.',
    'email'                => ':attribute يجب أن يكون عنوان بريد إلكتروني صالح.',
    'ends_with'            => ':attribute يجب أن ينتهي بأحد القيم التالية: :values.',
    'enum'                 => ':attribute المحددة غير صالحة.',
    'exists'               => ':attribute المحدد غير صالح.',
    'file'                 => ':attribute يجب أن يكون ملفًا.',
    'filled'               => 'حقل :attribute يجب أن يحتوي على قيمة.',
    'gt'                   => [
        'array'   => ':attribute يجب أن يحتوي على أكثر من :value عنصرًا.',
        'file'    => ':attribute يجب أن يكون أكبر من :value كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون أكبر من :value.',
        'string'  => ':attribute يجب أن يكون أكبر من :value حرفًا.',
    ],
    'gte'                  => [
        'array'   => ':attribute يجب أن يحتوي على :value عنصرًا أو أكثر.',
        'file'    => ':attribute يجب أن يكون أكبر من أو يساوي :value كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون أكبر من أو يساوي :value.',
        'string'  => ':attribute يجب أن يكون أكبر من أو يساوي :value حرفًا.',
    ],
    'image'               => ':attribute يجب أن يكون صورة.',
    'in'                  => ':attribute المحدد غير صالح.',
    'in_array'            => 'حقل :attribute لا يوجد في :other.',
    'integer'             => ':attribute يجب أن يكون عددًا صحيحًا.',
    'ip'                  => ':attribute يجب أن يكون عنوان IP صالحًا.',
    'ipv4'                => ':attribute يجب أن يكون عنوان IPv4 صالحًا.',
    'ipv6'                => ':attribute يجب أن يكون عنوان IPv6 صالحًا.',
    'json'                => ':attribute يجب أن يكون سلسلة JSON صالحة.',
    'lowercase'           => ':attribute يج',
    'lt' => [
        'array'   => ':attribute يجب أن يحتوي على أقل من :value عنصر.',
        'file'    => ':attribute يجب أن يكون أقل من :value كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون أقل من :value.',
        'string'  => ':attribute يجب أن يكون أقل من :value حرفًا.',
    ],
    'lte' => [
        'array'   => ':attribute يجب أن لا يحتوي على أكثر من :value عنصر.',
        'file'    => ':attribute يجب أن يكون أقل من أو يساوي :value كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون أقل من أو يساوي :value.',
        'string'  => ':attribute يجب أن يكون أقل من أو يساوي :value حرفًا.',
    ],
    'mac_address' => ':attribute يجب أن يكون عنوان MAC صحيح.',
    'max' => [
        'array'   => ':attribute يجب أن لا يحتوي على أكثر من :max عنصر.',
        'file'    => ':attribute يجب أن لا يكون أكبر من :max كيلوبايت.',
        'numeric' => ':attribute يجب أن لا يكون أكبر من :max.',
        'string'  => ':attribute يجب أن لا يكون أكبر من :max حرف.',
    ],

    'max_digits' => ':attribute يجب ألا يحتوي على أكثر من :max أرقام.',
    'mimes' => ':attribute يجب أن يكون نوع الملف: :values.',
    'mimetypes' => ':attribute يجب أن يكون نوع الملف: :values.',
    'min' => [
        'array' => ':attribute يجب أن يحتوي على الأقل على :min عنصر.',
        'file' => ':attribute يجب أن يكون على الأقل :min كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون على الأقل :min.',
        'string' => ':attribute يجب أن يكون على الأقل :min حرف.',
    ],
    'min_digits' => ':attribute يجب أن يحتوي على الأقل على :min أرقام.',
    'multiple_of' => ':attribute يجب أن يكون مضاعفًا للقيمة :value.',
    'not_in' => ':attribute المحدد غير صالح.',
    'not_regex' => 'تنسيق :attribute غير صالح.',
    'numeric' => ':attribute يجب أن يكون رقمًا.',
    'password' => [
        'letters' => ':attribute يجب أن يحتوي على حرف واحد على الأقل.',
        'mixed' => ':attribute يجب أن يحتوي على حرف كبير وحرف صغير على الأقل.',
        'numbers' => ':attribute يجب أن يحتوي على رقم واحد على الأقل.',
        'symbols' => ':attribute يجب أن يحتوي على رمز واحد على الأقل.',
        'uncompromised' => ':attribute الذي تم إدخاله قد ظهر في تسريب بيانات. يرجى اختيار :attribute مختلف.',
    ],
    'present' => ':attribute يجب أن يكون موجودًا.',
    'prohibited' => 'حقل :attribute محظور.',
    'prohibited_if' => 'حقل :attribute محظور عندما يكون :other هو :value.',
    'prohibited_unless' => 'حقل :attribute محظور ما لم يكن :other في :values.',
    'prohibits' => 'حقل :attribute يمنع :other من الوجود.',
    'regex' => 'تنسيق :attribute غير صالح.',
    'required' => 'حقل :attribute مطلوب.',
    'required_array_keys' => 'حقل :attribute يجب أن يحتوي على مدخلات لـ :values.',
    'required_if' => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
    'required_if_accepted' => 'حقل :attribute مطلوب عند قبول :other.',
    'required_unless' => 'حقل :attribute مطلوب ما لم يكن :other في :values.',
    'required_with' => 'حقل :attribute مطلوب عند وجود :values.',
    'required_with_all' => 'حقل :attribute مطلوب عند وجود كل من :values.',
    'required_without' => 'حقل :attribute مطلوب عند عدم وجود :values.',
    'required_without_all' => 'حقل :attribute مطلوب عند عدم وجود أي من :values.',
    'same' => ':attribute و :other يجب أن يتطابقان.',
    'size' => [
        'array' => ':attribute يجب أن يحتوي على :size عنصر.',
        'file' => ':attribute يجب أن يكون حجمه :size كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون :size.',
        'string' => ':attribute يجب أن يكون طوله :size حرفًا.',
    ],
    'starts_with' => ':attribute يجب أن يبدأ بأحد القيم التالية: :values.',
    'string' => ':attribute يجب أن يكون نصًا.',
    'timezone' => ':attribute يجب أن يكون منطقة زمنية صالحة.',
    'unique' => ':attribute مأخوذ بالفعل.',
    'uploaded' => 'فشل في تحميل :attribute.',
    'uppercase' => ':attribute يجب أن يكون بأحرف كبيرة.',
    'url' => ':attribute يجب أن يكون عنوان URL صالحًا.',
    'uuid' => ':attribute يجب أن يكون UUID صالحًا.',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
