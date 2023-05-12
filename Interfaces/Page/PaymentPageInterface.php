<?php

namespace App\Interfaces\Page;

interface PaymentPageInterface
{
    public const PAYMENT_SUCCESS_VIEW = 'payment.success';
    public const PAYMENT_FAIL_VIEW    = 'payment.fail';
    
    public function getSuccessPage();
    public function getFailurePage();
}