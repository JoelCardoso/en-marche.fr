<?php

namespace AppBundle\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class LegislativesController extends Controller
{
    /**
     * @Route("/candidates", name="api_legislatives_candidates", methods={"GET"})
     */
    public function getCandidatesListAction(): JsonResponse
    {
        return new JsonResponse($this->get('app.api.legislative_candidate_provider')->getForApi());
    }
}
