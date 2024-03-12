<?php

namespace App\Libraries;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class Qrcode
{
    private static $filePath;

    public static function setPath($path)
    {
        self::$filePath = $path;
    }

    public static function create($content) :string
    {
        if (is_array($content)) {
            $content = json_encode($content);
        }

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        );
        $writer = new Writer($renderer);
        $witeString = $writer->writeString($content, self::$filePath);
        return $writeString;
    }
}