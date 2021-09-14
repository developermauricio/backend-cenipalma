<?php

namespace App\Console\Commands;

use App\Services\GoogleSheet;
use App\Models\Point;
use App\Models\Variable;
use Illuminate\Console\Command;

class Points extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando permite poder sincronizar los datos de los puntos';

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
            ->where('name', 'lastPointsIDSync')
            ->first();
        
        $rows = Point::query()
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
                $row->name_scene,
                $row->click_name,
                $row->points,
                $row->date_visit,
                $row->created_at,
            ]);

            $lastId = $row->id;
        }   

        $googleSheet->saveDataToSheet(
            $finalData->toArray(),
            '1A7nXhl36Py8gb5DjL8r-LW3i4oSOa8C8AzCcoF2ORVE',
            'points',
        );

        $variable->value = $lastId;
        $variable->save();

        return true;
    }
}
