<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ConferenceController extends AbstractController
{
    /* TODO pasando el valor por query. ej:"https://127.0.0.1:8000/?hello=tronco" */
    #[Route('/', name: 'homepage')]
    public function index(ConferenceRepository $conferenceRepository): Response
    {
        /* *Una buena alternativa a llamar a entityManagerInterface es directamente llamar al repositorio de la clase que utilizaremos */
        #$conferences = $entityManager->getRepository(Conference::class)->findAll();
        #dd($conference);
        $conferences = $conferenceRepository->findAll();
        return $this->render('/conference/index.html.twig',[
            'conferences' => $conferences
        ]);
    }
    #[Route('/conference/{id}',name:'conference')]
    /*public function show(Environment $twig,Conference $conference, CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy(['conference' => $conference], ['createdAt' => 'DESC']);
        #dd($comments);
        return new Response($twig->render('/conference/show.html.twig',[
            'conference' => $conference,
            'comments' => $comments
        ]));
    }*/
    public function show(Request $request,Conference $conference, CommentRepository $commentRepository): Response
    {
        $offset = max($request->query->getInt('offset'),0);
        $paginator = $commentRepository->getCommentPaginator($conference,$offset);

        return $this->render('/conference/show.html.twig',[
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - commentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + $commentRepository::PAGINATOR_PER_PAGE)
        ]);
    }
}
