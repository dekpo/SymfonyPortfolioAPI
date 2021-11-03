<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{
    private $pictureRepository;
    public function __construct(PictureRepository $pictureRepository)
    {
       $this->pictureRepository = $pictureRepository;
    }
     /**
     * @Route("/pictures", name="pictures")
     */
    public function getAll(): JsonResponse
    {
       $pictures = $this->pictureRepository->findAll();
       $data = [];
       foreach($pictures as $pic){
           $data[] = [
               'id'     => $pic->getId(),
               'url'    => $pic->getUrl(),
               'author' => $pic->getAuthor(),
               'title'  => $pic->getTitle(),
               'description' => $pic->getDescription()
           ];
       }
       return new JsonResponse($data,Response::HTTP_OK);
    }
    /**
     * @Route("/picture", name="picture")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PictureController.php',
        ]);
    }
}
