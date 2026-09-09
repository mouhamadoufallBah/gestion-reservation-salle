<?php

declare(strict_types=1);

namespace Tests\Unit\DTO;

use App\DTO\PaginationDTO;
use PHPUnit\Framework\TestCase;

class PaginationDTOTest extends TestCase
{
    public function testPaginationCalculations(): void
    {
        $pagination = new PaginationDTO(
            page: 2,
            parPage: 5,
            total: 23,
            queryParams: ['q' => 'informatique']
        );

        $this->assertSame(2, $pagination->page);
        $this->assertSame(5, $pagination->parPage);
        $this->assertSame(23, $pagination->total);
        $this->assertSame(5, $pagination->totalPages());
        $this->assertTrue($pagination->hasPrevious());
        $this->assertTrue($pagination->hasNext());
        $this->assertSame(1, $pagination->previousPage());
        $this->assertSame(3, $pagination->nextPage());
        $this->assertSame(6, $pagination->debut());
        $this->assertSame(10, $pagination->fin());
        $this->assertSame('?q=informatique&page=3', $pagination->url(3));
    }

    public function testPaginationFirstPage(): void
    {
        $pagination = new PaginationDTO(
            page: 1,
            parPage: 10,
            total: 5
        );

        $this->assertSame(1, $pagination->totalPages());
        $this->assertFalse($pagination->hasPrevious());
        $this->assertFalse($pagination->hasNext());
        $this->assertSame(1, $pagination->debut());
        $this->assertSame(5, $pagination->fin());
    }

    public function testPaginationEmpty(): void
    {
        $pagination = new PaginationDTO(
            page: 1,
            parPage: 10,
            total: 0
        );

        $this->assertSame(1, $pagination->totalPages());
        $this->assertSame(0, $pagination->debut());
        $this->assertSame(0, $pagination->fin());
    }
}
