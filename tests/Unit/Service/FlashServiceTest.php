<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use App\Service\FlashService;
use App\Service\SessionManager;
use PHPUnit\Framework\TestCase;

class FlashServiceTest extends TestCase
{
    private FlashService $flashService;

    protected function setUp(): void
    {
        parent::setUp();

        $_SESSION = [];

        $session = new SessionManager();

        $this->flashService = new FlashService($session);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];

        parent::tearDown();
    }

    public function testAddAndRetrieveSuccessMessage(): void
    {
        $this->flashService->success('Opération réussie');

        $this->assertTrue($this->flashService->has('success'));
        $this->assertTrue($this->flashService->hasAny());

        $messages = $this->flashService->get('success');

        $this->assertCount(1, $messages);
        $this->assertSame('Opération réussie', $messages[0]);

        $this->assertFalse($this->flashService->has('success'));
    }

    public function testAddAndRetrieveErrorMessage(): void
    {
        $this->flashService->error('Une erreur est survenue');

        $this->assertTrue($this->flashService->has('danger'));

        $messages = $this->flashService->all();

        $this->assertArrayHasKey('danger', $messages);
        $this->assertSame(
            'Une erreur est survenue',
            $messages['danger'][0]
        );

        $this->assertFalse($this->flashService->hasAny());
    }

    public function testMultipleMessageTypes(): void
    {
        $this->flashService->success('Succès 1');
        $this->flashService->warning('Attention');
        $this->flashService->info('Information');

        $all = $this->flashService->all();

        $this->assertArrayHasKey('success', $all);
        $this->assertArrayHasKey('warning', $all);
        $this->assertArrayHasKey('info', $all);

        $this->assertCount(0, $this->flashService->all());
    }
}