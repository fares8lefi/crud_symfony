<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Book;
use App\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;


final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
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
    }
    return $this->render('author/addBook.html.twig', [
    'formBook' => $form->createView()
]);

}
}
