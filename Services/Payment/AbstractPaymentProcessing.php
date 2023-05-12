<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Payment;
use App\Models\User;

abstract class AbstractPaymentProcessing
{
    /**
     * URL запроса в платежную систему. Переопределить в них самих
     */
    private const URL = '';
    /**
     * Статус по умолчанию для платежа
     */
    private const STATUS_PAYMENT_PENDING = '';

    abstract protected function generateOrderId();
    abstract protected function createPayment(): Payment;
    abstract protected function sendRequestToProvider(): View;
    abstract protected function findOrCreateUser(Request $request) : User;
    abstract protected function generateUserPassword(): string;
    abstract protected function generateEmailToken(): string;
    abstract protected function sendVerifyMailToUser(): void;
    abstract protected function sendNewMailToUser(): void;
    abstract public function getPaymentMethodsPage(): View;
}