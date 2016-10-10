<?php
/*
 * ---------------------------------------------------------------------
 * Class GermanPrepare
 * ---------------------------------------------------------------------
 *
 * @package    german-prepare-tags
 * @version    0.1
 * @author     Maik Haasler
 * @copyright  Copyright (c) 2016, mhaasler.de
 * @license    GNU General Public License
 *
 * default language german; supported: english
 */

namespace mhaasler\GermanPrepare;

use mhaasler\GermanPrepare\Model\GermanPrepareModel;
use mhaasler\GermanPrepare\Utility\GermanPrepareUtility;

class GermanPrepare implements GermanPrepareInterface
{
    /**
     * default mod: use all options
     */
    const MOD_STEM_PHON = 0;
    /**
     * get only phonetic and trim result
     */
    const MOD_ONLY_PHON = 2;
    /**
     * get only stem and trim result
     */
    const MOD_ONLY_STEM = 3;
    /**
     * get only trim result
     */
    const MOD_ONLY_TRIM = 4;
    /**
     * @var integer $mod
     */
    protected $mod = 0;
    /**
     * @var string $modCompare
     */
    protected $modCompare = '';
    /**
     * @var string $text
     */
    protected $text;
    /**
     * @var string lang
     */
    protected $lang = 'de';
    /**
     * @var string $stopWords
     */
    protected $stopWords;
    /**
     * @var GermanPrepareModel[]
     */
    protected $result = array();

    /**
     * GermanPrepareInterface constructor. <br />
     * default mod is soundex, stem and trim
     *
     * @param string $text
     */
    public function __construct($text="", $stopWords=null)
    {
        $this->setStopWords($stopWords);
        $this->setText($text);

        return $this;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return string
     */
    public function getModCompare()
    {
        return $this->modCompare;
    }

    /**
     * @param string $modCompare
     */
    public function setModCompare($modCompare)
    {
        $this->modCompare = $modCompare;

        return $this;
    }

    /**
     * @return string
     */
    public function getStopWords()
    {
        return $this->stopWords;
    }

    /**
     * @param string $stopWords
     */
    public function setStopWords($stopWords)
    {
        $this->stopWords = $stopWords;

        return $this;
    }

    /**
     * @param integer $mod
     * @return $this
     */
    public function setMod($mod)
    {
        $this->mod = $mod;

        return $this;
    }

    /**
     * @param string $text
     * @param bool $setLang
     * @return $this
     */
    public function setText($text, $setLang = true)
    {
        $this->text = $text;

        if($setLang) {
            $this->setLang(GermanPrepareUtility::getTextLanguage($text));
        }

        return $this;
    }

    /**
     * catch tags from 'prepared' text as array
     *
     * @param bool $dedectLang
     * @return GermanPrepareModel[]
     */
    public function getTags($dedectLang = true)
    {
        $this->prepareText($dedectLang);
        return $this->result;
    }

    /**
     * clears stopwords from the text
     *
     * @param bool $dedectLang
     * @return GermanPrepareModel[]
     */
    protected function prepareText($dedectLang)
    {
        // clean/strip text
        $this->setText(GermanPrepareUtility::cleanText($this->text), $dedectLang);
        if($this->modCompare === 'stem'){
            // delete stemmed stopwords (compare stemmed)
            $this->setText(
                GermanPrepareUtility::deleteStopWordsStem($this->text, $this->getStopWords(), $this->lang)
            );
        } else {
            // remove stopWords from text
            $this->setText(
                GermanPrepareUtility::deleteStopWords($this->text, $this->getStopWords(), $this->lang)
            );
        }
        // text to array
        $textArray = explode(' ', $this->text);

        // literal text array
        foreach ($textArray as $word){
            // fill result
            $this->prepareWord($word);
        }
        // return result
        return $this->result;
    }

    /**
     * prepare singly word
     *
     * @param string $word
     */
    protected function prepareWord($word)
    {
        // compress word
        $wordC = GermanPrepareUtility::compressFulltext($word);
        if(!key_exists($wordC,$this->result)){

            // init obj
            $prepareWord = new GermanPrepareModel();
            // set original word
            $prepareWord->setOrig($word);
            // compress word
            $word = $wordC;
            // set compressed word
            $prepareWord->setClean(
                trim($word)
            );
            // set stem word
            if($this->mod === 3 || $this->mod === 0) {

                $prepareWord->setStem(
                    GermanPrepareUtility::getStemmedWord($word, $this->lang)
                );
            }
            // set soundex word
            if($this->mod === 2 || $this->mod === 0) {
                $prepareWord->setSoundex(
                    GermanPrepareUtility::getSoundexWord($word, $this->lang)
                );
            }
        } else {

            // get exists obj
            $prepareWord = $this->result[$wordC];
        }
        // increment object counter
        $prepareWord->incrementOccour();
        // result array push
        $this->result[$wordC] = $prepareWord;
    }
}