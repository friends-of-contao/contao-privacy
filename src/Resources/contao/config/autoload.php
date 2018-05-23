<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    // Modules
	//'Contao\ModuleCalendar'      => 'system/modules/contao-privacy/modules/ModuleCalendar.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'form_checkbox_foc'        => 'system/modules/contao-privacy/templates',

));
