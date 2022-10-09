<?php

namespace Database\Seeders;

use App\Models\RatingQuestion;
use Illuminate\Database\Seeder;

class RatingQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RatingQuestion::create(['id' => 1, 'name' => ['ar' => 'هل تعرفه شخصيا؟', 'en' => 'Do you know him personally?'], 'type' => 'know_personally']);
        RatingQuestion::create(['id' => 2, 'name' => ['ar' => 'هل تفيدك معلوماته؟', 'en' => 'Does his information benefit you?'], 'type' => 'beneficial_knowledge']);
        RatingQuestion::create(['id' => 3, 'name' => ['ar' => 'هل تعاملت معه من قبل؟', 'en' => 'Have you ever dealt with him?'], 'type' => 'dealt_personally']);
        RatingQuestion::create(['id' => 4, 'name' => ['ar' => 'هل هو جيد في المعاملات المادية؟', 'en' => 'Is he good at financial transactions?'], 'type' => 'good_financially']);
        RatingQuestion::create(['id' => 5, 'name' => ['ar' => 'هل هو سيء في المعاملات المادية؟', 'en' => 'Is he bad at financial transactions?'], 'type' => 'bad_financially']);
    }
}
