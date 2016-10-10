<?php
/*
 * ---------------------------------------------------------------------
 * German Prepare Model Class
 * ---------------------------------------------------------------------
 *
 * @package    german-prepare-tags
 * @version    0.1
 * @author     Maik Haasler
 * @copyright  Copyright (c) 2016, mhaasler.de
 * @license    GNU General Public License
 */

namespace mhaasler\GermanPrepare\Model;


class GermanPrepareModel
{
    /**
     * @var string
     */
    protected $orig;
    /**
     * @var string
     */
    protected $clean;
    /**
     * @var string
     */
    protected $stem;
    /**
     * @var string
     */
    protected $soundex;
    /**
     * @var integer
     */
    protected $occour=0;

    /**
     * @return int
     */
    public function getOccour()
    {
        return $this->occour;
    }

    /**
     * @param int $occour
     */
    public function setOccour($occour)
    {
        $this->occour = $occour;
    }

    /**
     * @return string
     */
    public function getOrig()
    {
        return $this->orig;
    }

    /**
     * @param string $orig
     */
    public function setOrig($orig)
    {
        $this->orig = $orig;
    }


    /**
     * @return string
     */
    public function getClean()
    {
        return $this->clean;
    }

    /**
     * @param string $clean
     */
    public function setClean($clean)
    {
        $this->clean = $clean;
    }

    /**
     * @return string
     */
    public function getStem()
    {
        return $this->stem;
    }

    /**
     * @param string $stem
     */
    public function setStem($stem)
    {
        $this->stem = $stem;
    }

    /**
     * @return string
     */
    public function getSoundex()
    {
        return $this->soundex;
    }

    /**
     * @param string $soundex
     */
    public function setSoundex($soundex)
    {
        $this->soundex = $soundex;
    }

    /**
     * increment occour counter
     */
    public function incrementOccour(){

        $this->occour++;
    }
}