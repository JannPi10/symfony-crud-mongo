<?php

namespace App\Controller;

use App\Document\Product;
use App\Form\ProductType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

#[Route('/products')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(DocumentManager $dm): Response
    {
        $products = $dm->getRepository(Product::class)->findAll();

        dump($products);
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @throws Throwable
     * @throws MongoDBException
     */
    #[Route('/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DocumentManager $dm): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->persist($product);
            $dm->flush();

            $this->addFlash('success', '¡Producto creado exitosamente!');

            dump($request);
            dump($product);
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws MappingException
     * @throws LockException
     */
    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(string $id, DocumentManager $dm): Response
    {
        $product = $dm->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new NotFoundHttpException('Producto no encontrado');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @throws MappingException
     * @throws Throwable
     * @throws MongoDBException
     * @throws LockException
     */
    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, string $id, DocumentManager $dm): Response
    {
        $product = $dm->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new NotFoundHttpException('Producto no encontrado');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->flush();

            $this->addFlash('success', '¡Producto actualizado exitosamente!');

            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws Throwable
     * @throws MappingException
     * @throws MongoDBException
     * @throws LockException
     */
    #[Route('/{id}/delete', name: 'product_delete', methods: ['POST'])]
    public function delete(Request $request, string $id, DocumentManager $dm): Response
    {
        $product = $dm->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new NotFoundHttpException('Producto no encontrado');
        }

        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $dm->remove($product);
            $dm->flush();

            $this->addFlash('success', '¡Producto eliminado exitosamente!');
        } else {
            $this->addFlash('error', 'Token de seguridad inválido');
        }

        return $this->redirectToRoute('product_index');
    }
}
