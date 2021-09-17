<?php

namespace App\Http\Controllers\PDF;

use Dompdf\Dompdf;
use GdImage;
use Illuminate\Support\Facades\Storage;

class PDFController
{
    private $dompdf = null;

    public function __construct()
    {
        $this->dompdf = new Dompdf();
    }

    public function open($fullName)
    {
        // $blob = Storage::get('certificado/tres.png');
        $path = Storage::path('certificado/tres.png');
        // $path = Storage::path('certificado/medusa.png');

        $imagen = imagecreatefromstring(file_get_contents($path));
        $properties = imagegetclip($imagen);

        $color = imagecolorallocate($imagen, 250, 250, 250);

        // $size = 30;
        $size = 60;
        $angle = 0;

        $x = 250;
        $y = 400;

        $texto = $fullName;

        $pathF = Storage::path('fonts/Roboto/Roboto-Bold.ttf');

        imagealphablending($imagen, true);
        imagesavealpha($imagen, true);
        imagettftext($imagen, $size, $angle, $x, $y, $color, $pathF, $texto);

        ob_start();
        imagepng($imagen, null, 0);
        $blob = ob_get_contents();
        ob_end_clean();
        imagedestroy($imagen);

        $html = '<html><head><style>@page {margin: 0px;}body {background-color: red;margin: 0px; background-repeat: no-repeat; background-attachment: fixed; background-position: center center; padding: 0; background-size: contain;}</style></head><body style="background-image: url(data:image/png;base64,' . base64_encode($blob) . ');"></body></html>';
        // $html = '<html><img src="data:image/svg+xml;base64,' . base64_encode($blob) . '" /></html>';

        $this->dompdf->loadHtml($html);
        // $this->dompdf->setPaper('A4', 'landscape');
        $this->dompdf->setPaper('A4', 'landscape');
        $this->dompdf->getPaperSize();
        $this->dompdf->render();
        $this->dompdf->stream("filename.pdf", array("Attachment" => false));
    }
}
