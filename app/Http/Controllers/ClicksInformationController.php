<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Variable;
use App\Models\Conference; 
use App\Models\EventClick; 
use App\Models\Point; 
use App\Models\Scene; 
use App\Models\PosterGallery; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClicksInformationController extends Controller
{
    public function registerSceneVisit( Request $request ) {  
        $currentUser = User::whereEmail( $request->email )->first();
  
        if ( !$currentUser ) {  
            return response()->json(['status' => 'fail', 'msg' => 'registro fallido el usuario no está registrado.']);         
        } 

        $this->registerpointsDB($request, $currentUser);

        if ( $this->registerSceneVisitDB( $currentUser, $request->go_scene ) ) {
            return response()->json(['status' => 'ok', 'msg' => 'registro agredado correctamente.']);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'registro fallido.']);
        }   
    } 

    public function registerSceneVisitDB( $currentUser, $nameScene ) {
        $listScenes = Scene::where('email', $currentUser->email)->get();

        if ( $listScenes ) {
            $dateNow = Carbon::now('America/Bogota');
            $existRegister = false;

            foreach ( $listScenes as $scene ) {
                $dateRegister = new Carbon($scene->created_at, 'America/Bogota');

                if ( $dateNow->dayOfYear == $dateRegister->dayOfYear && $nameScene == $scene->name_scene ) {
                    $existRegister = true;
                    break;
                }
            }

            if ( $existRegister ) {
                return false;
            }
        } 

        DB::beginTransaction();
        try {  
            
           $newScene = new Scene;           
           $newScene->email = $currentUser->email;
           $newScene->fullname = $currentUser->fullname;         
           $newScene->username = $currentUser->username;         
           $newScene->name_scene = $nameScene; 
           $newScene->date_visit = Carbon::now('America/Bogota');
           $newScene->save();
           Log::debug($newScene);
  
           DB::commit();
  
           return true;
  
        } catch (\Exception $exception) {
           DB::rollBack();
           Log::debug($exception->getMessage());
           return false;
        }
    }

    public function registerEventclick( Request $request ) {  
        $currentUser = User::whereEmail( $request->email )->first();
  
        if ( !$currentUser ) {  
            return response()->json(['status' => 'fail', 'msg' => 'registro fallido el usuario no está registrado.']);         
        } 

        $this->registerPointsDB($request, $currentUser);

        if ( $this->registerEventclickDB( $currentUser, $request ) ) {
            return response()->json(['status' => 'ok', 'msg' => 'registro agredado correctamente.']);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'registro fallido.']);
        }         
    } 

    public function registerEventclickDB( $currentUser, $request ) {
        $listEventClick = EventClick::where('email', $currentUser->email)->get();

        if ( $listEventClick ) {
            $dateNow = Carbon::now('America/Bogota');
            $existRegister = false;

            foreach ( $listEventClick as $eventClick ) {
                $dateRegister = new Carbon($eventClick->created_at, 'America/Bogota');

                if ( $dateNow->dayOfYear == $dateRegister->dayOfYear && $request->click_name == $eventClick->click_name && $request->nameScene == $eventClick->name_scene ) {
                    $existRegister = true;
                    break;
                }
            }

            if ( $existRegister ) {
                return false;
            }
        } 

        DB::beginTransaction();
        try {  
            
           $newEventClick = new EventClick;           
           $newEventClick->email = $currentUser->email;
           $newEventClick->fullname = $currentUser->fullname;         
           $newEventClick->username = $currentUser->username;         
           $newEventClick->name_scene = $request->nameScene; 
           $newEventClick->click_name = $request->click_name; 
           $newEventClick->date_visit = Carbon::now('America/Bogota');
           $newEventClick->save();
           Log::debug($newEventClick);
  
           DB::commit();
  
           return true;
  
        } catch (\Exception $exception) {
           DB::rollBack();
           Log::debug($exception->getMessage());
           return false;
        }
    }


    public function registerPointsImageRanking( Request $request ) {  
        $currentUser = User::whereEmail( $request->email )->first();
  
        if ( !$currentUser ) {  
            return response()->json(['status' => 'fail', 'msg' => 'registro fallido el usuario no está registrado.']);         
        } 

        $registerQualification = $this->registerQualificationImage($request, $currentUser);
        Log::debug($registerQualification);
        
        if ( $this->registerpointsDB($request, $currentUser) ) {
            return response()->json(['status' => 'ok', 'msg' => 'registro agredado correctamente.']);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'registro fallido.']);
        }   
    } 

    public function registerQualificationImage($request, $currentUser) {
        if ($request->qualification == 0) return false;

        $listImages = PosterGallery::where('email', $currentUser->email)->get();

        if ( $listImages ) {
            $dateNow = Carbon::now('America/Bogota');
            $existRegister = false;

            foreach ( $listImages as $image ) {
                $dateRegister = new Carbon($image->created_at, 'America/Bogota');

                if ( $dateNow->dayOfYear == $dateRegister->dayOfYear && $request->nameImage == $image->poster_name ) {
                    $existRegister = true;
                    break;
                }
            }

            if ( $existRegister ) {
                return false;
            }
        } 

        DB::beginTransaction();
        try {  
           
           $newPoster = new PosterGallery;           
           $newPoster->email = $currentUser->email;
           $newPoster->fullname = $currentUser->fullname;         
           $newPoster->username = $currentUser->username;         
           $newPoster->poster_name = $request->nameImage; 
           $newPoster->qualification = $request->qualification; 
           $newPoster->date_visit = Carbon::now('America/Bogota');
           $newPoster->save();
           Log::debug($newPoster);
  
           DB::commit();
  
           return true;
  
        } catch (\Exception $exception) {
           DB::rollBack();
           Log::debug($exception->getMessage());
           return false;
        }
    }

    public function registerpointsDB( $request, $currentUser ) {

        if ( $request->points == 0 ) return false;

        $listPoints = Point::where('email', $currentUser->email)->get();

        if ( $listPoints ) {
            $dateNow = Carbon::now('America/Bogota');
            $existRegister = false;

            foreach ( $listPoints as $point ) {
                $dateRegister = new Carbon($point->created_at, 'America/Bogota');

                if ( $dateNow->dayOfYear == $dateRegister->dayOfYear && $request->click_name == $point->click_name && $request->nameScene == $point->name_scene ) {
                    $existRegister = true;
                    break;
                }
            }

            if ( $existRegister ) {
                return false;
            }
        } 

        DB::beginTransaction();
        try {  
           
           $newPoint = new Point;           
           $newPoint->email = $currentUser->email;
           $newPoint->fullname = $currentUser->fullname;         
           $newPoint->username = $currentUser->username;         
           $newPoint->name_scene = $request->nameScene; 
           $newPoint->click_name = $request->click_name; 
           $newPoint->points = $request->points; 
           $newPoint->date_visit = Carbon::now('America/Bogota');
           $newPoint->save();
           Log::debug($newPoint);
  
           DB::commit();
  
           return true;
  
        } catch (\Exception $exception) {
           DB::rollBack();
           Log::debug($exception->getMessage());
           return false;
        }
    }

    public function getScenesVisit() {
        $listScenes = Scene::all();
        return response()->json(['status' => 'ok', 'data' => $listScenes]);
    }

    public function getEventClicks() {
        $listEventClicks = EventClick::all();
        return response()->json(['status' => 'ok', 'data' => $listEventClicks]);
    }

    public function getPosterGallery() {
        $listPosterGallery = PosterGallery::all();
        return response()->json(['status' => 'ok', 'data' => $listPosterGallery]);
    }

    public function registerVariable() {        
        $variable = new Variable;
        //$variable->name = 'lastRegisteredUsersIDSync';
        //$variable->name = 'lastLoggedUserIDSync';
        //$variable->name = 'lastScenesVisitIDSync';
        //$variable->name = 'lastClickEventsIDSync';
        //$variable->name = 'lastPointsIDSync';        
        $variable->name = 'lastPosterGalleryIDSync';
        $variable->value = 0;
        $variable->save();

        return response()->json(['data' => $variable]);
    }

    public function updateVariable() {
        $variable = Variable::where( 'name', 'lastRegisteredUsersIDSync' )->first();        
        //$variable = Variable::where( 'name', 'lastLoggedUserIDSync' )->first();        
        //$variable = Variable::where( 'name', 'lastScenesVisitIDSync' )->first();        
        //$variable = Variable::where( 'name', 'lastClickEventsIDSync' )->first();        
        //$variable = Variable::where( 'name', 'lastPointsIDSync' )->first();         
        $variable->value = 0;
        $variable->save();

        return response()->json(['data' => $variable]);
    }

    public function getVariable() {
        $var = Variable::all();
        return response()->json(['data' => $var]);
    }
}
