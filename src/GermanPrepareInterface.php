<?php
/*
 * ---------------------------------------------------------------------
 * Class GermanPrepare Interface
 * ---------------------------------------------------------------------
 *
 * @package    german-prepare-tags
 * @version    0.1
 * @author     Maik Haasler
 * @copyright  Copyright (c) 2016, mhaasler.de
 * @license    GNU General Public License
 */

namespace mhaasler\GermanPrepare;

use mhaasler\GermanPrepare\Model\GermanPrepareModel;

interface GermanPrepareInterface
{


    /**
     * GermanPrepareInterface constructor. <br />
     * default mod is soundex, stem and trim
     * @param string $text
     */
    public function __construct($text="");

    /**
     * @param integer $mod
     * @return $this
     */
    public function setMod($mod);

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text);

    /**
     * @return GermanPrepareModel[]
     */
    public function getTags();

}