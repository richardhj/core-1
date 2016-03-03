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
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_iso_producttype']['palettes']['coupon'] = '{name_legend},name,class,fallback;{description_legend:hide},description;{prices_legend:hide},prices;{template_legend},list_template,reader_template,list_gallery,reader_gallery;{attributes_legend},attributes;{coupon_legend},coupon_prefix,coupon_length,coupon_chars';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_iso_producttype']['fields']['coupon_prefix'] = [
    'label'         => $GLOBALS['TL_LANG']['tl_iso_producttype']['coupon_prefix'],
    'exclude'       => true,
    'inputType'     => 'text',
    'eval'          => ['maxlength' => 64, 'tl_class' => 'w50'],
];

$GLOBALS['TL_DCA']['tl_iso_producttype']['fields']['coupon_length'] = [
    'label'         => $GLOBALS['TL_LANG']['tl_iso_producttype']['coupon_length'],
    'exclude'       => true,
    'inputType'     => 'text',
    'eval'          => ['mandatory' => true, 'rgxp' => 'prcnt', 'maxlength' => 2, 'tl_class' => 'w50'],
];

$GLOBALS['TL_DCA']['tl_iso_producttype']['fields']['coupon_chars'] = [
    'label'         => $GLOBALS['TL_LANG']['tl_iso_producttype']['coupon_chars'],
    'exclude'       => true,
    'default'       => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-',
    'inputType'     => 'text',
    'eval'          => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr long'],
];
