<?php

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'Foc\ContaoPrivacyBundle\Modules\Registration'      => 'system/modules/contao-privacy/modules/Registration.php',
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'form_checkbox_foc'        => 'system/modules/contao-privacy/templates',
));
