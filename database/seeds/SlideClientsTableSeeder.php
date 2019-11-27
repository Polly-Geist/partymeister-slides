<?php

use Illuminate\Database\Seeder;
use Partymeister\Competitions\Models\OptionGroup;
use Partymeister\Core\Models\User;

/**
 * Class AccountsTableSeeder
 * @package Partymeister\Accounting\Database\Seeds
 */
class SlideClientsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('slide_clients')->insert([
            'name'          => 'Main Screen',
            'type'          => 'slidemeister-web',
            'sort_position' => 1,
            'configuration' => json_encode([ 'prizegiving_bar_color' => '#000000', 'prizegiving_bar_blink_color' => '#FF0000' ]),
            'created_by'    => User::get()->first()->id,
            'updated_by'    => User::get()->first()->id,
        ]);
    }
}
