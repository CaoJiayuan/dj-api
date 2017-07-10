<?php

use Illuminate\Database\Seeder;

class JourneyPaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rules = config('journeyrule');
        $i=0;
        foreach($rules as $v)
        {
            if(isset($v['children']))
            {
                foreach($v['children'] as $value)
                {
                    foreach($value as $key=>$vc)
                    {
                        $data[$i][$key] = $vc;
                    }
                    ++$i;
                }
            }else{
                foreach($v as $k=>$v)
                {
                    $data[$i][$k] = $v;
                }
            }
            ++$i;
        }
        DB::table('journey_pay_rules')->insert($data);
    }
}
