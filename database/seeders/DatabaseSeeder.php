<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(FarmActivityTypeSeeder::class);
        $this->call(HumanJobSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PostTypeSeeder::class);
        $this->call(BusinessFieldSeeder::class);
        $this->call(WorkFieldSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(FarmedTypeStageSeeder::class);
        $this->call(FarmRelationsSeeder::class);
        $this->call(TaskTypeSeeder::class);
        $this->call(InfectionRateSeeder::class);
        $this->call(DiseaseSeeder::class);
        $this->call(ProductTypeSeeder::class);
        $this->call(CityDistrictSeeder::class);
        $this->call(AdditionalRolesSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RatingQuestionSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(IrrigationRateSeeder::class);
        $this->call(PathogenTypeSeeder::class);
        $this->call(ReportTypeSeeder::class);
        $this->call(InformationSeeder::class);

        /**
         * ? ... ? means don't allow editing or deleting them
         * ? FarmActivityType ? because depended on by farms
         * ? BusinessField ? because depended on by Business::class scopes
         * ? ProductType ? because depended on by ProductAPIController()->store() method
         * ? RatingQuestionSeeder ? because depended on by UserAPIController()->user_rating_details() method
         * ? TaskType ? because depended on by TaskAPIController()->getRelations() method
         *
         * WorkField
         * PathogenType
         * InfectionRate
         * IrrigationRate
         */
  }
}
