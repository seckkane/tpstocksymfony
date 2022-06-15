<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/Produit/liste", name="produit_liste")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $p = new Produit();
        $form = $this->createForm(ProduitType::class,$p, 
                                  array('action' => $this->generateUrl('produit_add')));

        $data['form'] = $form->createView();
        $data['produits'] = $em->getRepository(Produit::class)->findAll();

        return $this->render('produit/liste.html.twig',$data);
    }


    /**
     * @Route("/Produit/add", name="produit_add")
     */
    public function add(Request $request)
    {
        $p = new Produit();
        $form = $this->createForm(ProduitType::class, $p);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $p->setUser($this->getUser());
            $p = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
        } 

        return $this->redirectToRoute('produit_liste');
    }
    

    /**
     * @Route("/Produit/delete/{id}", name="produit_delete")
     */
    public function delete($id)
    {

       $em = $this->getDoctrine()->getManager();
       $p = $em->getRepository(Produit::class)->find($id);
       if($p != null) {
          $em->remove($p); 
          $em->flush();
       }
       return $this->redirectToRoute('produit_liste');
    }

    /**
     * @Route("/Produit/edit/{id}", name="produit_edit")
     */
    public function edit($id)
    {
       $em = $this->getDoctrine()->getManager();
       $p = $em->getRepository(Produit::class)->find($id);
       $form = $this->createForm(ProduitType::class,$p, 
                        array('action' => $this->generateUrl('produit_update', ['id'=>$id])));

        $data['form'] = $form->createView();
        $data['produits'] = $em->getRepository(Produit::class)->findAll();

        return $this->render('produit/liste.html.twig',$data);
    }


    /**
     * @Route("/Produit/update/{id}", name="produit_update")
     */
    public function update($id, Request $request)
    {
        $p = new Produit();
        $form = $this->createForm(ProduitType::class, $p);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $p->setUser($this->getUser());
            $p = $form->getData();
            $p->setId($id);

            $em = $this->getDoctrine()->getManager();
            $produit = $em->getRepository(Produit::class)->find($id);
            $produit->setLibelle($p->getLibelle());
            $em->flush();
        } 

        return $this->redirectToRoute('produit_liste');
    }
 
    
}
