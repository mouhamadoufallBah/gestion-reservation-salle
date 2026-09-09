<?php

declare(strict_types=1);

namespace App\DTO;

class PaginationDTO
{
    public function __construct(
        public readonly int $page,
        public readonly int $parPage,
        public readonly int $total,
        public readonly array $queryParams = []
    ) {}

    public function totalPages(): int
    {
        return (int) max(1, (int) ceil($this->total / max(1, $this->parPage)));
    }

    public function hasPrevious(): bool
    {
        return $this->page > 1;
    }

    public function hasNext(): bool
    {
        return $this->page < $this->totalPages();
    }

    public function previousPage(): int
    {
        return max(1, $this->page - 1);
    }

    public function nextPage(): int
    {
        return min($this->totalPages(), $this->page + 1);
    }

    public function debut(): int
    {
        if ($this->total === 0) {
            return 0;
        }

        return ($this->page - 1) * $this->parPage + 1;
    }

    public function fin(): int
    {
        return min($this->page * $this->parPage, $this->total);
    }

    public function url(int $targetPage): string
    {
        $params = $this->queryParams;
        $params['page'] = $targetPage;

        return '?' . http_build_query($params);
    }
}
