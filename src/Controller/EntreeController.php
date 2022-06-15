<?php

namespace App\Controller;
use App\Entity\Entree;
use App\Entity\Produit;
use App\Form\EntreeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntreeController extends AbstractController
{
    /**
     * @Route("/Entree/Liste", name="entree_liste")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $e = new Entree();
        $form = $this->createForm(EntreeType::class,$e, 
                                  array('action' => $this->generateUrl('entree_add')));

        $data['form'] = $form->createView();
        $data['entrees'] = $em->getRepository(Entree::class)->findAll();

        return $this->render('Entree/liste.html.twig',$data);
    }

     /**
     * @Route("/Entree/add", name="entree_add")
     */
    public function add(Request $request)
    {

        $e = new Entree();
        $p = new Produit();
        $form = $this->createForm(EntreeType::class, $e);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $e = $form->getData();
            $e->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($e);
            $em->flush();

            //Mise a jour du Produit
            $p = $em->getRepository(Produit::class)->find($e->getProduit()->getId());
            $stock = $p->getQtStock() + $e->getQtE() ;
            $p->setQtStock($stock);
            $em->flush();
        } 

        return $this->redirectToRoute('entree_liste');
    }


}
