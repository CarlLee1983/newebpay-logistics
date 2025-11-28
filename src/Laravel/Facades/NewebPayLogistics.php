<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Laravel\Facades;

use CarlLee\NewebPayLogistics\NewebPayLogistics as Accessor;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \CarlLee\NewebPayLogistics\BaseRequest map()
 * @method static \CarlLee\NewebPayLogistics\BaseRequest createOrder()
 * @method static \CarlLee\NewebPayLogistics\BaseRequest query()
 * @method static \CarlLee\NewebPayLogistics\BaseRequest printOrder()
 * @method static \CarlLee\NewebPayLogistics\Responses\Response send(\CarlLee\NewebPayLogistics\BaseRequest $request)
 *
 * @see \CarlLee\NewebPayLogistics\NewebPayLogistics
 */
class NewebPayLogistics extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Accessor::class;
    }
}
