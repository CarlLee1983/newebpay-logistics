<?php

namespace CarlLee\NewebPayLogistics\Parameter;

enum TradeType: string
{
    /**
     * Payment on Delivery
     */
    case PAYMENT = '1';

    /**
     * Non-Payment on Delivery
     */
    case NON_PAYMENT = '3';
}
