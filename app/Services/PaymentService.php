<?php
namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Services\Gateways\StripeGateway;
use App\Services\Gateways\PaymobGateway;

class PaymentService
{
    public function gateway(string $gateway = 'stripe'): PaymentGatewayInterface
    {
        return match($gateway) {
            'paymob' => new PaymobGateway(),
            default  => new StripeGateway(),
        };
    }
}