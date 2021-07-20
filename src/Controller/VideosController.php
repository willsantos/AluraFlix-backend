<?php


namespace App\Controller;


use App\Entity\Videos;
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

    public function __construct(
        EntityManagerInterface $entityManager,
        VideosRepository $repository
    )
    {

        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @Route("/videos",methods={"GET"})
     */
    public function index(): Response
    {
        $videos = $this->repository->findAll();
        return new JsonResponse($videos);
    }

    /**
     * @Route("/videos/{id}",methods={"GET"})
     */
    public function show(int $id): Response
    {
        $video = $this->repository->find($id);

        if(is_null($video)){
            return new JsonResponse('Not Found',Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($video);
    }


    /**
     * @Route("/videos",methods={"POST"})
     *
     */

    public function create(Request $request): Response
    {
        $body = json_decode($request->getContent());

        $video = new Videos();
        $video
            ->setTitle($body->title)
            ->setDescription($body->description)
            ->setUrl($body->url);

        $this->entityManager->persist($video);
        $this->entityManager->flush();

        return new JsonResponse($video,Response::HTTP_CREATED);

    }

    /**
     * @Route("/videos/{id}",methods={"PUT","PATCH"})
     */
    public function update(int $id, Request $request): Response
    {
        $body = json_decode($request->getContent());
        $video = $this->repository->find($id);

        //TODO: precisa implementar o PATCH tb, no momento ele necessita receber todos os campos

        $video
            ->setTitle($body->title)
            ->setDescription($body->description)
            ->setUrl($body->url);

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

}