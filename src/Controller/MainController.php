<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\CrudType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main", methods={"GET","POST"})
     */
    public function index(ArticleRepository $repo): Response
    {
        $data= $repo-> findAll();
        return $this->render('main/affiche.html.twig', [
            'controller_name' => 'MainController',
            'data'=>$data,
        ]);
    }
    /**
     * @Route("/create", name="create", methods={"GET","POST"})
     */
    public function create(Request $request): Response
    {
        $crud = new Article();#article correspond au nom du fichier en entity#
        $form=$this->createForm( CrudType :: class , $crud); #creation de formulaire avec le type d'entité#
        $form -> handleRequest($request); #gestion des données entrées dans le formulaire#
        if ($form->isSubmitted() && $form->isValid()){

        $sendDatabase = $this->getDoctrine()->getManager();
        $sendDatabase->persist($crud); #ajout de l'objet crud à la base de donnée#
        $sendDatabase->flush();  
        
        $this->addFlash('notice', 'Soumission Réussi !!');
        #notice va creer un composant qui va contenir plusieurs valeurs (3)#
        return $this->redirectToRoute("app_main");
    }
        #permet retour a la page d'accueil directement suite a la soumission#
        #rendu de la page de creation#
        return $this->render('main/createForm.html.twig', [
            'controller_name' => 'MainController',
            'form' => $form->createView(),    
            #le 'form' est rappelé dans le twig createform : celui entre paranthese (form) #  
        ]);
    }
    


    /**
     * @Route("/update/{id}", name="update", methods={"GET","POST"})
     */
    public function update(Request $request, $id): Response
    {
        $crud = $this->getDoctrine()->getRepository( Article :: class )->find($id);  
        #recupère les informations de l'objet id de la table article#
        $form=$this->createForm( CrudType :: class , $crud); #creation de formulaire avec le type d'entité#
        $form -> handleRequest($request); #gestion des données entrées dans le formulaire#
        if ($form->isSubmitted() && $form->isValid()){
        $sendDatabase = $this->getDoctrine()->getManager();
        $sendDatabase->persist($crud); #ajout de l'objet crud à la base de donnée#
        $sendDatabase->flush();  
        
        $this->addFlash('notice', 'Soumission Réussi !!');
        #notice va creer un composant qui va contenir plusieurs valeurs (3)#
        return $this->redirectToRoute("app_main");
    }
        #permet retour a la page d'accueil directement suite a la soumission#
        #rendu de la page de creation#
        return $this->render('main/update.html.twig', [
            'controller_name' => 'MainController',
            'form' => $form->createView(),    
            #le 'form' est rappelé dans le twig createform : celui entre paranthese (form) #  
        ]);
    }
    
    /**
     * @Route("/delete/{id}", name="delete", methods={"GET","POST"})
     */
    public function remove($id): Response
    {
        $crud = $this->getDoctrine()->getRepository( Article :: class )->find($id);  
        #recupère les informations de l'objet id de la table article#
        $sendDatabase = $this->getDoctrine()->getManager();
        $sendDatabase->remove($crud); #suppression de l'objet crud à la base de donnée#
        $sendDatabase->flush();  
        
        $this->addFlash('notice', 'Soumission Réussi !!');
        #notice va creer un composant qui va contenir plusieurs valeurs (3)#
        return $this->redirectToRoute("app_main");
    }
    }