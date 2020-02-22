<?php

namespace App\Controller;

use App\Repository\BookRepository;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookController extends AbstractController
{
    /**
     * @Route("/", name="book")
     */
    public function index(Request $request , BookRepository $bookRepository)
    {
        $books=$bookRepository->findAll();
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
            'books' => $books,
        ]);
    }
    /**
     * @Route("/{id}", name="book_edit")
     */
    public function edit(Request $request,Book $book)
    {
        if($request->getMethod()=='POST'){
            $entityManager = $this->getDoctrine()->getManager();
            $book->setName($request->get('name'));
            $book->setAuthor($request->get('author'));
            $book->setYear($request->get('year'));
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirectToRoute('book');
        }
        return $this->render('book\edit.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/add", name="book_create")
     */
    public function create(Request $request){
        if($request->getMethod()=='POST'){
            $book = new Book();
            $entityManager = $this->getDoctrine()->getManager();
            $book->setName($request->get('name'));
            $book->setAuthor($request->get('author'));
            $book->setYear($request->get('year'));
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirect('book');
        }
        return $this->render('book\create.html.twig');
    }
}
