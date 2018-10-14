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
 * Register the extension
 */	
$GLOBALS['BE_MOD']['content']['insert_translations'] = array
												   (
													  'tables'       => array('tl_insert_translations'),
													  'stylesheet'   => 'system/modules/insert-translations/assets/css/custom.css',
												   );
/**
 * Register the hooks
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('\insertTranslations\insertTranslationsClassFE', 'replaceTranslation');