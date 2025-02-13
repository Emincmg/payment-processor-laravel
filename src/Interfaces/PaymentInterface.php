<?php

namespace Emincmg\PaymentProcessorLaravel\Interfaces;

interface PaymentInterface
{
    function process();

    function fail();

    function success();
}