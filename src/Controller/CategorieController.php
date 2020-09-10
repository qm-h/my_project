<?php

namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repository->findAll();

       
        return $this->render('categorie/index.html.twig', ['controller_name' => 'CategorieController', 'categories' =>
        $categories]);
        
    }

     /**
     * @Route("/categorie/new", name="new")
     */
 public function newCategorie(Request $request)
 {  
     

      $form = $this->createFormBuilder()
        ->setAction($this->generateUrl('new'))
        ->setMethod('POST')
        ->add('libelle', TextType::class, ['required' => true])
        ->add('save', SubmitType::class, ['label' => 'Create Categorie'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $categorie = new Categorie();
            $categorie->setLibelle($form->getData()['libelle']);
/*             dump($article);
 */
           $entityManager = $this->getDoctrine()->getManager();   
           // tell Doctrine you want to (eventually) save the Product (no queries yet)
           $entityManager->persist($categorie);
   
           // actually executes the queries (i.e. the INSERT query)
           $entityManager->flush(); 
           
           return $this->render('article/new.html.twig', ['controller_name' => 'CategorieController','form' => $form->createView(),'categories' =>
           $categorie->getLibelle() ]);

        }

        
     return $this->render('article/new.html.twig' ,['controller_name' => 'ArticleController', 'form' => $form->createView()]);
    
 }
}