<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProduitController extends AbstractController
{
    /**
     * @var ProduitRepository
     */
    private $produitRepository;

    public function __construct(ProduitRepository $produitRepository)
    {
        $this->produitRepository = $produitRepository;
    }

    /**
     * @Route("/produit/conroller", name="produit")
     */
    public function index()
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     * @Route("/create/produit", name="create_produit")
     */
    public function create(Request $request)
    {
        $produit=new Produit();
        $form=$this->createFormBuilder($produit)
            ->add('categorie',EntityType::class,[
                'class'=>Categorie::class,
                'query_builder'=>function(EntityRepository $entityRepository){
                return $entityRepository->createQueryBuilder('c')->orderBy('c.nomCategorie','ASC');
                }
            ])
            ->add('marque',TextType::class,[
                'label'=>'Marque'
            ])
            ->add('description')
            ->add('prix')
            ->add('codeBare',TextType::class,[
                'label'=>'Reference'
            ])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $produit->setDateSaisi(new \DateTime());
            $produit->setEmplacement("stock");
            $em=$this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute("read_produit");
        }

        return $this->render('produit/create.html.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/read/produit",name="read_produit")
     */
    public function read()
    {
        $produit=$this->produitRepository->findProduit();
        return $this->render('produit/read.html.twig',[
            'prod'=>$produit
        ]);
    }

    /**
     * @Route("read/produit/{id}",name="show_produit")
     * @param Produit $produit
     * @return Response
     */
    public function showProduit(Produit $produit)
    {
        return $this->render('produit/show.html.twig',[
            'produit'=>$produit
        ]);
    }


    /**
     * @param Produit $produit
     * @param Request $request
     * @return Response
     * @throws \Exception
     * @Route("/update/produit/{id}", name="update_produit")
     */
    public function update(Produit $produit,Request $request)
    {
        $form=$this->createFormBuilder($produit)
            ->add('categorie',EntityType::class,[
                'class'=>Categorie::class,
                'query_builder'=>function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('c')->orderBy('c.nomCategorie','ASC');
                }
            ])
            ->add('marque',TextType::class,[
                'label'=>'Marque'
            ])
            ->add('description')
            ->add('prix')
            ->add('codeBare',TextType::class,[
                'label'=>'Reference'
            ])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $produit->setDateSaisi(new \DateTime());
            $produit->setEmplacement("stock");
            $em=$this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('stock',['id'=>$produit->getCategorie()->getId(),'cate'=>$produit->getCategorie()->getNomCategorie()]);
        }

        return $this->render('produit/update.html.twig',[
            'form'=>$form->createView()
        ]);

    }

}
