<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Book;
use App\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;


final class AuthorController extends AbstractController
{
    
#[Route('/getAuthers', name: 'get_author')]
    public function getAuthers(AuthorRepository $author): Response
    {
         $authors= $author->findAll();
        return $this->render('author/getAuthers.html.twig', [
            'controller_name' => 'AuthorController',
            'authors' => $authors]
            );
    }

     #[Route('/addBook', name: 'app_addBook')]
    public function addBook(ManagerRegistry $mr ,Request $request): Response
    {
       $em=$mr->getManager();  //demande d'acces a l'entity manager
        $book=new Book();
        $form=$this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($book); // prepare la requete
            $em->flush(); // execute la requete
            return $this->redirectToRoute('get_books');
    }
    return $this->render('author/addBook.html.twig', [
    'formBook' => $form->createView()
]);
   

}
 #[Route('/getBooks', name: 'get_books')] 
    public function getBooks(BookRepository $b): Response
    {
         $books= $b->findAll(); 
        return $this->render('author/getbooks.html.twig', [
            'controller_name' => 'AuthorController',
            'books' => $books]
            );
    }

    #[Route('/editBook/{id}', name: 'app_editBook')]
public function editBook(ManagerRegistry $mr, Request $request, int $id): Response
{
    $em = $mr->getManager();
    $book = $em->getRepository(Book::class)->find($id);

    if (!$book) {
        throw $this->createNotFoundException("Le livre avec l'id $id n'existe pas !");
    }

    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush(); 
        $this->addFlash('success', 'Livre modifié avec succès !');
        return $this->redirectToRoute('get_books');
    }

    return $this->render('author/editBook.html.twig', [
        'formBook' => $form->createView(),
        'book' => $book,
    ]);
}


    #[Route('/deleteBook/{id}', name: 'app_deleteBook')]
public function deleteBook(ManagerRegistry $mr, int $id): Response
    {
    $em = $mr->getManager(); //demande d'acces a l'entity manager
    $book = $em->getRepository(Book::class)->find($id); // Recherche le livre 

    if (!$book) {
        $this->addFlash('error', 'error erveur !');
    }
   else{
    $em->remove($book);
    $em->flush(); 

    return $this->redirectToRoute('get_books');
   }
    return $this->render('author/getbooks.html.twig', [
            'controller_name' => 'AuthorController']
            );
    }
}
