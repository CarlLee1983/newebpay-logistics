<?php

namespace CarlLee\NewebPayLogistics\Parameter;

enum LgsType: string
{
    /**
     * B2C (Bulk to Store)
     */
    case B2C = 'B2C';

    /**
     * C2C (Store to Store)
     */
    case C2C = 'C2C';
}
