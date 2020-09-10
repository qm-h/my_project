<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\DBAL\DBALException;
use Exception;

class ArticleController extends AbstractController
{
     /**
     * @Route("/article", name="article_home")
     */
  public function index(Request $request)
    {
        try {
            
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findAll();
        
        

            $suppression = $request->get('suppression_name');
            $id = $request->get('id');
        
        
       dump($request);

        return $this->render('article/index.html.twig', ['controller_name' => 'ArticleController', 'articles' =>
        $articles,'suppression'=>$suppression,'id'=>$id]);

        

        } catch (DBALException $e) {
            $msg = 'Error 404';
        } 

    } 



     /**
 * @Route("/delete", name="delete")
 */
public function delete(Request $request)
{
   if (!is_null($request)) {
    $entityManager = $this->getDoctrine()->getManager();

  
    $id = $request->request->get('id');
    $article = $entityManager->getRepository(Article::class)->find($id);

    if (!$article) {
        throw $this->createNotFoundException(
            'No article found for id '.$id
        );
    }
    
    $entityManager->remove($article);
    $entityManager->flush();

    return $this->redirectToRoute('article_home', ['suppression_name' =>  $article->getLibelle(), 'id' => $id]);
   }
}
 
    /**
     * @Route("/detail/{id}", name="article_detail")
     */
  public function getFruit($id)
    {

        $article = $this->getDoctrine()->getRepository(Article::class)
        ->find($id);
        
        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }
        
        return $this->render('article/detail.html.twig', ['controller_name' => 'ArticleController', 'articles' =>
        $article->getLibelle(),'id' => $id]);
    }
   
 /**
     * @Route("/fill", name="fill")
     */
 public function new(Request $request)
 {  
     

      $form = $this->createFormBuilder()
        ->setAction($this->generateUrl('fill'))
        ->setMethod('POST')
        ->add('libelle', TextType::class, ['required' => true])
        ->add('categorie', EntityType::class, [
            'class' => Categorie::class,
            'choice_label' => 'nom',
            'choice_value' => 'id',
            ])
        ->add('save', SubmitType::class, ['label' => 'Create Article'])
        ->getForm();
        

        $form->handleRequest($request);

        $article = new Article();
        $msg="";

        if ($form->isSubmitted() && $form->isValid()) {

           
            $article = new Article();
            $article->setLibelle($form->getData()['libelle']);

            $article->setCategorie($form->getData()['categorie']);


            try {
                
                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($article);
                                
                $entityManager->flush();
            } catch (DBALException $e) {

                $msg = 'existe deja';
                
            }  
                            
    

        }

        
     return $this->render('article/new.html.twig' ,['controller_name' => 'ArticleController', 'form' => $form->createView(),'articles' =>
     $article->getLibelle(),'msg' => $msg, 'categorie' => $article->getCategorie()]);
    
 }

 /**
 * @Route("/product/edit/{id}")
 */
public function update($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $article = $entityManager->getRepository(Article::class)->find($id);

    if (!$article) {
        throw $this->createNotFoundException(
            'No product found for id '.$id
        );
    }

    $article->setName('Fraise');
    $entityManager->flush();

    return $this->redirectToRoute('product_show', [
        'id' => $article->getId()
    ]);
}

 
}