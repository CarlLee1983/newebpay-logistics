<?php

namespace CarlLee\NewebPayLogistics\Parameter;

enum ShipType: string
{
    /**
     * 7-11
     */
    case SEVEN_ELEVEN = '1';

    /**
     * 全家
     */
    case FAMILY = '2';

    /**
     * 萊爾富
     */
    case HILIFE = '3';

    /**
     * OK
     */
    case OK = '4';
}
