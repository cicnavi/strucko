<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Language;
use Illuminate\Support\Facades\Storage;
use DB;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate .txt sitemap for SE to use.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $directory = 'public' . DIRECTORY_SEPARATOR . 'sitemaps';
        $file = 'sitemap-';
        $fileNumber = 1;
        $extension = '.txt';

        // Remove all previous entries.
        Storage::deleteDirectory($directory);

        $hostname = 'https://strucko.com/';
        $sitemapFileName = $directory . DIRECTORY_SEPARATOR . $file . $fileNumber . $extension;
        Storage::append($sitemapFileName, $hostname);

        $languages = $this->getLanguages();

        $maxEntries = 50000;
        $counter = 1;
        foreach ($languages as $language) {
            $letters = $this->getLetters($language->id);
            foreach ($letters as $letter) {
                foreach($languages as $translateTo) {
                    if ($language->id == $translateTo->id) {
                        continue;
                    }
                    if (++$counter % $maxEntries == 0) {
                        $this->info('Finished file ' . $fileNumber);
                        $fileNumber++;
                    }
                    $url = $hostname . "browse/$language->id/$letter->letter/$translateTo->id";
                    $sitemapFileName = $directory . DIRECTORY_SEPARATOR . $file . $fileNumber . $extension;
                    Storage::append($sitemapFileName, $url);
                }
            }
        }
        $this->info('Finished all files');
    }

    protected function getLanguages()
    {
        return Language::where('active', true)
                ->orderBy('ref_name')
                ->get();
    }

    protected function getLetters($languageId)
    {
        return DB::select(
            "SELECT DISTINCT LEFT(menu_letter, 1) as letter
            FROM strucko.terms
            WHERE language_id = '" . $languageId . "'
            ORDER BY letter"
        );
    }
}
