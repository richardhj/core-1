<?php

/**
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2009-2016 terminal42 gmbh & Isotope eCommerce Workgroup
 *
 * @link       https://isotopeecommerce.org
 * @license    https://opensource.org/licenses/lgpl-3.0.html
 */

namespace Isotope;

/**
 * Class Isotope\Template
 *
 * Provide methods to handle Isotope templates.
 * @copyright  Isotope eCommerce Workgroup 2009-2012
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @author     Fred Bliss <fred.bliss@intelligentspark.com>
 */
class Template extends \FrontendTemplate
{

    /**
     * Check the Isotope config directory for a particular template
     *
     * @param string $strTemplate
     * @param string $strFormat
     *
     * @return string
     */
    public static function getTemplate($strTemplate, $strFormat = 'html5')
    {
        $arrAllowed = trimsplit(',', $GLOBALS['TL_CONFIG']['templateFiles']);

        if (is_array($GLOBALS['TL_CONFIG']['templateFiles']) && !in_array($strFormat, $arrAllowed)) {
            throw new \InvalidArgumentException("Invalid output format $strFormat");
        }

        $strKey      = $strTemplate . '.' . $strFormat;
        $strPath     = TL_ROOT . '/templates';
        $strTemplate = basename($strTemplate);

        // Check the templates subfolder
        $strTemplateGroup = str_replace(array('../', 'templates/'), '', Isotope::getConfig()->templateGroup);

        if ($strTemplateGroup != '') {
            $strFile = $strPath . '/' . $strTemplateGroup . '/' . $strKey;

            if (file_exists($strFile)) {
                return $strFile;
            }

            if (file_exists($strFile)) {
                return $strFile;
            }
        }

        return parent::getTemplate($strTemplate, $strFormat);
    }
}
