<?php

namespace App\Controller;

interface IReservationController
{
    public function index(): void;

    public function show(string $id): void;

    public function create(): void;

    public function store(): void;

    public function cancel(string $id): void;
}
