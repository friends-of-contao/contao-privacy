<?php

/**
 * @copyright   Friends of Contao 2018
 * @author      Oliver Hoff <https://hofff.com/>
 * @package     PrivacyConsentBundle
 * @license     LGPL-3.0-or-later
 * @see         https://github.com/friends-of-contao/contao-privacy
 */

$GLOBALS['TL_DCA']['tl_page'] = call_user_func(function(array $dca) {
    $dca['palettes']['__selector__'][] = 'focPrivacyComments';
    $dca['palettes']['root'] .= ';{focPrivacy_legend},focPrivacyComments';
    $dca['subpalettes']['focPrivacyComments'] = 'focPrivacyCommentsExplanation,focPrivacyCommentsLabel';

    $dca['fields']['focPrivacyComments'] = [
        'label'     => &$GLOBALS['TL_LANG']['tl_page']['focPrivacyComments'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['submitOnChange' => true, 'tl_class' => 'clr'],
        'sql'       => "char(1) NOT NULL default ''",
    ];

    $dca['fields']['focPrivacyCommentsExplanation'] = [
        'label'       => &$GLOBALS['TL_LANG']['tl_page']['focPrivacyCommentsExplanation'],
        'exclude'     => true,
        'inputType'   => 'textarea',
        'eval'        => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true, 'tl_class' => 'clr'],
        'explanation' => 'insertTags',
        'sql'         => "text NULL",
    ];

    $dca['fields']['focPrivacyCommentsLabel'] = [
        'label'     => &$GLOBALS['TL_LANG']['tl_page']['focPrivacyCommentsLabel'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['mandatory' => true, 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'clr long'],
        'sql'       => "varchar(255) NOT NULL default ''",
    ];

    return $dca;
}, $GLOBALS['TL_DCA']['tl_page']);
