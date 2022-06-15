<?php

namespace App\Controller;
use App\Entity\Sortie;
use App\Entity\Produit;
use App\Form\SortieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/Sortie/Liste", name="sortie_liste")
     */
 
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $s = new Sortie();
        $form = $this->createForm(SortieType::class,$s, 
                                  array('action' => $this->generateUrl('sortie_add')));

        $data['form'] = $form->createView();
        $data['sorties'] = $em->getRepository(Sortie::class)->findAll();

        return $this->render('Sortie/liste.html.twig',$data);
    }

     /**
     * @Route("/Sortie/add", name="sortie_add")
     */
    public function add(Request $request)
    {

        $s = new Sortie();
        $form = $this->createForm(SortieType::class, $s);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $s = $form->getData();
            $s->setUser($this->getUser());
            $p = $em->getRepository(Produit::class)->find($s->getProduit()->getId());
            $qteS = $s->getQteS();

            if ($p->getQtStock() < $s->getQteS())
            {
                $s = new Sortie();
                $form = $this->createForm(SortieType::class,$s, 
                                  array('action' => $this->generateUrl('sortie_add')));
                $data['form'] = $form->createView();
                $data['sorties'] = $em->getRepository(Sortie::class)->findAll();
                $data['error_message'] = 'Le stock disponible est inferieur a '.$qteS; 
                 
                return $this->render('Sortie/liste.html.twig',$data);
            }

            else 
            {
                //Mise a jour du Produit (Sortie Possible)
                $stock = $p->getQtStock() - $s->getQteS() ;
                $p->setQtStock($stock);
                $em->persist($s);
                $em->flush();
            }
        } 

        return $this->redirectToRoute('sortie_liste');
    }
}
