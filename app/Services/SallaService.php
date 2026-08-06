<?php

declare(strict_types=1);

namespace App\Services;

use App\Shared\Salla\SallaClient;

final class SallaService
{
    public function __construct(
        private readonly SallaClient $sallaClient,
    ) {}

    /**
     * Get products from Salla API.
     */
    public function getProducts(int $page = 1, int $perPage = 50): array
    {
        return $this->sallaClient->getProducts($page, $perPage);
    }

    /**
     * Get categories from Salla API.
     */
    public function getCategories(int $page = 1, int $perPage = 50): array
    {
        return $this->sallaClient->getCategories($page, $perPage);
    }
}
