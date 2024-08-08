<?php

declare(strict_types=1);

use OpenApi\Generator;
use OpenApi\Annotations as OA;

/**
 * @OA\Server(
 *     url="http://localhost:8888",
 *     description="Localhost"
 * )
 */
return function () {
    try {
        $openapi = Generator::scan([__DIR__ . '/../src']);
        return $openapi;
    } catch (\Exception $e) {
        error_log('Error generating Swagger documentation: ' . $e->getMessage());
        throw $e;
    }
};
