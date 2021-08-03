<?php

namespace App\Controller;

use App\Entity\VideoCategory;
use App\Repository\VideoCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideoCategoriesController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private VideoCategoryRepository $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        VideoCategoryRepository $repository
    )
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @Route("/categorias",methods={"GET"})
     */
    public function index(): Response
    {
        $category = $this->repository->findAll();
        return new JsonResponse($category,Response::HTTP_OK);
    }

    /**
     * @Route("/categorias/{id}",methods={"GET"})
     */
    public function show(int $id): Response
    {
        $category = $this->repository->find($id);

        if(is_null($category)){
            return new JsonResponse('Not Found',Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($category,Response::HTTP_OK);

    }

    /**
     * @Route("/categorias",methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $body = json_decode($request->getContent(),true);

        //TODO: Validate fields

        $category = new VideoCategory();
        $category
            ->setTitle($body['title'])
            ->setColor($body['color']);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return new JsonResponse($category,Response::HTTP_CREATED);

    }

    /**
     * @Route("/categorias/{id}",methods={"PUT","PATCH"})
     */
    public function update(int $id, Request $request): Response
    {
        $body = json_decode($request->getContent());
        $category = $this->repository->find($id);

        if(isset($body->title)){
            $category->setTitle($body->title);
        }
        if(isset($body->color)){
            $category->setColor($body->color);
        }

        $this->entityManager->flush();

        return new JsonResponse($category,Response::HTTP_OK);

    }

    /**
     * @Route("/categorias/{id}",methods={"DELETE"})
     */
    public function remove(int $id): Response
    {
        $category = $this->repository->find($id);
        if(is_null($category)){
            return new JsonResponse('Not Found',Response::HTTP_NOT_FOUND);
        }
        $this->entityManager->remove($category);
        $this->entityManager->flush();

        return new JsonResponse('',Response::HTTP_NO_CONTENT);
    }


}