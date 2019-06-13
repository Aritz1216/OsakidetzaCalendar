<?php


namespace App\Util;


class PDFToJsonService extends PDFRenderService
{

    public function renderFile($pdfFile)
    {
        return $this->extractEvents($pdfFile);
    }
}