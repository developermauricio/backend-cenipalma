<?php

namespace App\Console\Commands;

use App\Services\GoogleSheet;
use App\Models\Scene;
use App\Models\Variable;
use Illuminate\Console\Command;

class ScenesVisited extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:scenesvisited';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando permite poder sincronizar los datos de las escenas visitadas';

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
            ->where('name', 'lastScenesVisitIDSync')
            ->first();
        
        $rows = Scene::query()
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
            $namescene = $row->name_scene ? $row->name_scene : 'Indefinido';

            $finalData->push([
                $row->id,
                $row->email,
                $fullname,
                $username,
                $namescene,
                $row->date_visit,
                $row->created_at,
            ]);

            $lastId = $row->id;
        }   

        $googleSheet->saveDataToSheet(
            $finalData->toArray(),
            '16BqvGAFxIT7TfLOKkooEPST1btiBEebY2xVCXNxIyKE',
            'scenes_visited',
        );

        $variable->value = $lastId;
        $variable->save();

        return true;
    }
}
