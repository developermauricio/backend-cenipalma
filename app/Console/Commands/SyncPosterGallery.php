<?php

namespace App\Console\Commands;

use App\Services\GoogleSheet;
use App\Models\PosterGallery;
use App\Models\Variable;
use Illuminate\Console\Command;

class SyncPosterGallery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:postergallery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando permite poder sincronizar los datos de la galeria de poster.';

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
     * @return int
     */
    public function handle( GoogleSheet $googleSheet )
    {
        $variable = Variable::query()
            ->where('name', 'lastPosterGalleryIDSync')
            ->first();
        
        $rows = PosterGallery::query()
            ->where('id', '>', $variable->value)
            ->orderBy('id')
            ->limit(100)
            ->get();       

        if( $rows->count() === 0 ){
            return  true;
        }

        $finalData = collect();
        $lastId = 0;

        foreach ($rows as $row){
            $fullname = $row->fullname ? $row->fullname : 'Indefinido';
            $username = $row->username ? $row->username : 'Indefinido';

            $finalData->push([
                $row->id,
                $row->email,
                $fullname,
                $username,
                $row->poster_name,
                $row->qualification,
                $row->date_visit,
                $row->created_at,
            ]);

            $lastId = $row->id;
        }   

        $googleSheet->saveDataToSheet(
            $finalData->toArray(),
            '1JuTUAQIQyBaxU5Z0wquc7pUVhrl_A48_K_cpEVP9V5U',
            'poster-gallery',
        );

        $variable->value = $lastId;
        $variable->save();

        return true;
    }
}
