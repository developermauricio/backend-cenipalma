<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\LoginUser; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserInformationController extends Controller
{
   public function registerOrLoginUser( Request $request ) {      
      $currentUser = User::whereEmail( $request->email )->first();

      if ( $currentUser ) {
         if ( $login = $this->loginUser( $currentUser ) ) {
            return response()->json(['status' => 'ok', 'msg' => 'Registro de login con exito.']);
         } else {
            return response()->json(['status' => 'fail', 'msg' => 'registro de login fallido.']);
         }         
      } 
           
      DB::beginTransaction();
      try {

         $newUser = new User;
         $newUser->email = $request->email;
         $newUser->username = $request->username; 
         $newUser->fullname = $request->fullname ? $request->fullname : 'User cenipalma';         
         $newUser->date_register = Carbon::now('America/Bogota'); 
         $newUser->save();
         Log::debug($newUser);

         DB::commit();

         return response()->json(['status' => 'ok', 'msg' => 'Usuario registrado correctamente.']);

      } catch (\Exception $exception) {
         DB::rollBack();
         Log::debug($exception->getMessage());
         return response()->json([ 'status' => 'fail', 'msg' => $exception->getMessage() ]);
      }
   } 
  
   public function loginUser( $user ) {
      $userlogins = LoginUser::where( 'email', $user->email )->get();

      if ( $userlogins ) {
         $dateNow = Carbon::now('America/Bogota');
         $loginToday = false;

         foreach ($userlogins as $login) {
            $created_at = new Carbon($login->created_at, 'America/Bogota');

            if ( $dateNow->dayOfYear == $created_at->dayOfYear ) {
               $loginToday = true;
               break;
            }
         }

         if ( $loginToday ) {
            return false;
         }
      }

      DB::beginTransaction();
      try {

         $newLoginUser = new LoginUser;
         $newLoginUser->email = $user->email;
         $newLoginUser->username = $user->username; 
         $newLoginUser->fullname = $user->fullname;         
         $newLoginUser->date_register = Carbon::now('America/Bogota');
         $newLoginUser->save();
         Log::debug($newLoginUser);

         DB::commit();

         return true;

      } catch (\Exception $exception) {
         DB::rollBack();
         Log::debug($exception->getMessage());
         return false;
      }
   }

   public function getUserRegister() {
      $listUsers = User::all(); 
      return response()->json(["status" => "ok", "data" => $listUsers]);  
   }
  
   public function getUserLogin() { 
      $listUsers = LoginUser::all(); 
      return response()->json(["status" => "ok", "data" => $listUsers]);  
   }
}
