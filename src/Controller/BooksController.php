<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\BookRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Message\CommentMessage;
use Twig\Environment;

class BooksController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, 
    private MessageBusInterface $bus)
    {
        
    }

    #[Route('/', name: 'books')]
    public function index(Environment $twig,
        BookRepository $bookRepository): Response
    {
        return new Response($twig->render('books/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]));
    }

    #[Route('/book/{id}', name: 'book')]
    public function show(
        Environment $twig,
        Book $book,
        CommentRepository $commentRepository,
        Request $request
    ): Response {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setBook($book);

            $this->em->persist($comment);
            $this->em->flush();

            $context = [
                'user_ip' => $request->getClientIp(),
                'referrer' => $request->headers->get('referrer'),
                'permalink' => $request->getUri(),
            ];

            $this->bus->dispatch(new CommentMessage($comment->getId(),
                $context));
            
            return $this->redirectToRoute('book', [
                'id' => $book->getId()
            ]);
        }

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator( $book, $offset);

        return new Response($twig->render('books/show.html.twig', [
            'book' => $book,
            'comments' => $paginator,
            'previous' =>$offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form->createView()
        ]));
    }
}
