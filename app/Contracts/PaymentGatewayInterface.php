<?php
namespace App\Contracts;

use App\Models\Donation;

interface PaymentGatewayInterface
{
    public function charge(array $data): array;
    public function createSubscription(array $data): array;
    public function cancelSubscription(string $subscriptionId): bool;
    public function refund(string $transactionId, float $amount): bool;
    public function handleWebhook(array $payload, string $signature): array;
}