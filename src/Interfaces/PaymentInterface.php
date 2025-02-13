<?php

namespace Emincmg\PaymentProcessorLaravel\Interfaces;

interface PaymentInterface
{
    public function process();

    public function fail();

    public function success();
}