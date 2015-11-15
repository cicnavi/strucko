<?php

use Illuminate\Database\Seeder;

class ConceptTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Concept::class, 50)->create();
    }
}
