<?php

namespace App\Controller\Api;

use App\Document\Product;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/products', name: 'api_product_')]
class ProductApiController extends AbstractController
{
    public function __construct(
        private DocumentManager $dm,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    /**
     * GET /products
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $products = $this->dm->getRepository(Product::class)->findAll();

        return $this->json([
            'status' => 'success',
            'message' => 'Productos obtenidos correctamente',
            'data' => $products,
            'total' => count($products)
        ], 200);
    }

    /**
     * GET /products/{id}
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $product = $this->dm->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json([
                'status' => 'error',
                'message' => 'Producto no encontrado',
                'data' => null
            ], 404);
        }

        return $this->json([
            'status' => 'success',
            'message' => 'Producto encontrado',
            'data' => $product
        ], 200);
    }

    /**
     * POST /products
     */
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json([
                'status' => 'error',
                'message' => 'JSON inválido',
                'data' => null
            ], 400);
        }

        $product = new Product();

        if (isset($data['name'])) $product->setName($data['name']);
        if (isset($data['description'])) $product->setDescription($data['description']);
        if (isset($data['price'])) $product->setPrice((float) $data['price']);
        if (isset($data['stock'])) $product->setStock((int) $data['stock']);

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json([
                'status' => 'error',
                'message' => 'Datos de validación incorrectos',
                'errors' => $errorMessages
            ], 422);
        }

        $this->dm->persist($product);
        $this->dm->flush();

        return $this->json([
            'status' => 'success',
            'message' => 'Producto creado exitosamente',
            'data' => $product
        ], 201);
    }

    /**
     * PUT /products/{id}
     */
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $product = $this->dm->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json([
                'status' => 'error',
                'message' => 'Producto no encontrado',
                'data' => null
            ], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json([
                'status' => 'error',
                'message' => 'JSON inválido',
                'data' => null
            ], 400);
        }

        if (isset($data['name'])) $product->setName($data['name']);
        if (isset($data['description'])) $product->setDescription($data['description']);
        if (isset($data['price'])) $product->setPrice((float) $data['price']);
        if (isset($data['stock'])) $product->setStock((int) $data['stock']);

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json([
                'status' => 'error',
                'message' => 'Datos de validación incorrectos',
                'errors' => $errorMessages
            ], 422);
        }

        $this->dm->flush();

        return $this->json([
            'status' => 'success',
            'message' => 'Producto actualizado exitosamente',
            'data' => $product
        ], 200);
    }

    /**
     * PATCH /products/{id}
     */
    #[Route('/{id}', name: 'partial_update', methods: ['PATCH'])]
    public function partialUpdate(string $id, Request $request): JsonResponse
    {
        $product = $this->dm->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json([
                'status' => 'error',
                'message' => 'Producto no encontrado',
                'data' => null
            ], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json([
                'status' => 'error',
                'message' => 'JSON inválido',
                'data' => null
            ], 400);
        }

        if (array_key_exists('name', $data)) {
            $product->setName($data['name']);
        }
        if (array_key_exists('description', $data)) {
            $product->setDescription($data['description']);
        }
        if (array_key_exists('price', $data)) {
            $product->setPrice((float) $data['price']);
        }
        if (array_key_exists('stock', $data)) {
            $product->setStock((int) $data['stock']);
        }

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json([
                'status' => 'error',
                'message' => 'Datos de validación incorrectos',
                'errors' => $errorMessages
            ], 422);
        }

        $this->dm->flush();

        return $this->json([
            'status' => 'success',
            'message' => 'Producto actualizado parcialmente',
            'data' => $product
        ], 200);
    }

    /**
     * DELETE /products/{id}
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $product = $this->dm->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json([
                'status' => 'error',
                'message' => 'Producto no encontrado',
                'data' => null
            ], 404);
        }

        $productName = $product->getName();
        $this->dm->remove($product);
        $this->dm->flush();

        return $this->json([
            'status' => 'success',
            'message' => "Producto '$productName' eliminado exitosamente",
            'data' => null
        ], Response::HTTP_OK);
    }

    /**
     * GET /products/search?q=term
     * @throws MongoDBException
     */
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('q', '');

        if (empty($searchTerm)) {
            return $this->json([
                'status' => 'error',
                'message' => 'Parámetro de búsqueda "q" es requerido',
                'data' => []
            ], 400);
        }

        $queryBuilder = $this->dm->createQueryBuilder(Product::class);
        $queryBuilder->addOr($queryBuilder->expr()->field('name')->regex(new \MongoDB\BSON\Regex($searchTerm, 'i')))
            ->addOr($queryBuilder->expr()->field('description')->regex(new \MongoDB\BSON\Regex($searchTerm, 'i')));

        $products = $queryBuilder->getQuery()->execute()->toArray();

        return $this->json([
            'status' => 'success',
            'message' => 'Búsqueda completada',
            'data' => array_values($products),
            'total' => count($products),
            'search_term' => $searchTerm
        ], 200);
    }
}