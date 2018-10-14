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
 * Table tl_insert_translations
 */

$GLOBALS['TL_DCA']['tl_insert_translations'] = array
(

    // Config
    'config' => array
    (
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'alias' => 'index',
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode' => 0,
            'panelLayout' => 'search,limit;filter;',
            'flag' => 12,
            'fields' => array('alias'),
        ),
        'label' => array
        (
            'fields' => array('alias'),
            'format' => '{{trans::%s}}',
            'label_callback' => array('tl_insert_translations', 'setLabels'),
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            ),
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_insert_translations']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif'
            ),
            'copy' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_insert_translations']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif'
            ),
            'delete' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_insert_translations']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_insert_translations']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif'
            )
        )
    ),

    // Select
    'select' => array
    (
        'buttons_callback' => array()
    ),

    // Edit
    'edit' => array
    (
        'buttons_callback' => array()
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__' => array(''),
        'default' => 'alias,fallback,tag,trans;'
    ),

    // Subpalettes
    'subpalettes' => array
    (
        '' => ''
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'alias' => array
        (
            'label' => array('Alias', 'Der Alias ersetzt im Insert-Tag "{{trans::____}}" die Unterstriche'),
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => array(
                'unique' => true,
                'mandatory' => true,
                'maxlength' => 128
            ),
            'save_callback' => array
            (
                array('tl_insert_translations', 'generateAlias')
            ),
            'sql' => "varchar(128) BINARY NOT NULL default ''"
        ),
        'fallback' => array
        (
            'label' => array('Fallback','Falls eine Sprache keine Übersetzung hat wird der Fallback genommen'),
            'exclude' => true,
            'search' => true,
            'inputType' => 'textarea',
            'eval' => array(
                'allowHtml' => true
            ),
            'sql' => "mediumtext NOT NULL"
        ),
        'trans' => array(
            'label' => array('Übersetzung'),
            'inputType' => 'metaWizard',
            'eval' => array(
                'allowHtml' => true,
                'metaFields' => array(
                    'translation' => '',
                )
            ),
            'search' => true,
            'sql' => "blob NULL"
        ),
        'tag' => array
        (
            'label' => array('Gruppenname (Tag)', 'Bezeichnung um verschiedene Übersetzungen zu einer Gruppe zusammen zu fassen (Abfrage über insertTranslationsModel::findByTag($strTag))'),
            'filter' => true,
            'inputType' => 'text',
            'eval' => array(
                'rgxp'=>'alias',
                'maxlength' => 255,
            ),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
    )
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 */
class tl_insert_translations extends Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Auto-generate an unique alias if necessary
     *
     * @param mixed $varValue
     * @param DataContainer $dc
     *
     * @return string
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $aliasString = \StringUtil::generateAlias($varValue);
        $objAlias = $this->Database->prepare("SELECT id FROM tl_insert_translations WHERE alias=?")
            ->execute($aliasString);

        if ($objAlias->numRows > 1) {
            $aliasString .= '-' . $dc->id;
        }
        return $aliasString;
    }


    /**
     * @param $row
     * @param $label
     * @return string
     */
    public function setLabels($row, $label)
    {
        $returnString = '';
        $arrTrans = unserialize($row['trans']);
        $hasTranslations = false;
        $hasFallback = false;
        $translationsArr = [];

        // Only show the root page languages (see #7112, #7667)
        $languages = $this->getLanguages();
        $objRootLangs = $this->Database->query("SELECT REPLACE(language, '-', '_') AS language FROM tl_page WHERE type='root'");
        $existing = $objRootLangs->fetchEach('language');

        // Also add the existing keys (see #878)
        if (!empty($this->varValue)) {
            $existing = array_unique(array_merge($existing, array_keys($this->varValue)));
        }
        $languages = array_intersect_key($languages, array_flip($existing));

        // check all available page languages for translation in the insert tag
        foreach ($languages as $k => $v) {

            // reset status
            $status= '';

            // language key does not exist
            if (!array_key_exists($k, $arrTrans)) {
                // set language flag color to
                // white
            }
            // no translation provided
            else if(empty($arrTrans[$k]['translation'])) {
                // set language flag color to
                // orange
                $status= ' class="corrupt"';
            }else {
                // set language flag color to
                // green
                $status = ' class="active"';
                $hasTranslations = true;
                $translationsArr[] = $arrTrans[$k]['translation'];
            }
            $returnString .= '<div' . $status. '>' . str_replace('_','-',$k) . '</div>';
        }
        // if hasTranslations true
            // try to get a german translation
            // else get the first of the array of translations
        // if hasTranslations false try fallback
            // else error
        if($hasTranslations){
            if(empty($translation = $arrTrans['de']['translation']))
                $translation = $translationsArr[0];
        }else{
            if(empty($translation = $row['fallback']))
                $translation = false;
            else
                $hasFallback = true;
        }

        // return
        return '<div class="langconfig-item'.(!$translation?' all-corrupt':'').($hasFallback?' all-white':'').'"><div class="langconfig-tag"><span>' . ($translation?:'Keine Übersetzungen vorhanden!') . '</span><sub>' . $label . '</sub></div><div class="langconfig-flags">' . $returnString . '</div></div>';
    }
}