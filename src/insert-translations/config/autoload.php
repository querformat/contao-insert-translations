<?php

/**
 * Contao Open Source CMS
 *
 * @package   querformat/insert-translations
 * @author    Enrico Schiller
 * @license   GPL-3.0+
 * @copyright querformat GmbH & Co. KG
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'insertTranslations',
));

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'insertTranslations\insertTranslationsClassFE' => 'system/modules/insert-translations/classes/insertTranslationsClassFE.php',
	// Models
	'insertTranslations\insertTranslationsModel' => 'system/modules/insert-translations/models/insertTranslationsModel.php',
));