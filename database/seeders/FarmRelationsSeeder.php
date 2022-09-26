<?php

namespace Database\Seeders;

use App\Models\AcidityType;
use App\Models\AnimalBreedingPurpose;
use App\Models\AnimalFodderType;
use App\Models\FarmingMethod;
use App\Models\FarmingWay;
use App\Models\HomePlantIlluminatingSource;
use App\Models\HomePlantPotSize;
use App\Models\IrrigationWay;
use App\Models\MeasuringUnit;
use App\Models\SaltType;
use App\Models\SoilType;
use Illuminate\Database\Seeder;

class FarmRelationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcidityType::create(['id' => 1, 'name' => ['ar' => 'حامضية', 'en' => 'Acidic']]);
        AcidityType::create(['id' => 2, 'name' => ['ar' => 'قاعدية', 'en' => 'Basic']]);
        AcidityType::create(['id' => 3, 'name' => ['ar' => 'متعادلة', 'en' => 'Neutral']]);
       
        SaltType::create(['id' => 1, 'name' => ['ar' => 'حامضية', 'en' => 'Acidic'], 'type' => 'soil']);
        SaltType::create(['id' => 2, 'name' => ['ar' => 'قاعدية', 'en' => 'Basic'], 'type' => 'soil']);
        SaltType::create(['id' => 3, 'name' => ['ar' => 'متعادلة', 'en' => 'Neutral'], 'type' => 'soil']);

        HomePlantPotSize::create(['id' => 1, 'size' => 10]);
        HomePlantPotSize::create(['id' => 2, 'size' => 12]);
        HomePlantPotSize::create(['id' => 3, 'size' => 14]);
        HomePlantPotSize::create(['id' => 4, 'size' => 16]);
        HomePlantPotSize::create(['id' => 5, 'size' => 18]);
        HomePlantPotSize::create(['id' => 6, 'size' => 20]);
        HomePlantPotSize::create(['id' => 7, 'size' => 24]);
        
        MeasuringUnit::create(['id' => 1, 'name' => ['ar' => 'فدان', 'en' => 'acre'], 'code' => 'acre', 'measurable' => 'area']);
        MeasuringUnit::create(['id' => 2, 'name' => ['ar' => 'قيراط', 'en' => 'carat'], 'code' => 'carat', 'measurable' => 'area']);
        MeasuringUnit::create(['id' => 3, 'name' => ['ar' => 'متر مربع', 'en' => 'squared meter'], 'code' => 'm^2', 'measurable' => 'area']);
        MeasuringUnit::create(['id' => 4, 'name' => ['ar' => 'متر', 'en' => 'meter'], 'code' => 'm', 'measurable' => 'distance']);
        MeasuringUnit::create(['id' => 5, 'name' => ['ar' => 'PH', 'en' => 'PH'], 'code' => 'PH', 'measurable' => 'acidity']);
        MeasuringUnit::create(['id' => 6, 'name' => ['ar' => 'جم/لتر', 'en' => 'gm/liter'], 'code' => 'gm/litre', 'measurable' => 'salt_concentration']);

        IrrigationWay::create(['id' => 1, 'name' => ['ar' => 'غمر', 'en' => 'Flood']]);
        IrrigationWay::create(['id' => 2, 'name' => ['ar' => 'تنقيط', 'en' => 'Drip']]);
        
        HomePlantIlluminatingSource::create(['id' => 1, 'name' => ['ar' => 'الشمس', 'en' => 'Sun']]);
        HomePlantIlluminatingSource::create(['id' => 2, 'name' => ['ar' => 'مصباح', 'en' => 'Lamp']]);
        
        FarmingWay::create(['id' => 1, 'name' => ['ar' => 'طريقة 1', 'en' => 'Way 1'], 'type' => 'farming']);
        FarmingWay::create(['id' => 2, 'name' => ['ar' => 'طريقة 2', 'en' => 'Way 2'], 'type' => 'farming']);
        FarmingWay::create(['id' => 3, 'name' => ['ar' => 'تربية 1', 'en' => 'Breeding 1'], 'type' => 'breeding']);
        FarmingWay::create(['id' => 4, 'name' => ['ar' => 'تربية 2', 'en' => 'Breeding 2'], 'type' => 'breeding']);
        
        FarmingMethod::create(['id' => 1, 'name' => ['ar' => 'أسلوب 1', 'en' => 'Method 1']]);
        FarmingMethod::create(['id' => 2, 'name' => ['ar' => 'أسلوب 2', 'en' => 'Method 2']]);
        
        AnimalBreedingPurpose::create(['id' => 1, 'name' => ['ar' => 'إنتاج ألبان', 'en' => 'Milk production']]);
        AnimalBreedingPurpose::create(['id' => 2, 'name' => ['ar' => 'إنتاج لحوم', 'en' => 'Meat production']]);
        
        AnimalFodderType::create(['id' => 1, 'name' => ['ar' => 'تبن', 'en' => 'hay']]);
        AnimalFodderType::create(['id' => 2, 'name' => ['ar' => 'ذرة', 'en' => 'corn']]);
        
        SoilType::create(['id' => 1, 'name' => ['ar' => 'رملية', 'en' => 'Sandy']]);
        SoilType::create(['id' => 2, 'name' => ['ar' => 'طينية', 'en' => 'Clay']]);
        SoilType::create(['id' => 3, 'name' => ['ar' => 'طفلية', 'en' => 'Middle Clay']]);
        
    }
}
