<?php
/*
 * ---------------------------------------------------------------------
 * Class GermanPrepare Utility
 * ---------------------------------------------------------------------
 *
 * @package    german-prepare-tags
 * @version    0.1
 * @author     Maik Haasler
 * @copyright  Copyright (c) 2016, mhaasler.de
 * @license    GNU General Public License
 *
 * default language german; supported: english by auto detect
 */
namespace mhaasler\GermanPrepare\Utility;

use paslandau\GermanStemmer\GermanStemmer;
use mhaasler\GermanPrepare\GermanPhonetic\SoundexGer;
use TextLanguageDetect\TextLanguageDetect;
use TextLanguageDetect\LanguageDetect\TextLanguageDetectException;

abstract class GermanPrepareUtility
{
    /**
     * clean text
     *
     * @param string $text
     * @return string
     */
    public static function cleanText($text="")
    {
        // cleaning
        $text= html_entity_decode($text);
        $text= str_replace(">","> ",$text);
        $text= str_replace("<"," <",$text);
        $text = preg_replace("/<code(.|\n)*?<\/code>/i", " ", $text);
        $text = preg_replace("/[\$][a-zA-Z_\/0-9\,]*\b/im", " ", $text); // php vars like $text
        $text = strip_tags(trim($text));
        $text = preg_replace("/\b(http(s?):\/\/)[A-Za-z0-9\-\/\%\?\=\.]*\b/i", " ", $text); // urls
        $text = preg_replace("/\b\S+@\S+\.\S+\b/", " ", $text); // emails
        $text = preg_replace("/\.|,|:|;|-|'|\(|\)|\"|\&|[0-9]|#|…|–|“|„|”|‹|›|«|»|˜|\^|⋅|•|′|‘|’|‚|\!|\?|   |   |©|\[|\]/u"," ",$text);
        $text= str_replace("/"," ",$text);
        $text = preg_replace("/>|<|=/i"," ",$text);
        return $text;
    }

    /**
     * delete stopwords from string with autodedected language
     *
     * @param string $text
     * @param string $inStopwords
     * @param string $lang de | en
     * @return string
     */
    public static function deleteStopWords($text="",$inStopwords=null, $lang = null)
    {
        $lang = $lang ? $lang  : self::getTextLanguage($text);
        $inStopwords = $inStopwords ? $inStopwords :  implode('\b|\b', self::getStopWordsFromFile($lang));

        $locSearch[] = "=(\s[A-Za-z]{1,2})\s=";
        $locSearch[] = "=\b" .$inStopwords. "\b=iu";
        $locSearch[] = "= +=";

        $locReplace[] = " ";
        $locReplace[] = " ";
        $locReplace[] = " ";

        $outString = " " . str_replace("?", "", $text) . " ";
        $outString = str_replace("  ", " ", $outString);
        $outString = " " . str_replace(" ", "  ", $outString) . " ";
        $text = trim(preg_replace($locSearch, $locReplace, " " . $outString . " "));

        return $text;
    }

    /**
     * delete stopwords from string with autodedected language
     *
     * @param string $text
     * @param string $inStopwords
     * @param string $lang de | en
     * @return string
     */
    public static function deleteStopWordsStem($text="",$inStopwords=null, $lang = null)
    {
        $lang = $lang ? $lang  : self::getTextLanguage($text);
        $inStopwords = $inStopwords ? $inStopwords : implode('\b|\b', self::getStopWordsFromFile('stem_' . $lang));
        $text = preg_replace("=(\s[A-Za-z]{1,2})\s=", " ", $text);
        $text = str_replace('  ',' ',$text);
        $outString = explode(' ',$text);
        $outArray = array();

        foreach ($outString as $word){
            $stem_word = trim($word);
            if(empty($stem_word)){continue;};
            $stem_word = self::getStemmedWord($stem_word);
            $stem_word = " " . str_replace(" ", "  ", $stem_word) . " ";
            $stem_word = preg_replace("/\b".$inStopwords."\b/ui","",$stem_word);
            if(!empty(trim($stem_word))) {
                $outArray[] = $word;
            }
        }
        $outArray = array_diff($outArray,array(''));
        return implode(" ", $outArray);
    }


    /**
     * stemming word list
     *
     * @param array $list
     * @param string $lang
     * @return array
     */
    public static function stemWordList(array $list, $lang = "de")
    {
        $return = array();
        foreach ($list as $word) {
            $return[] = self::getStemmedWord($word, $lang);
        }
        $return = array_unique($return);
        return $return;
    }

    /**
     * cleaning text
     *
     * @param string $word
     * @return string
     */
    public static function compressFulltext ($word="")
    {
        $locSearch[] = "=ß=iu";
        $locSearch[] = "=ä=iu";
        $locSearch[] = "=ö=iu";
        $locSearch[] = "=ü=iu";
        $locSearch[] = "=([0-9/.,+-<>#]*\s)=";
        //$locSearch[] = "=([^\pL])=u"; // only letters - disabled
        $locSearch[] = "= +=";

        $locReplace[] = "ss";
        $locReplace[] = "ae";
        $locReplace[] = "oe";
        $locReplace[] = "ue";
        $locReplace[] = " ";
        //$locReplace[] = " "; // only letters - disabled
        $locReplace[] = " ";

        $outString = trim( mb_strtolower(stripslashes(strip_tags($word)),'UTF-8') );
        $outString = preg_replace($locSearch, $locReplace, $outString );

        return $outString;
    }

    /**
     * get stopwordlist by type
     * en | de | stem_en | stem_de
     *
     * @param string $type
     * @return string
     */
    public static function getStopWordsFromFile($type = 'de')
    {
        switch ($type) {
            case 'en':
                $content = json_decode(file_get_contents(self::_getStopWordsFile('en')));
                break;
            case 'stem_de':
                $content = json_decode(file_get_contents(self::_getStopWordsFile('stem_de')));
                break;
            case 'stem_en':
                $content = json_decode(file_get_contents(self::_getStopWordsFile('stem_en')));
                break;
            default:
                $content = json_decode(file_get_contents(self::_getStopWordsFile()));
                break;
        }
        return $content;
    }

    /**
     * get dat file by type
     *
     * @return string
     */
    protected static function _getStopWordsFile($type = 'de')
    {

        $file = dirname(__FILE__)."/data/stopwordlist_{$type}.json";
        return $file;
    }

    /**
     * get given word stemmed
     *
     * @param string $word
     * @param string $lang
     * @return string
     */
    public static function getStemmedWord($word, $lang = "de")
    {
        if("de" === $lang) {
            $word = GermanStemmer::stem($word);
        } else {
            $word = PorterStemmer::Stem($word);
        }
        return $word;
    }

    /**
     * get given word as soundex
     *
     * @param string $word
     * @param string $lang
     * @return string
     */
    public static function getSoundexWord($word, $lang = "de")
    {
        if("de" === $lang) {
            $word = SoundexGer::soundex($word);
        } else {
            $word = soundex($word);
        }
        return $word;
    }

    /**
     * get dedected language of text
     *
     * @param string $word
     * @param string $lang
     * @return mixed|string
     */
    public static function getTextLanguage($text)
    {
        $txtLanguageDetect = new TextLanguageDetect();
        try {
            //return 2-letter language codes only
            $txtLanguageDetect->setNameMode(2);
            $result = $txtLanguageDetect->detectSimple($text);
            $result = $result == 'de' ? 'de' : 'en';
            return $result;

        } catch (TextLanguageDetectException $e) {
            die($e->getMessage());
        }
    }
}