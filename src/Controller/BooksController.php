<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Comment;
use App\Entity\Lendings;
use App\Form\BookEditType;
use App\Form\BookFormType;
use App\Form\CommentFormType;
use App\Repository\BookRepository;
use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Message\CommentMessage;
use App\Repository\LendingsRepository;
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

    #[Route('/add_book', name:'add_book')]
    public function addBook(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookFormType::class, $book);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($book);
            $this->em->flush();
            
            return $this->redirectToRoute('books');
        }

        return $this->render('books/add_book.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/book/{id}', name: 'book')]
    public function show(
        Environment $twig,
        Book $book,
        CommentRepository $commentRepository,
        LendingsRepository $lendingsRepository,
        Request $request
    ): Response {

        $createdAt = new DateTime();
        $comment = new Comment();
        $lendings = $lendingsRepository->findLendingsForBook($book);

        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setDate($createdAt);
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
            'lendings' => $lendings,
            'previous' =>$offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form->createView()
        ]));
    }

    #[Route('/book/edit/{id}', name:'edit_book')]
    public function edit(Request $request, $id) : Response
    {      
        $book = $this->em->getRepository(Book::class)->find($id);

        $form =  $this->createForm(BookEditType::class, $book);

        $form->handleRequest($request);
    

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($book);
            $this->em->flush();

            return $this->redirectToRoute('books');
        }

        return$this->render('books/edit_book.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/book/delete/{id}', name:'delete_book')]
    public function deleteBook($id) : Response
    {      
        $book = $this->em->getRepository(Book::class)->find($id);

        if(!$book)
        {
            throw $this->createNotFoundException('This book does not exist.');
        }

        $this->em->remove($book);
        $this->em->flush();

        return $this->redirectToRoute('books');
    }

    #[Route('/book/{id}/borrow', name:'borrow_book')]
    public function borrowBook(Request $request, $id)
    {
        $book =  $this->em->getRepository(Book::class)->find($id);

        if(!$this->getUser())
        {
            return $this->redirectToRoute('app_login');
        }

        $lending = new Lendings();
        $lending->setBook($book);
        $lending->setUserId($this->getUser());

        $isAvailable = $this->isBookAvailable($book);
        $date = new DateTime();

        if(!$isAvailable)
        {

            $date = $this->getAvailableDate($book);
            $lending->setFromDate($date);
        }
        else
        {
            $lending->setFromDate(new DateTime());
        }

        $toDate = clone $date;
        $toDate->modify('+14 days');
        $lending->setToDate($toDate);

        $this->em->persist($lending);
        $this->em->flush();

        return $this->redirectToRoute('book', ['id' => $id]);
    }

    private function isBookAvailable(Book $book) : bool
    {
        $today = new DateTime();
        foreach($book->getLendings() as $lending)
        {
            if($today >= $lending->getFromDate() && $today <= $lending->getToDate())
            {
                return false;
            }
        }
        return true;
    }

    private function getAvailableDate(Book $book)
    {
        $latest = $book->getLatestLending();

        if($latest)
        {
            $date = clone $latest;
            $date->modify('+1 day');
        }
        else
        {
            $date = new DateTime();
        }
        return $date;
    }
}
