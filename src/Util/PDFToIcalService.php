<?php


namespace App\Util;


use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;

class PDFToIcalService extends PDFRenderService
{

    public function renderFile($file)
    {

        $eventsArray = $this->extractEvents($file);

        $vCalendar = new Calendar('OsakidetzaIcal');

        foreach ($eventsArray as $event) {
            $vEvent = new Event();
            $vEvent
                ->setDtStart($event['dtStart'])
                ->setDtEnd($event['dtEnd'])
                ->setNoTime(true)
                ->setSummary($event['summary']);
            $vCalendar->addComponent($vEvent);
        }

    }
}