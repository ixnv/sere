<?php

namespace App\Models\Contracts;

interface SecretInterface
{
    public function encrypt(string $text, string $password): void;

    public function decrypt(string $password): string;

    public function increaseAttempts(): void;

    public function getAttemptsLeft(): int;

    public function needsDeletion(): bool;
}