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
$GLOBALS['TL_DCA']['tl_iso_producttype']['palettes']['coupon'] = '{name_legend},name,class,fallback;{description_legend:hide},description;{prices_legend:hide},prices;{template_legend},list_template,reader_template,list_gallery,reader_gallery;{attributes_legend},attributes;{coupon_legend},coupon_info,coupon_prefix,coupon_numbers,coupon_alphabet,coupon_chars';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_iso_producttype']['fields']['coupon_info']['input_field_callback'] = function(\DataContainer $dc) {
    if ($dc->activeRecord->coupon_chars < 1
        || $dc->activeRecord->coupon_chars > strlen($dc->activeRecord->coupon_alphabet)
    ) {
        return '';
    }

    $code = '';

    if ($dc->activeRecord->coupon_prefix) {
        $code = Controller::replaceInsertTags($dc->activeRecord->coupon_prefix, false) . '-';
    }

    $code .= str_pad('1', $dc->activeRecord->coupon_numbers, '0', STR_PAD_LEFT) . '-';

    $code .= \Isotope\Model\Product\Coupon::generateRandomCode(
        str_split($dc->activeRecord->coupon_alphabet),
        $dc->activeRecord->coupon_chars
    );

    return '<p class="tl_info" style="margin-top:10px">' . sprintf($GLOBALS['TL_LANG']['tl_iso_producttype']['coupon_info'], $code) . '</p>';
};

$GLOBALS['TL_DCA']['tl_iso_producttype']['fields']['coupon_prefix'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_iso_producttype']['coupon_prefix'],
    'exclude'       => true,
    'inputType'     => 'text',
    'eval'          => ['maxlength' => 64, 'tl_class' => 'w50'],
    'sql'           => "varchar(64) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_iso_producttype']['fields']['coupon_numbers'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_iso_producttype']['coupon_numbers'],
    'exclude'       => true,
    'default'       => 4,
    'inputType'     => 'select',
    'options'       => [1,2,3,4,5,6,7,8,9],
    'eval'          => ['mandatory' => true, 'tl_class' => 'w50'],
    'sql'           => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_iso_producttype']['fields']['coupon_alphabet'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_iso_producttype']['coupon_alphabet'],
    'exclude'       => true,
    'default'       => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
    'inputType'     => 'text',
    'eval'          => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
    'sql'           => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_iso_producttype']['fields']['coupon_chars'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_iso_producttype']['coupon_chars'],
    'exclude'       => true,
    'default'       => 8,
    'inputType'     => 'text',
    'eval'          => ['mandatory' => true, 'rgxp' => 'prcnt', 'maxlength' => 2, 'tl_class' => 'w50'],
    'sql'           => "varchar(2) NOT NULL default ''",
];
