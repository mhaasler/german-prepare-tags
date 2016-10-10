<?php
/**
 * Author: mhaasler
 * Author URI: http://www.mhaasler.de
 * Copyright 2016  Maik Haasler  (email : m.haasler@gmx.de)
 */

/**
 * Created by PhpStorm.
 * User: maikhaasler
 * Date: 04.10.16
 * Time: 10:59
 */

namespace mhaasler\GermanPrepare;

require __DIR__ . '/../../../autoload.php';

use PHPUnit\Framework\TestCase;
use mhaasler\GermanPrepare\Utility\GermanPrepareUtility;


class GermanPrepareTest extends TestCase
{
    /**
     * @var GermanPrepare
     */
    private $g;
    /**
     * @var string $textEN
     */
    private $textEN = "To quickly localize defects, we want our attention to be focussed on relevant failing tests.";
    /**
     * @var string $textDE
     */
    private $textDE = "Fehlermeldungen, die mit „Warning: …“ beginnen, sollen dich (und nicht unbedingt die Öffentlichkeit) darauf aufmerksam machen, dass es ein Problem gibt. Generell lassen sich solche Fehlermeldungen unterdrücken, wenn du in deiner wp-config.php die Kontstante für WP_DEBUG auf false setzt: define(‚WP_DEBUG‘, false); Nur – behoben wird das Problem damit natürlich nicht. Gehen wir deshalb ein wenig ins Detail.";

    protected function setup ()
    {
        ini_set('magic_quotes_runtime', 0);
        $this->g = new GermanPrepare();
    }

    protected function tearDown ()
    {
        unset($this->g);
    }

    public function testClass()
    {
        $this->assertInstanceOf(GermanPrepare::class, $this->g);
    }

    public function testDatFileReading()
    {
        $fileTypes = array('en', 'de', 'stem_en', 'stem_de');

        foreach ($fileTypes as $type){

            $stopword = GermanPrepareUtility::getStopWordsFromFile($type);
            $this->assertTrue(is_array($stopword));
        }
    }

    public function testEnLang()
    {
        $this->g->setText($this->textEN);
        $this->assertEquals($this->g->getLang(), 'en');
    }

    public function testDeLang()
    {
        $this->g->setText($this->textDE);
        $this->assertEquals($this->g->getLang(), 'de');
    }

    public function testGetTagsEN()
    {
        $this->g->setText($this->textEN);
        $this->assertTrue(is_array($this->g->getTags()));
    }

    public function testGetTagsDE()
    {
        $this->g->setText($this->textDE);
        $this->assertTrue(is_array($this->g->getTags()));
    }

    public function testPrepareText()
    {
        print_r ($this->g->setText($this->textDE)
            ->getTags());
    }

    public function testPrepareStemText()
    {
        print_r ($this->g->setText($this->textDE)
            ->setModCompare('stem')
            ->getTags());
    }
}