<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
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
     * @Route("/stock", name="stock")
     */
    public function index()
    {
        return $this->render('stock/index.html.twig', [
            'controller_name' => 'StockController',
        ]);
    }

    /**
     * @Route("/stock/categorie/{id}/?categorie={cate}", name="stock")
     */
    public function stockPC(Categorie $categorie,Request $request)
    {
        $stockpc=$this->produitRepository->findStock($categorie->getId());
        $detaille=$this->produitRepository->findStockdetaille($categorie->getId());
        dump($request);
        return $this->render('produit/stock.html.twig',[
            'stpc'=>$stockpc,
            'catego'=>$request->get('categorie'),
            'detaille'=>$detaille
        ]);
    }
}
