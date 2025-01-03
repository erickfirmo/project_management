<?php

namespace App\Traits;

trait ApiPagination
{
    public function apiPagination(int $total, int $totalFiltered, int $perPage, int|string $page = 1, array $filters = []) : array
    {
        if (!empty($filters) && $totalFiltered > 0) $total = $totalFiltered;
    
        $lastPage = $perPage ? ceil($total / $perPage) : null;

        $links = $perPage ? range(1, $lastPage) : null;

        $paginationData = [
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'last_page' => $lastPage,
            'links' => $links,
            'previous' => $page > 1 ? $page - 1 : null,
            'next' => $page != $lastPage ? $page + 1 : null
        ];

        return $paginationData;
    }
}
