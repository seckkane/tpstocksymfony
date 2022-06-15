<?php

namespace App\Controller;
use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/Categorie/liste", name="categorie_liste")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $cat = new Categorie();
        $form = $this->createForm(CategorieType::class,$cat, 
                                  array('action' => $this->generateUrl('categorie_add')));

        $data['form'] = $form->createView();
        $data['categories'] = $em->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/liste.html.twig',$data);
    }


    /**
     * @Route("/Categorie/add", name="categorie_add")
     */
    public function add(Request $request)
    {
        $cat = new Categorie();
        $form = $this->createForm(CategorieType::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cat = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($cat);
            $em->flush();
        } 

        return $this->redirectToRoute('categorie_liste');
    }

    


    /**
     * @Route("/Categorie/edit/{id}", name="categorie_edit")
     */
    public function edit($id)
    {
       $em = $this->getDoctrine()->getManager();
       $cat = $em->getRepository(Categorie::class)->find($id);
       $form = $this->createForm(CategorieType::class,$cat, 
                        array('action' => $this->generateUrl('categorie_update', ['id'=>$id])));

        $data['form'] = $form->createView();
        $data['categories'] = $em->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/liste.html.twig',$data);
    }


    /**
     * @Route("/Categorie/update/{id}", name="categorie_update")
     */
    public function update($id, Request $request)
    {
        $cat = new Categorie();
        $form = $this->createForm(CategorieType::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cat = $form->getData();
            $cat->setId($id);

            $em = $this->getDoctrine()->getManager();
            $cat = $em->getRepository(Categorie::class)->find($id);
            $cat->setLibeleCateg($cat->getLibeleCateg());
            $em->flush();
        } 
        return $this->redirectToRoute('categorie_liste');
    }

 
    
}
