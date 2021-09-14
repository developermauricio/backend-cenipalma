<?php

namespace App\Console\Commands;

use App\Services\GoogleSheet;
use Carbon\Carbon;
use App\Models\LoginUser;
use App\Models\Variable;
use Illuminate\Console\Command;

class LoggedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:loggedusers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando permite poder sincronizar los datos de los usuarios logeados';

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
            ->where('name', 'lastLoggedUserIDSync')
            ->first();
        
        $rows = LoginUser::query()
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
                $row->date_register,
                $row->created_at,
            ]);

            $lastId = $row->id;
        }   

        $googleSheet->saveDataToSheet(
            $finalData->toArray(),
            '1fY6Ha8zfKkTeCzrsCJb257eDRuVa5-p0QpLODO03voQ',
            'logged_users',
        );

        $variable->value = $lastId;
        $variable->save();

        return true;
    }
}
