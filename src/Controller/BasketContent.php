<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketContent;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Hautelook\AliceBundle\Functional\TestBundle\Entity\Prod;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;




/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{

    /**
     * @Route("/{id}", name="basketContent_index", methods={"GET"})
     */
    public function index(BasketContent $basketContent): Response
    {
        return $this->render('BasketContent/BasketContentPage.html.twig', [
            'product' => $basketContent,
        ]);
    }


    /**
     * @Route("/{id}", name="basketContent_delete", methods={"POST"})
     */
    public function delete(Request $request, BasketContent $basketContent): Response
    {
        if ($this->isCsrfTokenValid('delete'.$basketContent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($basketContent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    }
}
