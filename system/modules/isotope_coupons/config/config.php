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


/**
 * Backend modules
 */
array_insert($GLOBALS['BE_MOD']['isotope'], 2, [
    'iso_coupons' => [
        'tables' => ['tl_iso_coupon'],
        'icon' => 'system/modules/isotope_coupons/assets/icon.png',
        'check' => ['Isotope\Backend\Coupon\Callback', 'toggleStatus'],
    ]
]);

/**
 * Products
 */
\Isotope\Model\Product::registerModelType('coupon', 'Isotope\Model\Product\Coupon');
