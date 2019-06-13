<?php

namespace App\Util;

use Spatie\PdfToText\Pdf;

abstract class PDFRenderService
{
    abstract public function renderFile($pdfFile);


    private static $meses = ['Urtarrila', 'Otsaila', 'Martxoa', 'Apirila', 'Maiatza', 'Ekaina', 'Uztaila', 'Abuztua', 'Iraila', 'Urria', 'Iraila', 'Abendua'];

    public function extractEvents($file)
    {
        $text = (new Pdf())
            ->setPdf($file)
            ->text();
        $lineas = explode(PHP_EOL, $text);
        $lineas = array_filter($lineas);

        // Creamos el array de eventos
        $calendar = array();

        $desde = false;
        $hasta = false;
        $planReal = false;

        $year = null;
        $month = null;
        $daysInMonth = null;
        $currentDay = null;
        foreach ($lineas as $linea) {

            if ($desde) {
                $aux = explode('.', $linea);
                $desdeDT = \DateTime::createFromFormat('Y/m/d', $aux[2] . '/' . $aux[1] . '/' . $aux[0]);
                $desde = false;
            }
            if ($hasta) {
                $aux = explode('.', $linea);
                $hastaDT = \DateTime::createFromFormat('Y/m/d', $aux[2] . '/' . $aux[1] . '/' . $aux[0]);
                $hasta = false;
            }
            if ($planReal) {
                $currentDay++;

                if ($currentDay <= $daysInMonth) {

                    // Creamos evento y lo incluimos en el calendario
                    $event = array();
                    $event['dtStart'] = (new \DateTime($year . '-' . $month . '-' . $currentDay));
                    $event['dtEnd'] = (new \DateTime($year . '-' . $month . '-' . $currentDay));
                    $event['summary'] = trim($linea);
                    $calendar[] = $event;
                }
            }


            // Preparamos la siguiente iteración
            $lineaArray = explode('/', $linea);
            if (in_array(trim($lineaArray[0]), self::$meses)) {
                $year = intval(substr($linea, -4, 4));
                $month = $this->getMonthIntFromName(trim($lineaArray[0]), self::$meses);
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            }

            switch (trim($linea)) {
                case 'Noiztik / Desde':
                    $desde = true;
                    break;
                case 'Noiz arte / Hasta':
                    $hasta = true;
                    break;
                case 'Plan teorikoa / Plan teórico';
                    $planTeorico = true;
                    $currentDay = 0;
                    break;
                case 'Plan erreala / Plan real':
                    $planReal = true;
                    $currentDay = 0;
                    break;
            }
        }

        return $calendar;
    }

    private function getMonthIntFromName($monthName)
    {
        return array_search($monthName, self::$meses) + 1;
    }
}