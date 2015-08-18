<?php

use League\Csv\Reader;
Use App\ScientificArea;
use Illuminate\Database\Seeder;

class ScientificAreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fake areas.
        // factory(App\ScientificArea::class, 5)->create();
        
        // Sources for Croatian Regulation on scientific and artistic areas, fields and branches:
        // http://narodne-novine.nn.hr/clanci/sluzbeni/2013_03_32_574.html
        // http://narodne-novine.nn.hr/clanci/sluzbeni/2012_07_82_1917.html
        // http://narodne-novine.nn.hr/clanci/sluzbeni/2009_09_118_2929.html
        // http://narodne-novine.nn.hr/clanci/sluzbeni/2008_07_78_2563.html
        // http://narodne-novine.nn.hr/clanci/sluzbeni/2005_06_76_1500.html
        
        $csv = Reader::createFromPath('database/seeds/data/scientific-areas.csv');
        // Set delimiter to ; (the default is ,).
        $csv->setDelimiter(';');
        // $data is the iterator. Set the limit because the last row is empty.
        $data = $csv->setOffset(1)->setLimit(7)->query();
        
        foreach ($data as $index => $row) {
            
            ScientificArea::create([
                'id' => $row[0],
                'scientific_area' => $row[1],
                'mark' => $row[2],
                'description' => $row[3] ?: null,
            ]);
        }
    }
}
