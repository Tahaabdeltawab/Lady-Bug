<?php

namespace App\Http\Helpers;

use App\Models\Farm;

class Compatibility{
    // get the average ot two numbers in an array
    protected function avg($array)
    {
        return count($array) ? array_sum($array) / count($array) : 0;
    }

    // sort an array of two items to make the first item the min one and the last item the max one
    protected function min_max($array)
    {
        if(count($array))
        {
            $min = $array[0] < $array[1] ? $array[0] : $array[1];
            $max = $min == $array[0] ? $array[1] : $array[0];
            return [$min, $max];
        }
        return false;
    }

    // checks if a number is in between two values in an array
    protected function in_range($num, $array)
    {
        if(count($array))
        {
            $min = $this->min_max($array)[0];
            $max = $this->min_max($array)[1];
            return ($num >= $min && $num <= $max);
        }
        else
        {
            return false;
        }
    }

    /**
     * soil_type_id -> نوع التربة للمزرةعة التي سنحسب ملاءمتها
     * suitable_soil_types -> أنواع التربة التي يمكن زراعة المحصول فيها ويتم ترتيبها في المصفوفة حسب ملاءمتها للتربة
     * full_deg -> الدرجة الكلية لهذا المعيار معيار نوع التربة
     * لو كان نوع تربة المزرعة ليس من ضمن أنواع التربة الملائمة للمزرعة تحسب الدرجة صفر
     * لو كان هو أول نوع ملائم في المصفوفة يأخذ نصف الدرجة
     * لو كان هو ثاني نوع ملائم في المصفوفة يأخذ ربع الدرجة
     */
    protected function calc_soil_type($soil_type_id, $suitable_soil_types, $full_deg)
    {
        $deg = 0;
        if((count($suitable_soil_types) > 0) && in_array($soil_type_id, $suitable_soil_types))
        {
            $deg = $soil_type_id == $suitable_soil_types[0] ? 0.5 : 0.25;
        }

        return $deg = $deg * $full_deg;
    }

    /**
     * هذه الفنكشن خاصة بحساب الدرجة الخاصة بخصائص الملاءمة التي تكون الدرجة العظمى فيها هي القيمة المتوسطة
     * مثال
     * قيمة الأس الهيدروجيني الملائم للمزرعة يكون ما بين 8 إلى 10
     * تأخد المزرعة الدرجة النهائية إذا كانت قيمة أسها الهيدروجيني تساوي 9 لأنها القيمة المتوسطة
     * وتأخذ درجة أقل إذا زادت أو قلت عن المتوسط إلى أن تصل إلى درجة صفر عندما يكون قيمة الأس الهيدروجيني مساوية للقيمة العظمى 10 أو الصغري 8
     * value -> قيمة الأس الهيدروجيني الفعلي للمزرعة
     * model -> [8, 10] -> قيمة الأس الهيدروجيني الملائم للمزرعة
     * full_deg -> الدرجة الكلية لهذا المعيار
     */
    protected function calc_deg_avg($value, $model, $full_deg) // get degree in things whose best degree is the average value
    {
        $deg = 0;
        if((count($model) > 0))
        {
            if($this->in_range($value, $model))
            {
                $best   = $this->avg($model);
                $min    = $this->min_max($model)[0];
                $max    = $this->min_max($model)[1];
                $deg    = $value < $best ? ($value - $min) / ($best - $min) : ($max - $value) / ($max - $best);
            }
        }

        return $deg = $deg * $full_deg;
    }

    /**
     * هذه الفنكشن خاصة بحساب الدرجة الخاصة بخصائص الملاءمة التي تكون الدرجة العظمى فيها هي القيمة الدنيا أو أقل من القيمة الدنيا
     * مثال
     * قيمة أملاح التربة الملائمة للمزرعة يكون ما بين 300 إلى 1200
     * تأخد المزرعة الدرجة النهائية إذا كانت قيمة أسها الهيدروجيني تساوي 300 أو أقل
     * وتأخذ درجة أقل إذا زادت عن القيمة الدنيا إلى أن تصل إلى درجة صفر عندما يكون قيمة أملاح التربة مساوية للقيمة العظمى 12008
     * value -> قيمة أملاح التربة الفعلية للمزرعة
     * model -> [300, 1200] -> قيمة أملاح التربة الملائمة للمزرعة
     * full_deg -> الدرجة الكلية لهذا المعيار
     */
    protected function calc_deg_min($value, $model, $full_deg) // get degree in things whose best degree is the minimum value
    {
        $deg = 0;
        if((count($model) > 0))
        {
            if($this->in_range($value, $model))
            {
                $best   = $this->min_max($model)[0];
                $min    = $this->min_max($model)[0];
                $max    = $this->min_max($model)[1];
                $deg    = ($max - $value) / ($max - $best);
            }
        }
        // in case of the min is 300 and max = 1000; if given the value less than 300, the deg will be greater than 1 (which is wrong because it makes the full degree > max degree)
        $deg = $deg > 1 ? 1 : $deg;
        return $deg = $deg * $full_deg;
    }

    public function calculate_compatibility($farm)
    {
        $farm = $farm instanceOf Farm ? $farm : Farm::with(['farmed_type', 'soil_detail', 'irrigation_water_detail'])->find($farm);

        if (empty($farm))
        {
            return Resp::makeError('Farm not found');
        }

        if (!in_array($farm->farm_activity_type_id, [1, 2])) // farm must be crops or trees
        {
            return Resp::makeError('Farm type not valid');
        }



        $soil_type_deg = $this->calc_soil_type($farm->soil_type_id, $farm->farmed_type->suitable_soil_types, 5);

        $soil_salt_deg = $this->calc_deg_min($farm->soil_detail->salt_concentration_value, $farm->farmed_type->suitable_soil_salts_concentration, 20);

        $water_salt_deg = $this->calc_deg_min($farm->irrigation_water_detail->salt_concentration_value, $farm->farmed_type->suitable_water_salts_concentration, 25);

        $ph_deg = $this->calc_deg_avg($farm->soil_detail->acidity_value, $farm->farmed_type->suitable_ph, 5);


        if(!$farm->farmed_type->flowering_time || !$farm->farmed_type->maturity_time)
        return Resp::makeError('crop flowering and maturity times cannot be null');

        /**
         * ظروف الطقس للعام الماضي لأنها تكون مشابهة للعام الجاري ولعدم عثورنا على
         * API
         * يقوم بجلب توقعات لفترات بعيدة مثل شهور
         **/

        $year_before = $farm->farming_date . ' - 1 year';

        $farming_day    = date("Y-m-d", strtotime($year_before));

        $flowering_day1  = date("Y-m-d", strtotime($year_before . ' + ' . ($farm->farmed_type->flowering_time - 10) . ' days'));
        $flowering_day2  = date("Y-m-d", strtotime($year_before . ' + ' . ($farm->farmed_type->flowering_time - 05) . ' days'));
        $flowering_day3  = date("Y-m-d", strtotime($year_before . ' + ' . ($farm->farmed_type->flowering_time - 00) . ' days'));
        $flowering_day4  = date("Y-m-d", strtotime($year_before . ' + ' . ($farm->farmed_type->flowering_time + 05) . ' days'));
        $flowering_day5  = date("Y-m-d", strtotime($year_before . ' + ' . ($farm->farmed_type->flowering_time + 10) . ' days'));

        $maturity_day   = date("Y-m-d", strtotime($year_before . ' + ' . $farm->farmed_type->maturity_time . ' days'));


        $lat = $farm->location->latitude;
        $lon = $farm->location->longitude;

        $conditions = [
            'soil_type_deg' => $soil_type_deg,
            'soil_salt_deg' => $soil_salt_deg,
            'water_salt_deg' => $water_salt_deg,
            'ph_deg' => $ph_deg,
            'flowering_day1' => $flowering_day1,
            'maturity_day' => $maturity_day,
            'lat' => $lat,
            'lon' => $lon,
        ];

        // if the database has compatibility set
        if($compat = json_decode($farm->farming_compatibility)){
            // check if the conditions changed or not, if not changed so don't calculate it again. Else, calculate it.
            if(json_encode($compat->conditions) == json_encode($conditions)){
                $messages = [];
                foreach($compat->messages as $message){
                    $message = __($message);
                    $messages[] = $message;
                }
                return Resp::makeResponse(['total' => $compat->total, 'messages' => $messages], 'Compatibility retrieved');
            }
        }
        // calculate compat if not set or set but conditions changed

        // جلب معلومات الطقس عند الزراعة
        $farming_info   = WeatherApi::instance()->weather_history($lat, $lon, $farming_day);
        if(isset($farming_info['error'])){return Resp::makeError($farming_info['error']);}
        // جلب معلومات الطقس عند الزراعة في أيام مختلقة ثم الحصول على متوسط هذه القيم
        $flowering_info1 = WeatherApi::instance()->weather_history($lat, $lon, $flowering_day1);
        if(isset($flowering_info1['error'])) return Resp::makeError($flowering_info1['error']);
        $flowering_info2 = WeatherApi::instance()->weather_history($lat, $lon, $flowering_day2);
        if(isset($flowering_info2['error'])) return Resp::makeError($flowering_info2['error']);
        $flowering_info3 = WeatherApi::instance()->weather_history($lat, $lon, $flowering_day3);
        if(isset($flowering_info3['error'])) return Resp::makeError($flowering_info3['error']);
        $flowering_info4 = WeatherApi::instance()->weather_history($lat, $lon, $flowering_day4);
        if(isset($flowering_info4['error'])) return Resp::makeError($flowering_info4['error']);
        $flowering_info5 = WeatherApi::instance()->weather_history($lat, $lon, $flowering_day5);
        if(isset($flowering_info5['error'])) return Resp::makeError($flowering_info5['error']);
        // جلب معلومات الطقس عند النضج
        $maturity_info  = WeatherApi::instance()->weather_history($lat, $lon, $maturity_day);
        if(isset($maturity_info['error'])) return Resp::makeError($maturity_info['error']);


        // الحرارة عند الزراعة
        $farming_temperature = $farming_info['temperature'];
        // متوسط درجات الحرارة عند التزهير
        $flowering_temperature_average = ($flowering_info1['temperature'] + $flowering_info2['temperature'] + $flowering_info3['temperature'] + $flowering_info4['temperature'] + $flowering_info5['temperature']) / 5 ;
        // الحرارة عند النضج
        $maturity_temperature = $maturity_info['temperature'];
        // الرطوبة عند النضج
        $humidity = $maturity_info['humidity'];
        // $farming_temperature = 33;
        // $flowering_temperature_average = 35;
        // $maturity_temperature = 37;
        // $humidity = 77;

        $tfarming_deg = $this->calc_deg_avg($farming_temperature, $farm->farmed_type->farming_temperature, 20);

        $tflowering_deg = $this->calc_deg_avg($flowering_temperature_average, $farm->farmed_type->flowering_temperature, 15);

        $tmaturity_deg = $this->calc_deg_avg($maturity_temperature, $farm->farmed_type->maturity_temperature, 5);

        $hmaturity_deg = $this->calc_deg_avg($humidity, $farm->farmed_type->humidity, 5);

        // جمع الدرجة النهائية للملاءمة
        $total = $soil_type_deg + $soil_salt_deg + $water_salt_deg + $ph_deg + $tfarming_deg + $tflowering_deg + $tmaturity_deg + $hmaturity_deg;
        // $total = 'soil_type_deg = ' . $soil_type_deg . ' - ' . 'soil_salt_deg = ' . $soil_salt_deg . ' - ' . 'water_salt_deg = ' . $water_salt_deg . ' - ' . 'ph_deg = ' . $ph_deg . ' - ' . 'tfarming_deg = ' . $tfarming_deg . ' - ' . 'tflowering_deg = ' . $tflowering_deg . ' - ' . 'tmaturity_deg = ' . $tmaturity_deg . ' - ' . 'hmaturity_deg = ' . $hmaturity_deg;

        $messages = [];
        if($total < 50)
            $messages[] = "incompatible";
        else
            $messages []= "compatible";
        if($water_salt_deg == 0)
            $messages[] = "incompatible because of non suitable water salts concentration";
        if($soil_salt_deg == 0)
            $messages[] = "incompatible because of non suitable soil salts concentration";
        if($tflowering_deg == 0)
            $messages[] = "incompatible because of non suitable flowering temperature";
        if($tfarming_deg == 0)
            $messages[] = "incompatible because of non suitable farming temperature";
        if($tmaturity_deg == 0)
            $messages[] = "incompatible because of non suitable maturity temperature";
        if($ph_deg == 0)
            $messages[] = "incompatible because of non suitable PH";
        if($soil_type_deg == 0)
            $messages[] = "incompatible because of non suitable soil type";
        if($hmaturity_deg == 0)
            $messages[] = "incompatible because of non suitable humidity";

        $data = [
            'total' => $total,
            'conditions' => $conditions,
            'messages' => $messages
        ];

        $farm->update(['farming_compatibility' => json_encode($data)]);

        foreach($messages as &$message){
            $message = __($message);
        }
        return Resp::makeResponse(['total' => $total, 'messages' => $messages], 'Compatibility retrieved');
    }
}
