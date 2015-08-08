<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Tables which will be truncated on every artisan db::seed command.
     * 
     * @var array 
     */
    protected $toTruncate = [
        'terms',
        'scientific_branches',
        'scientific_fields',
        'scientific_areas',
        'part_of_speeches',
        'statuses',
        'definitions',
        'languages',
        'synonyms',
        'users',
    ];
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        // Truncate all tables before seeding.
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($this->toTruncate as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        
        // Run the seeds.
        $this->call(UserTableSeeder::class);
        $this->call(SynonymTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(PartOfSpeechTableSeeder::class);
        $this->call(StatusTableSeeder::class);
        $this->call(ScientificAreaTableSeeder::class);
        $this->call(ScientificFieldTableSeeder::class);
        $this->call(ScientificBranchTableSeeder::class);
        $this->call(DefinitionTableSeeder::class);
        $this->call(TermTableSeeder::class);
        
        Model::reguard();
    }
}
