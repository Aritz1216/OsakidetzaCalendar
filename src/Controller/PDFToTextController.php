<?php

namespace App\Controller;

use App\Util\PDFToIcalService;
use App\Util\PDFToJsonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PDFToTextController extends AbstractController
{
    /**
     * @Route("/file/ical", name="fichero_pdf")
     *
     * @param PDFToIcalService $icalService
     * @return Response
     */
    public function fileIcal(PDFToIcalService $icalService)
    {
        $fichero = 'Cartelera.pdf';
        $ficheroString = $icalService->renderFile($fichero);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/calendar');
        $response->headers->set('Content-Disposition', 'attachment; filename="osakidetza.ical"');
        $response->headers->set('Content-Length', strlen($ficheroString));

        $response->setContent($ficheroString);

        return $response;
    }

    /**
     * @Route("/file/json", name="fichero_json")
     *
     * @param PDFToJsonService $jsonService
     * @return JsonResponse
     */
    public function fileJson(PDFToJsonService $jsonService)
    {
        $fichero = 'Cartelera.pdf';
        $ficheroArray = $jsonService->renderFile($fichero);


        return new JsonResponse($ficheroArray);

    }


}
