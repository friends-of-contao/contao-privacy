<?php

/**
 * Privacy Overview in contao-privacy for Contao Open Source CMS
 *
 * @copyright   pdir GmbH 2018 <https://pdir.de>
 * @author      Mathias Arzberger <https://pdir.de>
 * @package     ContaoPrivacy
 * @license     LGPL-3.0+
 * @see         https://github.com/friends-of-contao/contao-privacy
 */

/**
 * Namespace
 */
namespace Foc\ContaoPrivacyBundle\Module;

class PrivacyOverview extends \BackendModule
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_privacy_overview';

    /**
     * Generate the module
     * @throws \Exception
     */
    protected function compile()
    {
        /* @todo add scan actions for external scripts, fonts ... */


        /* generate overview and add widgets via hooks */
        $arrWidgets = array();

        // add own privacy widget :)
        $arrWidgets[] = array(
            'title' => 'friends-of-contao/contao-privacy',
            'content' => 'Es werden keine datenschutzrelevanten Informationen erhoben.',
            'class' => 'green icon'
        );

        // HOOK: add privacy widget
        if (isset($GLOBALS['TL_HOOKS']['addPrivacyWidget']) && is_array($GLOBALS['TL_HOOKS']['addPrivacyWidget']))
        {
            foreach ($GLOBALS['TL_HOOKS']['addPrivacyWidget'] as $callback)
            {
                $this->import($callback[0]);
                $arrWidgets = $this->$callback[0]->$callback[1]($arrWidgets);
            };
        }

        /* sort array */
        usort($arrWidgets, function($a, $b) {
            return $a['title'] <=> $b['title'];
        });

        $strWidgets = '';

        foreach($arrWidgets as $widget){

            $objWidgetTemplate = new \BackendTemplate('be_privacy_widget');
            $objWidgetTemplate->widgetTitle = $widget['title'];
            $objWidgetTemplate->widgetClass =  $widget['class'];
            $objWidgetTemplate->widgetContent = $widget['content'];
            $strWidgets .= $objWidgetTemplate->parse();
        }

        if ($strWidgets)
        {
            $this->Template->widgets = $strWidgets;
        }
    }
}