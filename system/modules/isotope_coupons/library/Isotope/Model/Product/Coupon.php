<?php

/**
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2009-2014 terminal42 gmbh & Isotope eCommerce Workgroup
 *
 * @package    Isotope
 * @link       http://isotopeecommerce.org
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace Isotope\Model\Product;

class Coupon extends Standard
{
    /**
     * 
     *
     * @param array $chars
     * @param int   $length
     *
     * @return string
     */
    public static function generateRandomCode(array $chars, $length)
    {
        return implode(
            '',
            array_intersect_key(
                $chars,
                array_flip(
                    array_rand(
                        $chars,
                        $length
                    )
                )
            )
        );
    }
}
