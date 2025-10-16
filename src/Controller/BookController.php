<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Form\BookType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Author;
use App\Repository\AuthorRepository;


final class BookController extends AbstractController
{    
    #[Route('/getAuthor', name: 'app_getAuthor')]
    public function getAuthor(AuthorRepository $a): Response
    {
        $authors = $a->findAll();   
        return $this->render('book/index.html.twig', [
            'BookController' => 'BookController',
            'authors' => $authors,
        ]);
    }
   // get function  "affichage" 
   
    #[Route('/getBook', name: 'app_getBook')]
    public function getBook(BookRepository $book ): Response
    {
        $books = $book->findAll();
        return $this->render('book/getBook.html.twig', [
            'BookController' => 'BookController',
            'books' => $books,
        ]);
    }
  //  add function 
    #[Route('/addBook', name: 'app_addBook')]
    public function addBook(ManagerRegistry $mr ,Request $request): Response
    {
       $m= $mr->getManager(); // demande au manager de gerer les entites
        $book = new Book(); 
        $from= $this->createForm(BookType::class,$book);   
        $form= $from->handleRequest($request); // gere la requete
        if($form->isSubmitted() && $form->isValid()){
            $m->persist($book); // prepare l'insertion
            $m->flush(); // execute l'insertion
            return $this->redirectToRoute('app_getBook');
        }    

        return $this->render('book/addBook.html.twig', [
            'BookController' => 'BookController',
             "addFrorm"=>$form->createView()
        ]);
    }
   // delete function 
    #[Route('/deleteBook/{id}', name: 'app_deleteBook')]
    public function deleteBook(ManagerRegistry $mr , int $id): Response
    {
       $m= $mr->getManager(); 
        $book = $m->getRepository(Book::class)->find($id); 
        if($book){
            $m->remove($book); 
            $m->flush(); 
        }
        return $this->redirectToRoute('app_getBook'); 

        return $this->render('book/addBook.html.twig', [
            'BookController' => 'BookController',
        ]);
    }
    

}
