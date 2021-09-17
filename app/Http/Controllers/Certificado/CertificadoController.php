<?php

namespace App\Http\Controllers\Certificado;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PDF\PDFController;
use App\Models\LoginUser;
use App\Models\User;
use Illuminate\Http\Request;

class CertificadoController extends Controller
{

    public function index()
    {
        return view('certificado');
    }

    public function download(Request $request)
    {
        $user = LoginUser::where('email', $request->input('email'))->first();
        if ($user) {
            $fullName = $user->fullname;


            $pdf = new PDFController();
            $pdf->open($fullName);
        }
    }
}
