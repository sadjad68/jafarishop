<?php

namespace App\Modules\Location\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Modules\Location\Entities\City;
use App\Modules\Location\Entities\State;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Entities\SettingPartial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::truncate();
        State::truncate();
        set_time_limit(10000000000);
        $json = File::get(database_path('data/states_with_cities_final.json'));
        $data = json_decode($json, true);
        $cities = [];
        foreach ($data as $row) {
            $state = State::find($row['id']);
            if ($state) {
                $state->update([
                    'name' => $row['name'],
                    'chapar_id' => $row['chapar_id'],
                    'status' => $row['status'] ?? 1,
                ]);
            } else {
                $state = State::create(
                    [
                        'name' => $row['name'],
                        'chapar_id' => $row['chapar_id'],
                        'status' => $row['status'] ?? 1,
                    ]
                );
            }
            foreach ($row['cities'] as $city) {
                $check_city = City::find($city['id']);
                if ($check_city) {
                    $check_city->update([
                        'name' => $city['name'],
                        'state_id' => $state['id'],
                        'chapar_id' => $city['chapar_id'],
                        'status' => $city['status'] ?? 1,
                    ]);
                } else {
                    $cities[] = [
                        'id'=>$city['id'],
                        'name' => $city['name'],
                        'state_id' => $state['id'],
                        'chapar_id' => $city['chapar_id'],
                        'status' => $city['status'] ?? 1,
                    ];
                }

            }
        }
        City::insert($cities);
    }
}
