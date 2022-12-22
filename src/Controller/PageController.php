<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\User;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @method User getUser()
 */

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class PageController extends AbstractController
{
    #[Route('/{id?}', name: 'app_page')]
    public function index(?Page $page, PageRepository $repository, EntityManagerInterface $entityManager): Response
    {
        if (!$page) {
            $page = new Page($this->getUser());

            $entityManager->persist($page);
            $entityManager->flush();


            return $this->redirectToRoute('app_page', [
                'id' => $page->getId()
            ]);

        }

        return $this->render('page/index.html.twig', [
            'current_page' => $page,
            'pages' => $this->getUser()->getPages()
        ]);
    }

    #[Route('/{id?}', name: 'app_page_add', methods: ['POST'])]
    public function add(EntityManagerInterface $em): Response
    {
        $page = new Page($this->getUser());

        $em->persist($page);
        $em->flush();

        return $this->json([
           'result' => $page->getId()
        ]);
    }

    #[Route('/{id?}', name: 'app_page_update', methods: ['GET', 'PATCH'])]
    public function update(?Page $page, Request $request, EntityManagerInterface $em): Response
    {
        if (!$page) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        if ($request->isMethod(Request::METHOD_PATCH)) {
            $content = json_decode($request->getContent(), true);

            if (isset($content['content'])) {
                $page->setContent($content['content']);
            }

            if (isset($content['title'])) {
                $page->setTitle($content['title']);
            }

            $em->flush();
        }

        return $this->json([
            'result' => [
                'title' => $page->getTitle(),
                'content' => json_decode($page->getContent())
            ]
        ]);

    }

    #[Route('/pages/id', name: 'app_page_delete', methods: ['DELETE'])]
    public function delete(?Page $page, EntityManagerInterface $em): Response
    {
        if (!$page) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        $em->remove($page);
        $em->flush();

        return $this->json([
           'result' => null
        ]);
    }
}
