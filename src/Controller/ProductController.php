<?php

namespace App\Controller;

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
    // /**
    //  * @Route("/", name="product_index", methods={"GET"})
    //  */
    // public function index(ProductRepository $productRepository): Response
    // {
    //     return $this->render('product/index.html.twig', [
    //         'products' => $productRepository->findAll(),
    //     ]);
    // }

    /**
     * @Route("/{id}", name="product_index", methods={"GET"})
     */
    public function index(Product $product): Response
    {
        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/{id}/addToBasket", name = "add_to_basket", methods={"POST"})
     */
    public function addToBasket(Request $request, Product $product, TranslatorInterface $t): Response
    {
        if ($this->getUser()){
            $basket = $this->getUser()->getBasket();
            $basketContent = new BasketContent();
            $basketContent-> setProduct($product);
            $basketContent->setBasket($basket);
            $quantity = $basketContent->getQuantity() +1 ;
            $basketContent->setQuantity($quantity);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($basketContent);
            $entityManager->flush();
            $this->addFlash('success', $t->trans('product.success_add_to_basket'));
        }
        else{
            $this->addFlash('failed', $t->trans('product.fail_add_to_basket'));
        }
        return $this->redirectToRoute('product_index', ["id"=>$product->getId()], Response::HTTP_SEE_OTHER);


    }
}
