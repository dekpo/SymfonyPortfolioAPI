<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{
    private $pictureRepository;
    public function __construct(PictureRepository $pictureRepository)
    {
       $this->pictureRepository = $pictureRepository;
    }
     /**
     * @Route("/pictures/count", name="pictures_count")
     */
    public function getCount(): JsonResponse
    {
       $count = $this->pictureRepository->count([]);
       $data = ['count'=>$count];
       return new JsonResponse($data,Response::HTTP_OK);
    }
     /**
     * @Route("/pictures/list", name="pictures_list")
     */
    public function getList(Request $request): JsonResponse
    {
       $page = $request->query->get('page')!==null ? $request->query->get('page') : 1;
       $limit = $request->query->get('limit')!==null ? $request->query->get('limit') : 6;
       
       $pictures = $this->pictureRepository->findBy([],['date_updated'=>'DESC'],$limit,($page-1)*$limit);
       $data = [];
       foreach($pictures as $pic){
           $data[] = [
               'id'     => $pic->getId(),
               'url'    => $pic->getUrl(),
               'title'  => $pic->getTitle(),
               'description' => substr($pic->getDescription(),0,130)."...",
           ];
       }
       return new JsonResponse($data,Response::HTTP_OK);
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
               'description' => $pic->getDescription(),
               'date_created' => $pic->getDateCreated(),
               'date_updated' => $pic->getDateUpdated(),
               'likes' => $pic->getLikes()
           ];
       }
       return new JsonResponse($data,Response::HTTP_OK);
    }
    /**
     * @Route("/picture", name="add_picture", methods={"POST"})
     */
    public function addPicture(Request $request): JsonResponse
    {
        $url = $request->request->get('url');
        $author = $request->request->get('author');
        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $date = new \DateTime();
        $date->format('Y-m-d H:i:s');
         if (empty($url) || empty($author) || empty($title)){
             throw new NotFoundHttpException('Mandatory values expected !!!');
         }
        $this->pictureRepository->savePicture($url,$author,$title,$description,$date);
        return new JsonResponse(['status'=>'New Picture added !!!'],Response::HTTP_CREATED);
    }
    /**
     * @Route("/picture/{id}", name="update_picture", methods={"PUT"})
     */
    public function updatePicture($id,Request $request): JsonResponse
    {
        $pic = $this->pictureRepository->findOneBy(['id'=>$id]);
        $updatePicture = $this->pictureRepository->updatePicture($pic,$request);
        return new JsonResponse($updatePicture->toArray(),Response::HTTP_OK);
    }
    /**
     * @Route("/picture/{id}/like", name="like_picture", methods={"PUT","POST"})
     */
    public function likePicture($id): JsonResponse
    {
        $pic = $this->pictureRepository->findOneBy(['id'=>$id]);
        $updatePicture = $this->pictureRepository->likePicture($pic);
        return new JsonResponse($updatePicture->toArray(),Response::HTTP_OK);
    }
    /**
     * @Route("/picture/{id}", name="get_one_picture", methods={"GET"})
     */
    public function getOnePicture($id): JsonResponse
    {
        $pic = $this->pictureRepository->findOneBy(['id'=>$id]);
        $data = [
                'id'    => $pic->getId(),
               'url'    => $pic->getUrl(),
               'author' => $pic->getAuthor(),
               'title'  => $pic->getTitle(),
               'description' => $pic->getDescription(),
               'date_created' => $pic->getDateCreated(),
               'date_updated' => $pic->getDateUpdated(),
               'likes' => $pic->getLikes()
        ];
        return new JsonResponse($data,Response::HTTP_OK);
    }
    /**
     * @Route("/picture/{id}", name="delete_picture", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $picture = $this->pictureRepository->findOneBy(['id'=>$id]);
        $this->pictureRepository->removePicture($picture);
        return new JsonResponse(['status'=>'Picture deleted !!!'],Response::HTTP_OK);
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
