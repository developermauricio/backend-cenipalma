<?php

namespace App\Http\Controllers\Utils;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;

class PDFController
{
    private $dompdf = null;

    public function __construct()
    {
        $this->dompdf = new Dompdf();
        $this->config();
    }

    private function config()
    {
        $this->dompdf->setPaper('letter', 'landscape');
    }

    public function open(string $html)
    {
        $this->dompdf->loadHtml($html);
    }

    public function download($fileName, $attachment = true)
    {
        $this->dompdf->render();
        $this->dompdf->stream($fileName, array("Attachment" => $attachment));
    }

    public function saveFile($fileName)
    {
        $this->dompdf->render();
        $output = $this->dompdf->output();
        return Storage::put($fileName, $output);
    }
}
