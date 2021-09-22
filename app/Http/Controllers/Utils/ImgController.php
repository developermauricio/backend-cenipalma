<?php

namespace App\Http\Controllers\Utils;


class ImgController
{
    private $imagen = null;

    public function openImagePng(string $path)
    {
        if ($this->imagen !== null) {
            $this->reset();
        }
        $this->imagen = imagecreatefrompng($path);
        $this->setConfigPng();
    }

    private function setConfigPng()
    {
        imagealphablending($this->imagen, true);
        imagesavealpha($this->imagen, true);
    }

    public function setText(float $size, float $angle, int $x, int $y, int $color, string $pathFont, $texto)
    {
        if ($this->imagen === null) {
            return;
        }

        imagettftext($this->imagen, $size, $angle, $x, $y, $color, $pathFont, $texto);
    }

    public function download()
    {
        if ($this->imagen === null) {
            return;
        }

        ob_start();
        imagepng($this->imagen, null, 0);
        $blob = ob_get_contents();
        ob_end_clean();
        $this->reset();
        return $blob;
    }

    public function reset()
    {
        if ($this->imagen === null) {
            return;
        }

        $destroyed = imagedestroy($this->imagen);
        if ($destroyed) {
            $this->imagen = null;
        }
    }

    public function getColor(int $R, int $G, int $B)
    {
        return imagecolorallocate($this->imagen, $R, $G, $B);
    }

    public function getParams()
    {
        return imagegetclip($this->imagen);
    }
}
