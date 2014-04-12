<?php
namespace Cena\Eloquent;

class Factory
{
    /**
     * @var EmaEloquent
     */
    protected static $ema;

    /**
     * @return EmaEloquent
     */
    public static function buildEmaEloquent()
    {
        return new EmaEloquent();
    }

    /**
     * @return EmaEloquent
     */
    public static function getEmaEloquent()
    {
        if( !static::$ema ) {
            static::$ema = static::buildEmaEloquent();
        }
        return static::$ema;
    }
}