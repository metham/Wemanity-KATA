<?php

namespace App\Controller;

use App\Service\MineFieldService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MineSweeperController extends AbstractController
{

    private MineFieldService $mineFieldService;

    public function __construct(MineFieldService $mineFieldService)
    {
        $this->mineFieldService = $mineFieldService;
    }

    /**
     * @Route("/minefield", methods={"POST"})
     * @throws \JsonException
     */
    public function index(Request $request): Response
    {
        $body = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        if(!$body) {
            return $this->json(["No field given"], 400);
        }
        $minefields = $body["minefields"];

        if(!$minefields) {
            return $this->json(["No field given"], 400);
        }

        $output = $this->mineFieldService->solveMinefields($minefields);

        return $this->json([
            $output,
        ], 202);
    }
}
