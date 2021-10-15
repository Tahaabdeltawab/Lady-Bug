<?php

class DocsGeneral
{
    
     // MODELS NAMES
     public $modelsNames = [
         'AcidityType'              => 'حامضي أو قلوي أو متعادل',
         'AnimalBreedingPurpose'    => 'الغرض من تربية الحيوان',
         'AnimalFodderSource'       => 'مصادر الأعلاف',
         'AnimalFodderType'         => 'الأعلاف',
         'AnimalMedicineSource'     => 'مصادر الأدوية',
         'Asset'                    => 'الصور والفيديوهات',
         'BuyingNote'               => 'الملاحظات الموجودة في صفحة شراء المنتج',
         'ChemicalDetail'           => '
         لكل مرزعة خواص كيميائية مثل تركيز الأملاح وحامضية أو قلوية وهكذا
         هذه الخواص فصلت في جدول منفصل عن جدول المزرعة حفاظا على 
         normalization
         وتم ربطها بالمرزعة عن طريق
         soil_detail_id
         بالنسبة للخواص الكيميائية الخاصة بالتربة
         irrigation_water_detail_id
         بالنسبة للخواص الكيميائية الخاصة بمياه الري
         ---
         
         ',
         'ChemicalFertilizerSource' => 'مصادر الكيماوي',
         'City'                     => 'المدينة',
         'Comment'                  => 'التعليق',
         'District'                 => 'الحي',
         'Farm'                     => 'المزرعة',
         'FarmActivityType'         => '
         
         نوع النشاط الخاص بالمزرعة
         مثل 
         محاصيل ولها 
         id = 1
         أشجار ولها 
         id = 2
         نباتات منزلية ولها 
         id = 3
         حيوانات ولها 
         id = 4
         ',
         'FarmedType'               => 'المحصول مثل التفاح',
         'FarmedTypeClass'          => 'نوع المحصول مثل التفاح الأحمر',
         'FarmedTypeGinfo'          => 'المنشورات الخاصة بكل محصول',
         'FarmedTypeStage'          => 'مراحل نمو المحصول مثل الإثمار والتزهير',
         'FarmingMethod'            => 'أسلوب الزراعة',
         'FarmingWay'               => 'طريقة الزراعة',
         'HomePlantIlluminatingSource'=> 'مصدر الإضاءة بالنسبة للنباتات المنزلية',
         'HomePlantPotSize'         => 'حجم الأصيص للنباتات المنزلية',
         'HumanJob'                 => 'الوظائف مثل مهندس زراعي',
         'Information'              => 'صفحات المعلومات مثل من نحن والخصوصية وغيرها',
         'IrrigationWay'            => 'طريقة الري',
         'Location'                 => 'الموقع',
         'MeasuringUnit'            => 'وحدات القياس',
         'Permission'               => 'الصلاحيات',
         'Post'                     => 'المنشور',
         'PostType'                 => 'نوع المنشور',
         'Product'                  => 'المنتج',
         'Report'                   => 'التقارير',
         'ReportType'               => 'أنواع التقارير',
         'Role'                     => 'الأدوار',
         'SaltDetail'               => '

         نفس السيناريو المتبع بين جدول المزرعة وجدول التفاصيل الكيميائية
         حيث أن لكل مرزعة خواص كيميائية مثل تركيز الأملاح وحامضية أو قلوية وهكذا
         هذه الخواص لها خواص أخرى خاصة بأملاح التربة أو ماء الري مثل الصوديوم وغيره. فصلت هذه العناصر في جدول منفصل عن جدول التفاصيل الكيميائية حفاظا على 
         normalization
         وتم ربطها بالتفاصيل الكيميائية عن طريق
         salt_detail_id
         ---
         ',
         'SaltType'                 => 'نوع الأملاح',
         'SeedlingSource'           => 'مصادر الشتلات',
         'ServiceTable'             => 'جداول الخدمة',
         'ServiceTask'              => 'المهام الخاصة بجداول الخدمة',
         'SoilType'                 => 'نوع التربة',
         'TaskType'                 => 'نوع المهمة',
         'User'                     => 'المستخدم',
         'WeatherNote'              => 'الملاحظات الخاصة بالطقس'

     ];
     // Notes
     // Farm Activity Types 1234
    public function save_localized($input, $id=''){

        /**
         * file: BaseRepository
         * saves data if contains localized fields like 'name_ar_localized', 'name_en_localized'
         * it finds the localized fields in the $input by checking if the field name ends with string 'localized' or not
         * the key of the field should be $1_$2_$3
         * $1 => field name in the database (name)
         * $2 => the language key (ar)
         * $3 => the word (localized)
         */

    }
    
}


?>