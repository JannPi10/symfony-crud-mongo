<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiInfoController extends AbstractController
{
    /**
     * GET /api
     */
    #[Route('', name: 'info', methods: ['GET'])]
    public function info(): JsonResponse
    {
        return $this->json([
            'api_name' => 'Products CRUD API',
            'version' => '1.0.0',
            'description' => 'API REST para gestión de productos con MongoDB',
            'endpoints' => [
                'GET /api' => 'Información de la API',
                'GET /api/products' => 'Obtener todos los productos',
                'GET /api/products/{id}' => 'Obtener producto por ID',
                'POST /api/products' => 'Crear nuevo producto',
                'PUT /api/products/{id}' => 'Actualizar producto completo',
                'PATCH /api/products/{id}' => 'Actualizar producto parcial',
                'DELETE /api/products/{id}' => 'Eliminar producto',
                'GET /api/products/search?q=term' => 'Buscar productos',
            ],
            'example_product' => [
                'name' => 'Producto Ejemplo',
                'description' => 'Descripción del producto ejemplo',
                'price' => 99.99,
                'stock' => 10
            ],
            'status_codes' => [
                200 => 'Éxito',
                201 => 'Creado',
                400 => 'Solicitud incorrecta',
                404 => 'No encontrado',
                422 => 'Error de validación',
                500 => 'Error interno del servidor'
            ]
        ], 200);
    }

    /**
     * GET /api/health
     */
    #[Route('/health', name: 'health', methods: ['GET'])]
    public function health(): JsonResponse
    {
        return $this->json([
            'status' => 'healthy',
            'timestamp' => (new \DateTime())->format('Y-m-d H:i:s'),
            'database' => 'connected',
            'api_version' => '1.0.0'
        ], 200);
    }
}