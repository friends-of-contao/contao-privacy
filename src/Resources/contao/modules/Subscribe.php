<?php

/**
 * Registration for ContaoPrivacy
 *
 * @copyright   Friends of Contao 2018
 * @author      Christian Barkowsky <https://brkwsky.de>
 * @package     ContaoPrivacy
 * @license     LGPL-3.0+
 * @see         https://github.com/friends-of-contao/contao-privacy
 */

namespace Foc\ContaoPrivacyBundle\Modules;

use Contao\BackendTemplate;
use Contao\ModuleSubscribe;

/**
 * Class ModuleSubscribe
 * @package Foc\ContaoPrivacyBundle\Modules
 */
class Subscribe extends ModuleSubscribe
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'nl_default_foc';

	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['focSubscribe'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->nl_channels = deserialize($this->nl_channels);

		// Return if there are no channels
		if (!is_array($this->nl_channels) || empty($this->nl_channels)) {
			return '';
		}

		return parent::generate();
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{
        parent::compile();

        // DSGVO checkbox
        $this->Template->focPrivacy = $this->focPrivacy;
        $this->Template->focPrivacyLabel = $this->focPrivacyLabel;
        $this->Template->mandatoryField = $GLOBALS['TL_LANG']['MSC']['mandatory'];
	}
}
