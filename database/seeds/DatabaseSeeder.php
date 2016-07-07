<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(AddOatSampleData::class);
        $this->call(NewActivity::class);
        // $this->call(GenerateTestData::class);
        // $this->call(GenerateAlotEmployee::class);
    }
}
