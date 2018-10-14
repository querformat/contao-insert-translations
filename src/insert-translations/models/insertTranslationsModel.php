<?php

/**
 * Contao Open Source CMS
 *
 * @package   querformat/insert-translations
 * @author    Enrico Schiller
 * @license   GPL-3.0+
 * @copyright querformat GmbH & Co. KG
 *
 * @method static findById($id, $opt = array())
 * @method static findByPk($id, $opt = array())
 * @method static findByIdOrAlias($val, $opt = array())
 * @method static findOneBy($col, $val, $opt = array())
 * @method static findOneByPid($val, $opt = array())
 * @method static findOneBySorting($val, $opt = array())
 * @method static findOneByTstamp($val, $opt = array())
 * @method static findOneByTitle($val, $opt = array())
 * @method static findOneByAlias($val, $opt = array())
 * @method static findOneByAuthor($val, $opt = array())
 * @method static findOneByInColumn($val, $opt = array())
 * @method static findOneByKeywords($val, $opt = array())
 * @method static findOneByShowTeaser($val, $opt = array())
 * @method static findOneByTeaserCssID($val, $opt = array())
 * @method static findOneByTeaser($val, $opt = array())
 * @method static findOneByPrintable($val, $opt = array())
 * @method static findOneByCustomTpl($val, $opt = array())
 * @method static findOneByProtected($val, $opt = array())
 * @method static findOneByGroups($val, $opt = array())
 * @method static findOneByGuests($val, $opt = array())
 * @method static findOneByCssID($val, $opt = array())
 * @method static findOneBySpace($val, $opt = array())
 * @method static findOneByPublished($val, $opt = array())
 * @method static findOneByStart($val, $opt = array())
 * @method static findOneByStop($val, $opt = array())
 * @method static findByPid($val, $opt = array())
 * @method static findBySorting($val, $opt = array())
 * @method static findByTstamp($val, $opt = array())
 * @method static findByTitle($val, $opt = array())
 * @method static findByAlias($val, $opt = array())
 * @method static findByAuthor($val, $opt = array())
 * @method static findByInColumn($val, $opt = array())
 * @method static findByKeywords($val, $opt = array())
 * @method static findByShowTeaser($val, $opt = array())
 * @method static findByTeaserCssID($val, $opt = array())
 * @method static findByTeaser($val, $opt = array())
 * @method static findByPrintable($val, $opt = array())
 * @method static findByCustomTpl($val, $opt = array())
 * @method static findByProtected($val, $opt = array())
 * @method static findByGroups($val, $opt = array())
 * @method static findByGuests($val, $opt = array())
 * @method static findByCssID($val, $opt = array())
 * @method static findBySpace($val, $opt = array())
 * @method static findByPublished($val, $opt = array())
 * @method static findByStart($val, $opt = array())
 * @method static findByStop($val, $opt = array())
 * @method static findMultipleByIds($var)
 * @method static findBy($col, $val, $opt = array())
 * @method static findAll($opt = array())
 *
 * @method static integer countById($id, $opt = array())
 * @method static integer countByPid($val, $opt = array())
 * @method static integer countBySorting($val, $opt = array())
 * @method static integer countByTstamp($val, $opt = array())
 * @method static integer countByTitle($val, $opt = array())
 * @method static integer countByAlias($val, $opt = array())
 * @method static integer countByAuthor($val, $opt = array())
 * @method static integer countByInColumn($val, $opt = array())
 * @method static integer countByKeywords($val, $opt = array())
 * @method static integer countByShowTeaser($val, $opt = array())
 * @method static integer countByTeaserCssID($val, $opt = array())
 * @method static integer countByTeaser($val, $opt = array())
 * @method static integer countByPrintable($val, $opt = array())
 * @method static integer countByCustomTpl($val, $opt = array())
 * @method static integer countByProtected($val, $opt = array())
 * @method static integer countByGroups($val, $opt = array())
 * @method static integer countByGuests($val, $opt = array())
 * @method static integer countByCssID($val, $opt = array())
 * @method static integer countBySpace($val, $opt = array())
 * @method static integer countByPublished($val, $opt = array())
 * @method static integer countByStart($val, $opt = array())
 * @method static integer countByStop($val, $opt = array())
 */
/**
 * Namespace
 */
namespace insertTranslations;

/**
 * Class insertTranslationsModel
 */
class insertTranslationsModel extends \Model
{

    /**
     * Name of the table
     * @var string
     */
    protected static $strTable = 'tl_insert_translations';


    /**
     * @param $strTag
     * @param array $options
     * @return array
     */
    public static function findByTag($strTag, $options = array('lang' => '', 'sort' => ''))
    {
        $t = static::$strTable;
        $return = [];

        // take given sorting if possible
        $sorting = ($options['sort'] ?$options['sort']: '');

        // take given lang else global tl_language as default
        $strLang = (!empty($options['lang']) ?$options['lang']: $GLOBALS['TL_LANGUAGE']);
        $strLang = str_replace('-','_',$strLang);

        // try finding items with tag
        if (!($items = static::findBy(array("$t.tag=?"), array($strTag))))
            return $return[] = 'no translations with tag "' . $strTag . '" found';

        // iterate items
        foreach ($items as $item) {

            // set array key to trans-ID
            $translations = unserialize($item->trans);

            // try to find translation for language
            if (empty($return['trans-' . $item->id] = $translations[$strLang]['translation']))
                // try to find fallback
                if (empty($return['trans-' . $item->id] = $item->fallback))
                    // set to insert tag alias
                    $return['trans-' . $item->id] = $item->alias;
        }

        // sort return by value asc
        if ($sorting == 'asc')
            asort($return);

        // sort return by value desc
        if ($sorting == 'desc')
            arsort($return);

        return $return;
    }
}
