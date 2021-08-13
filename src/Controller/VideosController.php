<?php


namespace App\Controller;


use App\Entity\Videos;
use App\Repository\VideoCategoryRepository;
use App\Repository\VideosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideosController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private VideosRepository $repository;
    private VideoCategoryRepository $categoryRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        VideosRepository $repository,
        VideoCategoryRepository $categoryRepository

    )
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/videos",methods={"GET"})
     */
    public function index(Request $request): Response
    {

        $query = $request->query->all();

        $page = array_key_exists('page',$query) ? $query['page'] : 1;
        unset($query['page']);


        $videos = $this->repository->findBy(
            $query,
            ['title'=>'ASC'],
            5,
            ($page -1)*5
        );
        return new JsonResponse($videos);
    }

    /**
     * @Route("/videos/{id}",methods={"GET"},requirements={"id"="\d+"})
     */
    public function show(int $id): Response
    {


        $video = $this->repository->find($id);



        if(is_null($video)){
            return new JsonResponse('Not Found',Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($video, Response::HTTP_OK);
    }


    /**
     * @Route("/videos",methods={"POST"})
     *
     */

    public function create(Request $request): Response
    {
        $body = json_decode($request->getContent(),true);
        $category = $this->categoryRepository->find(1);
        $video = new Videos();

        switch ($body){
            case !array_key_exists('title',$body):
                return new JsonResponse('Todos os campos são obrigatórios, você não preencheu o título',
                    Response::HTTP_BAD_REQUEST);
                break;
            case !array_key_exists('description',$body):
                return new JsonResponse('Todos os campos são obrigatórios, você não preencheu a descrição',
                    Response::HTTP_BAD_REQUEST);
                break;
            case !array_key_exists('url', $body):
                return new JsonResponse('Todos os campos são obrigatórios, você não preencheu a url',
                    Response::HTTP_BAD_REQUEST);
                break;
            case !array_key_exists('categoryId',$body):
                $video->setCategoriaId($category);
                break;

        }
        //TODO: REFATORAR para obj URGENTE
        if(array_key_exists('categoryId',$body)){
            $category = $this->categoryRepository->find($body['categoryId']);
            $video->setCategoriaId($category);
        }

        $video
            ->setTitle($body['title'])
            ->setDescription($body['description'])
            ->setUrl($body['url']);

        $this->entityManager->persist($video);
        $this->entityManager->flush();

        return new JsonResponse($video,Response::HTTP_CREATED);

    }

    /**
     * @Route("/videos/{id}",methods={"PUT","PATCH"})
     */
    public function update(int $id, Request $request): Response
    {

        //funciona com JSON
            //$body = json_decode($request->getContent());


        // só funciona com x-www-form-urlencoded
        $body = $request->request->all();
        $video = $this->repository->find($id);



        // só funciona com x-www-form-urlencoded
       if($request->request->has('title')){
            $video->setTitle($body['title']);
        }

        if($request->request->has('description')){
            $video->setDescription($body['description']);
        }

        if($request->request->has('url')){
            $video->setUrl($body['url']);
        }


        //TODO: preciso entender melhor esse comportamento

        /*$video
            ->setTitle($body->title)
            ->setDescription($body->description)
            ->setUrl($body->url);*/

        $this->entityManager->flush();

        return new JsonResponse($video,Response::HTTP_OK);
    }

    /**
     * @Route("/videos/{id}",methods={"DELETE"})
     */
    public function remove(int $id): Response
    {
        $video = $this->repository->find($id);
        $this->entityManager->remove($video);
        $this->entityManager->flush();

        return new JsonResponse('',Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/categorias/{id}/videos",methods={"GET"}, name="api.category")
     */
    public function findByCategory(int $id)
    {
        $repository = $this
            ->getDoctrine()
            ->getRepository(Videos::class);

        $category = $repository->findBy([
            'categoriaId' =>$id
        ]);

        return new JsonResponse($category);
    }

    /**
     * @Route("/videos/free",methods={"GET"})
     * TODO: Preciso pensar em uma forma de náo repetir a função só pra ter uma rota personalizada
     */
    public function showFreeCategory(): Response
    {

        $videos = $this->repository->findBy([
            'categoriaId' => 1
        ]);



        return new JsonResponse($videos);
    }

}