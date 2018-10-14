<?php

/**
 * Contao Open Source CMS
 *
 * @package   querformat/insert-translations
 * @author    Enrico Schiller
 * @license   GPL-3.0+
 * @copyright querformat GmbH & Co. KG
 */


namespace insertTranslations;

class insertTranslationsClassFE extends \Frontend
{
    public function replaceTranslation($strTag)
    {
        if (($arrSplit = explode('::', $strTag))) {
            if ($arrSplit[0] == 'trans') {
                if (!empty($arrSplit[1]) && ($objTrans = \insertTranslationsModel::findOneBy(array("alias=?"), array($arrSplit[1])))) {

                    $language = str_replace('-','_',$GLOBALS['TL_LANGUAGE']);
                    $translations = unserialize($objTrans->trans);
                    if (!empty($translation = $translations[$language]['translation']))
                        return html_entity_decode(\Controller::replaceInsertTags($translation));
                    else {
                        if (!empty($translation = $objTrans->fallback))
                            return html_entity_decode(\Controller::replaceInsertTags($translation));
                        else
                            return $arrSplit[1] . '-missing-translation[' . $language . ']';
                    }
                }
            }
        }
        return false;
    }
}