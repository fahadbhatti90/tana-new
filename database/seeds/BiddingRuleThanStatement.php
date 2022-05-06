<?php

use App\Model\Ams\BiddingRule\BiddingRule;
use App\Model\Ams\BiddingRule\PreSetRule;
use Illuminate\Database\Seeder;

class BiddingRuleThanStatement extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allRule = BiddingRule::get();
        foreach ($allRule as $singleRule){
            $conditionValue = $singleRule->condition;
            $updateConditionValue = str_replace("then","than",$conditionValue);

            $singleRule->update(['condition' => $updateConditionValue]);
        }

        $allPreSetRule = PreSetRule::get();
        foreach ($allPreSetRule as $singlePreSetRule){
            $conditionValue = $singlePreSetRule->condition;
            $updateConditionValue = str_replace("then","than",$conditionValue);

            $singlePreSetRule->update(['condition' => $updateConditionValue]);
        }

    }
}
