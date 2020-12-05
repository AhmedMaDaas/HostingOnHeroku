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

	    'accepted'             => 'حقل :attribute يجب أن يكون مقبول',
	    'active_url'           => 'حقل :attribute ليس URL صالح',
	    'after'                => 'حقل :attribute يجب أن يكون تاريخ بعد :date',
	    'after_or_equal'       => 'حقل :attribute يجب أن يكون تاريخ بعد أو يساوي :date',
	    'alpha'                => 'حقل :attribute يجب أن يحتوي على أحرف فقط',
	    'alpha_dash'           => 'حقل :attribute يجب أن يحتوي على أحرف, أرقام و داش فقط',
	    'alpha_num'            => 'حقل :attribute يجب أن يحتوي على أحرف و أرقام فقط',
	    'array'                => 'حقل :attribute يجب أن يكون مصفوفة',
	    'before'               => 'حقل :attribute يجب أن يكون تاريخ قبل :date',
	    'before_or_equal'      => 'حقل :attribute يجب أن يكون تاريخ قبل أو يساوي :date',
	    'between'              => [
	        'numeric' => 'حقل :attribute يجب أن يكون بين :min و :max',
	        'file'    => 'حقل :attribute يجب أن يكون بين :min و :max كيلوبايت',
	        'string'  => 'حقل :attribute يجب أن يكون بين :min و :max محرف',
	        'array'   => 'حقل :attribute يجب أن يحتوي على :min إلى :max عنصر',
	    ],
	    'boolean'              => 'حقل :attribute يجب أن يكون True أو False',
	    'confirmed'            => 'حقل تأكيد :attribute غير متطابق',
	    'date'                 => 'حقل :attribute تاريخ غير صالح',
	    'date_format'          => 'صيغة حقل :attribute لا تتطابق مع صيغة :format',
	    'different'            => 'حقل :attribute و :other يجب ان يكونا مختلفان',
	    'digits'               => 'حقل :attribute يجب أن يكون :digits أرقام',
	    'digits_between'       => 'حقل :attribute يجب أن يكون بين :min و :max رقم',
	    'dimensions'           => 'حقل :attribute أبعاد صورة غير صالحة',
	    'distinct'             => 'حقل :attribute يحوي قيمة متكررة',
	    'email'                => 'حقل :attribute يجب أن يكون عنوان بريد إلكاروني صالح',
	    'exists'               => 'حقل :attribute المختار غير صالح',
	    'file'                 => 'حقل :attribute يجب أن يكون ملف',
	    'filled'               => 'حقل :attribute يجب أن يحوي قيمة',
	    'image'                => 'حقل :attribute يجب أن يكون صورة',
	    'in'                   => 'حقل :attribute المختار غير صالح',
	    'in_array'             => 'حقل :attribute غير موجود في :other',
	    'integer'              => 'حقل :attribute يجب أن يكون عدد صحيح',
	    'ip'                   => 'حقل :attribute يجب أن يكون عنوان IP صالح',
	    'ipv4'                 => 'حقل :attribute يجب أن يكون عنوان IPv4 صالح',
	    'ipv6'                 => 'حقل :attribute يجب أن يكون عنوان IPv6 صالح',
	    'json'                 => 'حقل :attribute يجب أن يكون بصيغة JSON صالحة',
	    'max'                  => [
	        'numeric' => 'حقل :attribute يجب أن لا يكون أكبر من :max',
	        'file'    => 'حقل :attribute يجب أن لا يكون أكبر من :max كيلو بايت',
	        'string'  => 'حقل :attribute يجب أن لا يكون أكبر من :max محرف',
	        'array'   => 'حقل :attribute يجب أن لا يحوي على أكثر من :max عنصر',
	    ],
	    'mimes'                => 'حقل :attribute يجب أن يكون ملف من نوع :values',
	    'mimetypes'            => 'حقل :attribute يجب أن يكون ملف من نوع :values',
	    'min'                  => [
	        'numeric' => 'حقل :attribute يجب أن يكون على الأقل :min',
	        'file'    => 'حقل :attribute يجب أن يكون على الأقل :min كيلو بايت',
	        'string'  => 'حقل :attribute يجب أن يكون على الأقل :min محرف',
	        'array'   => 'حقل :attribute يجب أن يحوي على الأقل :min عنصر',
	    ],
	    'not_in'               => 'حقل :attribute المختار غير صالح',
	    'numeric'              => 'حقل :attribute يجب أن يكون رقم',
	    'present'              => 'حقل :attribute يجب أن يكون موجود',
	    'regex'                => 'صيغة الحقل :attribute غير صالحة',
	    'required'             => 'حقل :attribute مطلوب',
	    'required_if'          => 'حقل :attribute مطلوب عندما :other يساوي :value',
	    'required_unless'      => 'حقل :attribute مطلوب إلا إذا كان :other يساوي :value',
	    'required_with'        => 'حقل :attribute مطلوب عندما :values موجودة',
	    'required_with_all'    => 'حقل :attribute مطلوب عندما :values موجودة',
	    'required_without'     => 'حقل :attribute مطلوب عندما تكون :values غير موجودة',
	    'required_without_all' => 'حقل :attribute مطلوب عندما تكون أي من :values غير موجودة',
	    'same'                 => 'حقل :attribute و :other يجب أن يكونا متطابقان',
	    'size'                 => [
	        'numeric' => 'حقل :attribute يجب أن يكون :size',
	        'file'    => 'حقل :attribute يجب أن يكون :size كيلو بايت',
	        'string'  => 'حقل :attribute يجب أن يكون :size محرف',
	        'array'   => 'حقل :attribute يجب أن يحتوي :size عنصر',
	    ],
	    'string'               => 'حقل :attribute يجب أن يكون مصفوفة محارف',
	    'timezone'             => 'حقل :attribute يجب أن يكون منطقة صالحة',
	    'unique'               => 'حقل :attribute موجود بالفعل',
	    'uploaded'             => 'فشل رفع حقل :attribute',
	    'url'                  => 'صيغة حقل :attribute غير صالحة',

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

	    'attributes' => [
	    	'email' => 'البريد الالكتروني',
	    	'password' => 'كلمة الامرور',
	    	'title_ar' => 'العنوان بالعربي',
	        'title_en' => 'العنوان بالإنجليزية',
	        'photo' => 'الصورة',
	        'start_at' => 'يبدأ في',
	        'end_at' => 'ينتهي في',
	        'ad' => 'محتوى الإعلان',
	        'mall_id' => 'المتجر',
	        'discountRadio' => 'حسم أم لا',
	        'discount' => 'حسم',
	        'productsDepartments' => 'منتجات أم أقسام',
	        'departments_ids' => 'الأقسام',
	        'products' => 'المنتجات',
	        'delete' => 'السجلات اللازم حذفها',
	        'choose' => 'منتجات أم أقسام',
	        'mallId' => 'المتجر',
	        'name' => 'الاسم',
	        'name_ar' => 'الاسم العربي',
	        'name_en' => 'الاسم بالإنجليزية',
	        'country_id' => 'الدولة',
	        'color' => 'اللون',
	        'logo' => 'اللوغو',
	        'code' => 'رمز البلد',
	        'mob' => 'رمز الجوال',
	        'currency' => 'العملة',
	        'parent' => 'القسم الأب',
	        'is_active' => 'مفعل أم لا',
	        'icon' => 'الأيقونة',
	        'keywords' => 'الكلمات الفتاحية',
	        'description' => 'الوصف',
	        'user_id' => 'المستخدم',
	        'facebook' => 'حساب فيسبوك',
	        'twitter' => 'حساب تويتر',
	        'website' => 'الموقع',
	        'contact_name' => 'جهة الاتصال',
	        'address' => 'العنوان',
	        'id' => 'الرقم',
	        'not_id' => 'الإشعار',
	        'productId' => 'المنتج',
	        'trade_id' => 'العلامة التجارية',
	        'manu_id' => 'الشركة المصنعة',
	        'size' => 'المقاس',
	        'weight_id' => 'نوع الوزن',
	        'weight' => 'الوزن',
	        'stock' => 'الكمية',
	        'offer_start_at' => 'تاريخ بداية العرض',
	        'offer_end_at' => 'تاريخ نهاية العرض',
	        'price_offer' => 'السعر بعد الحسم',
	        'price' => 'السعر',
	        'status' => 'الحالة',
	        'reason' => 'سبب الرفض',
	        'malls' => 'المتاجر',
	        'sizes_quantities' => 'كميات المقاسات',
	        'colors_quantities' => 'كميات الألوان',
	        'sitename_ar' => 'اسم الموقع بالعربي',
	        'sitename_en' => 'اسم الموقع بالإنجليزية',
	        'main_lang' => 'اللغة الرئيسية',
	        'message_maintenance' => 'رسالة الصيانة',
	        'is_public' => 'عام أم لا',
	        'city_id' => 'المدينة',
	        'level' => 'المستوى',
	        'phone' => 'الهاتف',
            'main_photo' => 'الصورة الرئيسية',
	        'photo_title' => 'عنوان الصورة',
	        'photo_desc' => 'محتوى الصورة',
	        'desc_photo' => 'صورة العرض',
	        'web_desc' => 'وصف الموقع',
	        'instagram' => 'إنستغرام',
	    ],
		
	];