<?php

namespace App\Http\Controllers\Certificado;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\ImgController;
use App\Http\Controllers\Utils\PDFController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificadoController extends Controller
{

    public function index()
    {
        return view('certificado');
    }

    public function checkCertificado(Request $request)
    {
        $user = User::select('id', 'fullName', 'certificado')
            ->where('email', $request->input('email'))
            ->first();

        if ($user && (!$user->certificado || !Storage::exists($user->certificado))) {
            $user->certificado = null;
            $pdf = $this->generateCertificate($user->fullName);
            $path = 'certificates/certificado_' . $user->id . ".pdf";
            if ($pdf->saveFile($path)) {
                $user->certificado = $path;
                $user->save();
            }
        }

        if ($user) {
            $user->hasCertificado = isset($user->certificado);
            return response()->json($user);
        } else {
            return response()->json([
                'hasCertificado' => false
            ]);
        }
    }

    public function download(Request $request)
    {
        $user = User::select('certificado')
            ->where('email', $request->input('email'))
            ->where('id', $request->input('key'))
            ->first();
        if ($user && $user->certificado) {
            return Storage::download($user->certificado, "certificado.pdf");
            // $fullName = $user->fullname;
            // return $this->generateCertificate($fullName)->download('Certificado', false);
        } else if ($user) {
            // return $this->generateCertificate($fullName)->download('Certificado', false);
        }
        return response()->json([
            "msg" => "Certificado no encontrado"
        ], 400);
    }

    private function generateCertificate($fullName)
    {
        $pathCertificado = Storage::path('certificado/uno.png');

        $image = new ImgController();
        $image->openImagePng($pathCertificado);

        //texto
        $size = 30;
        $angle = 0;
        $x = 280;
        $y = 400;
        $pathFont = Storage::path('fonts/Roboto/Roboto-Bold.ttf');
        $color = $image->getColor(0, 0, 0);

        $image->setText($size, $angle, $x, $y, $color, $pathFont, $fullName);

        $imageBlob = $image->download();

        $html = '
            <html>
                <head>
                    <title>Certificado</title>
                    <style>
                        @page {margin: 0px;}
                        body {
                            margin: 0px;
                            background-repeat: no-repeat;
                            background-attachment: fixed;
                            background-position: center center;
                            padding: 0;
                            background-size: contain;
                        }
                    </style>
                </head>
                <body style="background-image: url(data:image/png;base64,' . base64_encode($imageBlob) . ');"></body>
            </html>';

        $pdf = new PDFController();
        $pdf->open($html);
        return $pdf;
    }
}
