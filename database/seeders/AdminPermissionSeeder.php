<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            /* [
                'name' => 'farms.index',
                'display_name' => 'Show farms',
                'description' => 'إظهار المزارع'
            ],
            [
                'name' => 'farms.destroy',
                'display_name' => 'Delete a farm',
                'description' => 'حذف مزرعة'
            ],
            [
                'name' => 'reports.index',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'reports.show',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'reports.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'reports.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'posts.index',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'posts.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'users.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'users.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'users.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'users.admin_index',
                'display_name' => 'Show admins',
                'description' => 'عرض المشرفين'
            ],
            [
                'name' => 'users.admin_show',
                'display_name' => 'Show an admin',
                'description' => 'عرض مشرف'
            ],
            [
                'name' => 'users.show',
                'display_name' => 'Show a user',
                'description' => 'عرض مستخدم'
            ],
            [
                'name' => 'users.index',
                'display_name' => 'Show users',
                'description' => 'عرض المستخدمين'
            ],
            [
                'name' => 'buying_notes.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'animal_medicine_sources.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'animal_fodder_types.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'animal_fodder_sources.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'animal_breeding_purposes.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'acidity_types.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'roles.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'permissions.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farmed_type_stages.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'chemical_fertilizer_sources.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'home_plant_illuminating_sources.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farming_methods.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'human_jobs.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'post_types.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'seedling_sources.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'measuring_units.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'information.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'weather_notes.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'soil_types.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'irrigation_ways.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'report_types.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'home_plant_pot_sizes.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'salt_types.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'task_types.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'districts.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'cities.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farmed_type_ginfos.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farmed_type_classes.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farmed_types.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farming_ways.store',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'buying_notes.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'animal_medicine_sources.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'animal_fodder_types.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'animal_fodder_sources.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'animal_breeding_purposes.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'acidity_types.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'roles.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'permissions.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farmed_type_stages.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'chemical_fertilizer_sources.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'home_plant_illuminating_sources.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farming_methods.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'human_jobs.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'post_types.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'seedling_sources.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'measuring_units.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'information.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'weather_notes.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'soil_types.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'irrigation_ways.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'report_types.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'home_plant_pot_sizes.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'salt_types.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'task_types.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'districts.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'cities.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farmed_type_ginfos.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farmed_type_classes.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farmed_types.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farming_ways.update',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'buying_notes.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'animal_medicine_sources.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'animal_fodder_types.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'animal_fodder_sources.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'animal_breeding_purposes.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'acidity_types.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'roles.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'permissions.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farmed_type_stages.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'chemical_fertilizer_sources.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'home_plant_illuminating_sources.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farming_methods.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'human_jobs.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'post_types.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'seedling_sources.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'measuring_units.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'information.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'weather_notes.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'soil_types.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'irrigation_ways.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'report_types.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'home_plant_pot_sizes.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'salt_types.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'task_types.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'districts.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'cities.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farmed_type_ginfos.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farmed_type_classes.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farmed_types.destroy',
                'display_name' => NULL,
                'description' => NULL
            ],
            [
                'name' => 'farming_ways.destroy',
                'display_name' => NULL,
                'description' => NULL
            ], */

            // NEW

            // consultant
            [
                'name' => 'consultants.index',
                'display_name' => 'consultants table',
                'description' => 'عرض الاستشاريين'
            ],
            [
                'name' => 'consultants.show',
                'display_name' => 'consultant details',
                'description' => 'عرض تفاصيل الاستشاري'
            ],
            [
                'name' => 'consultants.update',
                'display_name' => 'update consultants',
                'description' => 'تعديل الاستشاريين'
            ],
            // farm rating
            [
                'name' => 'farms.update',
                'display_name' => 'update farm',
                'description' => 'تعديل المزرعة'
            ],
            // farmed type
            [
                'name' => 'farmed_types.index',
                'display_name' => 'farmed types table',
                'description' => 'جدول المحاصيل'
            ],
            [
                'name' => 'farmed_types.show',
                'display_name' => 'farmed types details',
                'description' => 'تفاصيل المحصول'
            ],
            // irrigation rate
            [
                'name' => 'irrigation_rates.store',
                'display_name' => 'create irrigation rates',
                'description' => 'إضافة معدلات الري'
            ],
            [
                'name' => 'irrigation_rates.update',
                'display_name' => 'update irrigation rates',
                'description' => 'تعديل معدلات الري'
            ],
            [
                'name' => 'irrigation_rates.destroy',
                'display_name' => 'delete irrigation rates',
                'description' => 'حذف معدلات الري'
            ],
            // posts
            [
                'name' => 'posts.destroy',
                'display_name' => 'delete posts',
                'description' => 'حذف منشور'
            ],
            // products
            [
                'name' => 'products.index',
                'display_name' => 'products table',
                'description' => 'جدول المنتجات'
            ],
            [
                'name' => 'products.show',
                'display_name' => 'products details',
                'description' => 'تفاصيل منتج'
            ],
            [
                'name' => 'products.update',
                'display_name' => 'update products',
                'description' => 'تعديل منتج'
            ],
            [
                'name' => 'products.destroy',
                'display_name' => 'delete products',
                'description' => 'حذف منتج'
            ],
            // acs
            [
                'name' => 'acs.index',
                'display_name' => 'acs table',
                'description' => 'جدول المواد الفعالة'
            ],
            [
                'name' => 'acs.show',
                'display_name' => 'acs details',
                'description' => 'تفاصيل مادة فعالة'
            ],
            [
                'name' => 'acs.store',
                'display_name' => 'store acs',
                'description' => 'إضافة مادة فعالة'
            ],
            [
                'name' => 'acs.update',
                'display_name' => 'update acs',
                'description' => 'تعديل مادة فعالة'
            ],
            [
                'name' => 'acs.destroy',
                'display_name' => 'delete acs',
                'description' => 'حذف مادة فعالة'
            ],
            // pathogens
            [
                'name' => 'pathogens.index',
                'display_name' => 'pathogens table',
                'description' => 'جدول مسببات المرض'
            ],
            [
                'name' => 'pathogens.show',
                'display_name' => 'pathogens details',
                'description' => 'تفاصيل مسبب المرض'
            ],
            [
                'name' => 'pathogens.store',
                'display_name' => 'store pathogens',
                'description' => 'إضافة مسبب المرض'
            ],
            [
                'name' => 'pathogens.update',
                'display_name' => 'update pathogens',
                'description' => 'تعديل مسبب المرض'
            ],
            [
                'name' => 'pathogens.destroy',
                'display_name' => 'delete pathogens',
                'description' => 'حذف مسبب المرض'
            ],
            // insecticides
            [
                'name' => 'insecticides.index',
                'display_name' => 'insecticides table',
                'description' => 'جدول المبيدات الحشرية'
            ],
            [
                'name' => 'insecticides.show',
                'display_name' => 'insecticides details',
                'description' => 'تفاصيل مبيد حشري'
            ],
            [
                'name' => 'insecticides.store',
                'display_name' => 'store insecticides',
                'description' => 'إضافة مبيد حشري'
            ],
            [
                'name' => 'insecticides.update',
                'display_name' => 'update insecticides',
                'description' => 'تعديل مبيد حشري'
            ],
            [
                'name' => 'insecticides.destroy',
                'display_name' => 'delete insecticides',
                'description' => 'حذف مبيد حشري'
            ],
            // fertilizers
            [
                'name' => 'fertilizers.index',
                'display_name' => 'fertilizers table',
                'description' => 'جدول الأسمدة'
            ],
            [
                'name' => 'fertilizers.show',
                'display_name' => 'fertilizers details',
                'description' => 'تفاصيل سماد'
            ],
            [
                'name' => 'fertilizers.store',
                'display_name' => 'store fertilizers',
                'description' => 'إضافة سماد'
            ],
            [
                'name' => 'fertilizers.update',
                'display_name' => 'update fertilizers',
                'description' => 'تعديل سماد'
            ],
            [
                'name' => 'fertilizers.destroy',
                'display_name' => 'delete fertilizers',
                'description' => 'حذف سماد'
            ],
            // diseases
            [
                'name' => 'diseases.index',
                'display_name' => 'diseases table',
                'description' => 'جدول الأمراض'
            ],
            [
                'name' => 'diseases.show',
                'display_name' => 'diseases details',
                'description' => 'تفاصيل مرض'
            ],
            [
                'name' => 'diseases.store',
                'display_name' => 'store diseases',
                'description' => 'إضافة مرض'
            ],
            [
                'name' => 'diseases.update',
                'display_name' => 'update diseases',
                'description' => 'تعديل مرض'
            ],
            [
                'name' => 'diseases.destroy',
                'display_name' => 'delete diseases',
                'description' => 'حذف مرض'
            ],
            // pathogen types
            [
                'name' => 'pathogens_types.store',
                'display_name' => 'store pathogen types',
                'description' => 'إضافة نوع مسبب المرض'
            ],
            [
                'name' => 'pathogens_types.update',
                'display_name' => 'update pathogen types',
                'description' => 'تعديل نوع مسبب المرض'
            ],
            [
                'name' => 'pathogens_types.destroy',
                'display_name' => 'delete pathogen types',
                'description' => 'حذف نوع مسبب المرض'
            ],
            // infection rates
            [
                'name' => 'infection_rates.store',
                'display_name' => 'store infection rates',
                'description' => 'إضافة معدل الإصابة'
            ],
            [
                'name' => 'infection_rates.update',
                'display_name' => 'update infection rates',
                'description' => 'تعديل معدل الإصابة'
            ],
            [
                'name' => 'infection_rates.destroy',
                'display_name' => 'delete infection rates',
                'description' => 'حذف معدل الإصابة'
            ],
            // disease registrations
            [
                'name' => 'disease_registrations.index',
                'display_name' => 'disease registrations table',
                'description' => 'جدول تسجيلات الأمراض'
            ],
            [
                'name' => 'disease_registrations.show',
                'display_name' => 'disease registrations details',
                'description' => 'تفاصيل تسجيلات الأمراض'
            ],
            [
                'name' => 'disease_registrations.update',
                'display_name' => 'update disease registrations',
                'description' => 'تعديل تسجيلات الأمراض'
            ],
            // businesses
            [
                'name' => 'businesses.index',
                'display_name' => 'businesses table',
                'description' => 'جدول الأعمال'
            ],
            [
                'name' => 'businesses.show',
                'display_name' => 'businesses details',
                'description' => 'تفاصيل الأعمال'
            ],
            [
                'name' => 'businesses.update',
                'display_name' => 'update businesses',
                'description' => 'تعديل الأعمال'
            ],
            // work fields
            [
                'name' => 'work_fields.store',
                'display_name' => 'store work fields',
                'description' => 'إضافة مجالات العمل'
            ],
            [
                'name' => 'work_fields.update',
                'display_name' => 'update work fields',
                'description' => 'تعديل مجالات العمل'
            ],
            [
                'name' => 'work_fields.destroy',
                'display_name' => 'delete work fields',
                'description' => 'حذف مجالات العمل'
            ],
            // settings
            [
                'name' => 'settings.update',
                'display_name' => 'update settings',
                'description' => 'تعديل الإعدادات'
            ],



        ]);
    }
}
