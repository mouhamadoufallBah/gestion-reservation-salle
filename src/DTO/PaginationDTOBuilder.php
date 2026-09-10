<?php

declare(strict_types=1);

namespace App\DTO;

class PaginationDTOBuilder
{
    private int $page = 1;
    private int $parPage = 10;
    private int $total = 0;
    private array $queryParams = [];

    public function page(int $page): self
    {
        $this->page = max(1, $page);
        return $this;
    }

    public function parPage(int $parPage): self
    {
        $this->parPage = max(1, $parPage);
        return $this;
    }

    public function total(int $total): self
    {
        $this->total = max(0, $total);
        return $this;
    }

    public function queryParams(array $queryParams): self
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    public function build(): PaginationDTO
    {
        return new PaginationDTO(
            page: $this->page,
            parPage: $this->parPage,
            total: $this->total,
            queryParams: $this->queryParams
        );
    }
}