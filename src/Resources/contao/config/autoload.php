<?php

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'Foc\ContaoPrivacyBundle\Modules\Registration'      => 'system/modules/contao-privacy/modules/Registration.php',
	'Foc\ContaoPrivacyBundle\Modules\Subscribe'         => 'system/modules/contao-privacy/modules/Subscribe.php',
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'form_checkbox_foc'        => 'system/modules/contao-privacy/templates',
	'mod_comment_form'         => 'system/modules/contao-privacy/templates',
	'nl_default_foc'           => 'system/modules/contao-privacy/templates',
));
