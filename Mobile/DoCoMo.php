<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: KUBO Atsuhiro <kubo@isite.co.jp>                            |
// +----------------------------------------------------------------------+
//
// $Id: DoCoMo.php,v 1.18 2004/05/27 07:27:55 kuboa Exp $
//

require_once(dirname(__FILE__) . '/Common.php');
require_once(dirname(__FILE__) . '/Display.php');
require_once(dirname(__FILE__) . '/DoCoMoDisplayMap.php');

/**
 * NTT DoCoMo implementation
 *
 * Net_UserAgent_Mobile_DoCoMo is a subclass of
 * {@link Net_UserAgent_Mobile_Common}, which implements NTT docomo i-mode
 * user agents.
 *
 * SYNOPSIS:
 * <code>
 * require_once('Net/UserAgent/Mobile.php');
 *
 * $_SERVER['HTTP_USER_AGENT'] = 'DoCoMo/1.0/P502i/c10';
 * $agent = &Net_UserAgent_Mobile::factory();
 *
 * printf("Name: %s\n", $agent->getName()); // 'DoCoMo'
 * printf("Version: %s\n", $agent->getVersion()); // 1.0
 * printf("HTML version: %s\n", $agent->getHTMLVersion()); // 2.0
 * printf("Model: %s\n", $agent->getModel()); // 'P502i'
 * printf("Cache: %dk\n", $agent->getCacheSize()); // 10
 * if ($agent->isFOMA()) {
 *     print "FOMA\n";             // false
 * }
 * printf("Vendor: %s\n", $agent->getVendor()); // 'P'
 * printf("Series: %s\n", $agent->getSeries()); // '502i'
 *
 * // only available with <form utn>
 * // e.g.) 'DoCoMo/1.0/P503i/c10/serNMABH200331';
 * printf("Serial: %s\n", $agent->getSerialNumber()); // 'NMABH200331'
 *
 * // e.g.) 'DoCoMo/2.0 N2001(c10;ser0123456789abcde;icc01234567890123456789)';
 * printf("Serial: %s\n", $agent->getSerialNumber()); // '0123456789abcde'
 * printf("Card ID: %s\n", $agent->getCardID()); // '01234567890123456789'
 *
 * // e.g.) 'DoCoMo/1.0/P502i (Google CHTML Proxy/1.0)'
 * printf("Comment: %s\n", $agent->getComment()); // 'Google CHTML Proxy/1.0'
 *
 * // only available in eggy/M-stage
 * // e.g.) 'DoCoMo/1.0/eggy/c300/s32/kPHS-K'
 * printf("Bandwidth: %dkbps\n", $agent->getBandwidth()); // 32
 * </code>
 *
 * @package  Net_UserAgent_Mobile
 * @category Networking
 * @author   KUBO Atsuhiro <kubo@isite.co.jp>
 * @access   public
 * @version  $Revision: 1.18 $
 * @see      Net_UserAgent_Mobile_Common
 * @link     http://www.nttdocomo.co.jp/p_s/imode/spec/useragent.html
 * @link     http://www.nttdocomo.co.jp/p_s/imode/tag/imodetag.html
 * @link     http://www.nttdocomo.co.jp/p_s/imode/tag/utn.html
 * @link     http://www.nttdocomo.co.jp/p_s/mstage/visual/contents/cnt_mpage.html
 */
class Net_UserAgent_Mobile_DoCoMo extends Net_UserAgent_Mobile_Common
{

    // {{{ properties

    /**#@+
     * @access private
     */

    /**
     * name of the model like 'P502i'
     * @var string
     */
    var $_model = '';

    /**
     * status of the cache (TC, TB, TD, TJ)
     * @var string
     */
    var $_status = '';

    /**
     * bandwidth like 32 as kilobytes unit
     * @var integer
     */
    var $_bandwidth = null;

    /**
     * hardware unique serial number
     * @var string
     */
    var $_serialNumber = null;

    /**
     * whether it's FOMA or not
     * @var boolean
     */
    var $_isFOMA = false;

    /**
     * FOMA Card ID (20 digit alphanumeric)
     * @var string
     */
    var $_cardID = null;

    /**
     * comment on user agent string like 'Google Proxy'
     * @var string
     */
    var $_comment = null;

    /**
     * cache size as killobytes unit
     * @var integer
     */
    var $_cacheSize;

    /**
     * width and height of the display
     * @var string
     */
    var $_displayBytes = '';

    /**#@-*/

    /**#@+
     * @access public
     */

    // }}}
    // {{{ isDoCoMo()

    /**
     * returns true
     *
     * @return boolean
     */
    function isDoCoMo()
    {
        return true;
    }

    // }}}
    // {{{ parse()

    /**
     * parse HTTP_USER_AGENT string
     *
     * @return mixed void, or a PEAR error object on error
     */
    function parse()
    {
        @list($main, $foma_or_comment) =
            explode(' ', $this->getUserAgent(), 2);

        if ($foma_or_comment
            && preg_match('/^\((.*)\)$/', $foma_or_comment, $matches)
            ) {

            // DoCoMo/1.0/P209is (Google CHTML Proxy/1.0)
            $this->_comment = $matches[1];
            $result = $this->_parseMain($main);
        } elseif ($foma_or_comment) {

            // DoCoMo/2.0 N2001(c10;ser0123456789abcde;icc01234567890123456789)
            $this->_isFOMA = true;
            list($this->name, $this->version) = explode('/', $main);
            $result = $this->_parseFOMA($foma_or_comment);
        } else {

            // DoCoMo/1.0/R692i/c10
            $result = $this->_parseMain($main);
        }

        if (Net_UserAgent_Mobile::isError($result)) {
            return $result;
        }
    }

    // }}}
    // {{{ makeDisplay()

    /**
     * create a new {@link Net_UserAgent_Mobile_Display} class instance
     *
     * @return object a newly created {@link Net_UserAgent_Mobile_Display}
     *     object
     * @see Net_UserAgent_Mobile_Display
     * @see Net_UserAgent_Mobile_DoCoMoDisplayMap::get()
     */
    function makeDisplay()
    {
        $display = Net_UserAgent_Mobile_DoCoMoDisplayMap::get($this->_model);
        if ($this->_displayBytes !== '') {
            list($widthBytes, $heightBytes) =
                explode('*', $this->_displayBytes);
            $display['width_bytes']  = $widthBytes;
            $display['height_bytes'] = $heightBytes;
        }
        return new Net_UserAgent_Mobile_Display($display);
    }

    // }}}
    // {{{ getHTMLVersion()

    /**
     * returns supported HTML version like '3.0'. retuns null if unknown.
     *
     * @return string
     */
    function getHTMLVersion()
    {
        static $htmlVersionMap;
        if (!isset($htmlVersionMap)) {
            $htmlVersionMap = array(
                                    '[DFNP]501i' => '1.0',
                                    '502i|821i|209i|691i|(F|N|P|KO)210i|^F671i$' => '2.0',
                                    '(D210i|SO210i)|503i|211i|SH251i|692i|200[12]|2101V' => '3.0',
                                    '504i|251i|^F671iS$|212i|2051|2102V|^F661i$|2701|^F672i$' => '4.0',
                                    'eggy|P751v' => '3.2',
                                    '505i|252i|900i|506i' => '5.0'
                                    );
        }

        foreach ($htmlVersionMap as $key => $value) {
            if (preg_match("/$key/", $this->_model)) {
                return $value;
            }
        }
        return null;
    }

    // }}}
    // {{{ getCacheSize()

    /**
     * returns cache size as kilobytes unit. returns 5 if unknown.
     *
     * @return integer
     */
    function getCacheSize()
    {
        if ($this->_cacheSize) {
            return $this->_cacheSize;
        }

        static $defaultCacheSize;
        if (!isset($defaultCacheSize)) {
            $defaultCacheSize = 5;
        }
        return $defaultCacheSize;
    }

    // }}}
    // {{{ getSeries()

    /**
     * returns series name like '502i'. returns null if unknown.
     *
     * @return string
     */
    function getSeries()
    {
        if ($this->isFOMA()) {
            return 'FOMA';
        }
        if (preg_match('/(\d{3}i)/', $this->_model, $matches)) {
            return $matches[1];
        }
        return null;
    }

    // }}}
    // {{{ getVendor()

    /**
     * returns vender code like 'SO' for Sony. returns null if unknown.
     *
     * @return string
     */
    function getVendor()
    {
        if (preg_match('/([A-Z]+)\d/', $this->_model, $matches)) {
            return $matches[1];
        }
        return null;
    }

    // }}}
    // {{{ getModel()

    /**
     * returns name of the model like 'P502i'
     *
     * @return string
     */
    function getModel()
    {
        return $this->_model;
    }

    // }}}
    // {{{ getStatus()

    /**
     * returns status of the cache (TB, TD, TJ)
     *
     * @return string
     */
    function getStatus()
    {
        return $this->_status;
    }

    // }}}
    // {{{ getBandwidth()

    /**
     * returns bandwidth like 32 as killobytes unit. Only vailable in eggy,
     * returns null otherwise.
     *
     * @return integer
     */
    function getBandwidth()
    {
        return $this->_bandwidth;
    }

    // }}}
    // {{{ getSerialNumber()

    /**
     * returns hardware unique serial number (15 digit in FOMA, 11 digit
     * otherwise alphanumeric). Only available with form utn attribute.
     * returns null otherwise.
     *
     * @return string
     */
    function getSerialNumber()
    {
        return $this->_serialNumber;
    }

    // }}}
    // {{{ isFOMA()

    /**
     * retuns whether it's FOMA or not
     *
     * @return boolean
     */
    function isFOMA()
    {
        return $this->_isFOMA;
    }

    // }}}
    // {{{ getComment()

    /**
     * returns comment on user agent string like 'Google Proxy'. returns null
     * otherwise.
     *
     * @return string
     */
    function getComment()
    {
        return $this->_comment;
    }

    // }}}
    // {{{ getCardID()

    /**
     * returns FOMA Card ID (20 digit alphanumeric). Only available in FOMA
     * with <form utn> attribute. returns null otherwise.
     *
     * @return string
     */ 
    function getCardID()
    {
        return $this->_cardID;
    }

    // }}}
    // {{{ isGPS()

    /**
     * @return boolean
     */ 
    function isGPS()
    {
        static $gpsModels;
        if (!isset($gpsModels)) {
            $gpsModels = array('F661i');
        }
        return in_array($this->_model, $gpsModels);
    }

    /**#@-*/

    /**#@+
     * @access private
     */

    // }}}
    // {{{ _parseMain()

    /**
     * parse main part of HTTP_USER_AGENT string (not FOMA)
     *
     * @param string $main main part of HTTP_USER_AGENT string
     * @return mixed void, or a PEAR error object on error
     */ 
    function _parseMain($main)
    {
        @list($this->name, $this->version, $this->_model, $cache, $rest) =
            explode('/', $main, 5);
        if ($this->_model === 'SH505i2') {
            $this->_model = 'SH505i';
        }

        if ($cache) {
            if (!preg_match('/^c(\d+)/', $cache, $matches)) {
                return $this->noMatch();
            }
            $this->_cacheSize = (integer)$matches[1];
        }

        if ($rest) {
            $rest = explode('/', $rest);
            foreach ($rest as $value) {
                if (preg_match('/^ser(\w{11})$/', $value, $matches)) {
                    $this->_serialNumber = $matches[1];
                    continue;
                }
                if (preg_match('/^(T[CDBJ])$/', $value, $matches)) {
                    $this->_status = $matches[1];
                    continue;
                }
                if (preg_match('/^s(\d+)$/', $value, $matches)) {
                    $this->_bandwidth = (integer)$matches[1];
                    continue;
                }
                if (preg_match('/^W(\d+)H(\d+)$/', $value, $matches)) {
                    $this->_displayBytes = "{$matches[1]}*{$matches[2]}";
                    continue;
                }
            }
        }
    }

    // }}}
    // {{{ _parseFOMA()

    /**
     * parse main part of HTTP_USER_AGENT string (FOMA)
     *
     * @param string $foma main part of HTTP_USER_AGENT string
     * @return mixed void, or a PEAR error object on error
     */ 
    function _parseFOMA($foma)
    {
        if (!preg_match('/^([^(]+)/', $foma, $matches)) {
            return $this->noMatch();
        }
        $this->_model = $matches[1];
        if ($matches[1] === 'MST_v_SH2101V') {
            $this->_model = 'SH2101V';
        }

        if (preg_match('/^[^(]+\((.*?)\)$/', $foma, $matches)) {
            $rest = explode(';', $matches[1]);
            foreach ($rest as $value) {
                if (preg_match('/^c(\d+)/', $value, $matches)) {
                    $this->_cacheSize = (integer)$matches[1];
                    continue;
                }
                if (preg_match('/^ser(\w{15})$/', $value, $matches)) {
                    $this->_serialNumber = $matches[1];
                    continue;
                }
                if (preg_match('/^(T[CDBJ])$/', $value, $matches)) {
                    $this->_status = $matches[1];
                    continue;
                }
                if (preg_match('/^icc(\w{20})$/', $value, $matches)) {
                    $this->_cardID = $matches[1];
                    continue;
                }
                if (preg_match('/^W(\d+)H(\d+)$/', $value, $matches)) {
                    $this->_displayBytes = "{$matches[1]}*{$matches[2]}";
                    continue;
                }
                return $this->noMatch();
            }
        }
    }

    /**#@-*/
}

/*
 * Local Variables:
 * mode: php
 * coding: iso-8859-1
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * indent-tabs-mode: nil
 * End:
 */
?>
