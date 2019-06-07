<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\Produit;
use App\Repository\LigneCommandeRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VenteController extends AbstractController
{
    /**
     * @var LigneCommandeRepository
     */
    private $repository;

    public function __construct(LigneCommandeRepository $repository)
    {

        $this->repository = $repository;
    }

    /**
     * @Route("/create/client",name="client_new")
     */
    public function insertClient(Request $request)
    {
        $client=new Client();
        $commande=new Commande();

        $form=$this->createFormBuilder($client)
            ->add('nomComplet')
            ->add('contact')
            ->add('adresse')
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            $idclient=$client->getId();

            $commande->setClient($client)
                ->setDateAchat(new \DateTime());
            $em->persist($commande);
            $em->flush();

            $commandes=$commande->getId();
            return $this->redirectToRoute('lignecommande',['id'=>$commandes]);

        }
        return $this->render('vente/createClient.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @Route("/create/ligne/{id}",name="lignecommande")
     * @return Response
     */
    public function ligneCommande(Commande $commande,Request $request)
    {
        $ligne=new LigneCommande();
        $form=$this->createFormBuilder($ligne)
            ->add('produit',EntityType::class,[
                'class'=>Produit::class,
                'query_builder'  => function(EntityRepository $s){
                    return $s->createQueryBuilder('p')->andWhere('p.emplacement= :st')->setParameter('st',"stock");
                },
                'label'=>"Code barre du produit"
                ])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $ligne->setCommande($commande);
            $em=$this->getDoctrine()->getManager();
            $em->persist($ligne);
            $em->flush();
            $idProduit=$ligne->getProduit()->getId();

            $em->persist($ligne->getProduit()->setEmplacement("vendu"));
            $em->flush();

        }

        $line=$this->repository->findLine($commande->getId());
        return $this->render('vente/ligneCommande.html.twig',[
            'form'=>$form->createView(),
            'commande'=>$commande,
            'line'=>$line
        ]);
    }

    /**
     * @param $id
     * @Route("/update/produit/{id}")
     */
    public function updateProduit(Produit $produit)
    {
        $this->repository->update($produit->getId());
        $em=$this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();

    }

}
