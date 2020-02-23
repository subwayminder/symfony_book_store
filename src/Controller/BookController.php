<?php

namespace App\Controller;

use App\Repository\BookRepository;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class BookController extends AbstractController
{
    /**
     * @Route("/", name="book")
     */
    public function index(Request $request , BookRepository $bookRepository, PaginatorInterface $paginator)
    {
        $books=$bookRepository->findAll();

        $pagination = $paginator->paginate(
            $books, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
            'books' => $pagination,
        ]);
    }
    /**
     * @Route("/edit/{id}", name="book_edit")
     */
    public function edit(Request $request,Book $book, ValidatorInterface $validator)
    {
        if($request->getMethod()=='POST'){
            $entityManager = $this->getDoctrine()->getManager();

            $book->setName($request->get('name'));
            $book->setAuthor($request->get('author'));
            $book->setYear($request->get('year'));
            $errors=$validator->validate($book);
            if(count($errors)>0){
                return new Response((string) $errors, 400);
            }
            else{
                $entityManager->persist($book);
                $entityManager->flush();
                return $this->redirectToRoute('book');
            }
        }
        return $this->render('book\edit.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/add/", name="book_create")
     */
    public function create(Request $request, ValidatorInterface $validator){
        if($request->getMethod()=='POST'){
            $book = new Book();
            $entityManager = $this->getDoctrine()->getManager();
            $book->setName($request->get('name'));
            $book->setAuthor($request->get('author'));
            $book->setYear($request->get('year'));
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirectToRoute('book');
        }
        return $this->render('book\create.html.twig');
    }
}
