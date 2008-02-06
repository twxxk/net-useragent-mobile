<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP versions 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Networking
 * @package    Net_UserAgent_Mobile
 * @author     KUBO Atsuhiro <iteman@users.sourceforge.net>
 * @copyright  2008 KUBO Atsuhiro <iteman@users.sourceforge.net>
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id: EZwebTestCase.php,v 1.1 2008/02/06 03:24:33 kuboa Exp $
 * @since      File available since Release 0.31.0
 */

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Net/UserAgent/Mobile/EZweb.php';

// {{{ Net_UserAgent_Mobile_EZwebTestCase

/**
 * Some tests for Net_UserAgent_Mobile_EZweb.
 *
 * @category   Networking
 * @package    Net_UserAgent_Mobile
 * @author     KUBO Atsuhiro <iteman@users.sourceforge.net>
 * @copyright  2008 KUBO Atsuhiro <iteman@users.sourceforge.net>
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @since      Class available since Release 0.31.0
 */
class Net_UserAgent_Mobile_EZwebTestCase extends PHPUnit_Framework_TestCase
{

    // {{{ properties

    /**#@+
     * @access public
     */

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    private $_profiles = array('KDDI-CA23 UP.Browser/6.2.0.3.111 (GUI) MMP/2.0' => array('model' => 'CA23'),
                               'KDDI-CA24 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'CA24'),
                               'KDDI-CA25 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'CA25'),
                               'KDDI-CA26 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'CA26'),
                               'KDDI-CA27 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'CA27'),
                               'KDDI-CA28 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'CA28'),
                               'KDDI-CA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'CA31'),
                               'KDDI-CA32 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'CA32'),
                               'KDDI-CA33 UP.Browser/6.2.0.10.4 (GUI) MMP/2.0' => array('model' => 'CA33'),
                               'KDDI-CA34 UP.Browser/6.2.0.10.3.3 (GUI) MMP/2.0' => array('model' => 'CA34'),
                               'KDDI-CA35 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'CA35'),
                               'KDDI-CA37 UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'CA37'),
                               'KDDI-HI32 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'HI32'),
                               'KDDI-HI33 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'HI33'),
                               'KDDI-HI34 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'HI34'),
                               'KDDI-HI35 UP.Browser/6.2.0.9.2 (GUI) MMP/2.0' => array('model' => 'HI35'),
                               'KDDI-HI36 UP.Browser/6.2.0.10.4 (GUI) MMP/2.0' => array('model' => 'HI36'),
                               'KDDI-HI37 UP.Browser/6.2.0.10.3.3 (GUI) MMP/2.0' => array('model' => 'HI37'),
                               'KDDI-HI38 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'HI38'),
                               'KDDI-HI38 UP.Browser/6.2.0.11.1.3.110 (GUI) MMP/2.0' => array('model' => 'HI38'),
                               'KDDI-HI39 UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'HI39'),
                               'KDDI-KC22 UP.Browser/6.0.8.3 (GUI) MMP/1.1' => array('model' => 'KC22'),
                               'KDDI-KC23 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'KC23'),
                               'KDDI-KC27 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'KC27'),
                               'KDDI-KC28 UP.Browser/6.2.0.10.1 (GUI) MMP/2.0' => array('model' => 'KC28'),
                               'KDDI-KC31 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'KC31'),
                               'KDDI-KC31 UP.Browser/6.2.0.5.c.1.100 (GUI) MMP/2.0' => array('model' => 'KC31'),
                               'KDDI-KC32 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'KC32'),
                               'KDDI-KC33 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'KC33'),
                               'KDDI-KC34 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'KC34'),
                               'KDDI-KC35 UP.Browser/6.2.0.10.2.2 (GUI) MMP/2.0' => array('model' => 'KC35'),
                               'KDDI-KC36 UP.Browser/6.2.0.10.2.2 (GUI) MMP/2.0' => array('model' => 'KC36'),
                               'KDDI-KC37 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'KC37'),
                               'KDDI-KC38 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'KC38'),
                               'KDDI-KC38 UP.Browser/6.2.0.11.1.2.2 (GUI) MMP/2.0' => array('model' => 'KC38'),
                               'KDDI-KC39 UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'KC39'),
                               'KDDI-KC3A UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'KC3A'),
                               'KDDI-KCU1 UP.Browser/6.2.0.5.1 (GUI) MMP/2.0' => array('model' => 'KCU1'),
                               'KDDI-MA31 UP.Browser/6.2.0.11.1.3.110 (GUI) MMP/2.0' => array('model' => 'MA31'),
                               'KDDI-MA31 UP.Browser/6.2.0.11.1.4 (GUI) MMP/2.0' => array('model' => 'MA31'),
                               'KDDI-PT21 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'PT21'),
                               'KDDI-SA24 UP.Browser/6.0.8.2 (GUI) MMP/1.1' => array('model' => 'SA24'),
                               'KDDI-SA25 UP.Browser/6.2.0.4 (GUI) MMP/2.0' => array('model' => 'SA25'),
                               'KDDI-SA25 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'SA25'),
                               'KDDI-SA26 UP.Browser/6.2.0.5.1 (GUI) MMP/2.0' => array('model' => 'SA26'),
                               'KDDI-SA27 UP.Browser/6.2.0.6.3 (GUI) MMP/2.0' => array('model' => 'SA27'),
                               'KDDI-SA28 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'SA28'),
                               'KDDI-SA29 UP.Browser/6.2.0.10.1 (GUI) MMP/2.0' => array('model' => 'SA29'),
                               'KDDI-SA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'SA31'),
                               'KDDI-SA32 UP.Browser/6.2.0.8 (GUI) MMP/2.0' => array('model' => 'SA32'),
                               'KDDI-SA33 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'SA33'),
                               'KDDI-SA34 UP.Browser/6.2.0.9.1 (GUI) MMP/2.0' => array('model' => 'SA34'),
                               'KDDI-SA35 UP.Browser/6.2.0.9.1 (GUI) MMP/2.0' => array('model' => 'SA35'),
                               'KDDI-SA36 UP.Browser/6.2.0.10.2.1 (GUI) MMP/2.0' => array('model' => 'SA36'),
                               'KDDI-SA38 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'SA38'),
                               'KDDI-SA39 UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'SA39'),
                               'KDDI-SH31 UP.Browser/6.2.0.10.3.5 (GUI) MMP/2.0' => array('model' => 'SH31'),
                               'KDDI-SH32 UP.Browser/6.2.0.11.2.1 (GUI) MMP/2.0' => array('model' => 'SH32'),
                               'KDDI-SN25 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'SN25'),
                               'KDDI-SN26 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'SN26'),
                               'KDDI-SN27 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'SN27'),
                               'KDDI-SN29 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'SN29'),
                               'KDDI-SN31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'SN31'),
                               'KDDI-SN32 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'SN32'),
                               'KDDI-SN33 UP.Browser/6.2.0.9.2 (GUI) MMP/2.0' => array('model' => 'SN33'),
                               'KDDI-SN34 UP.Browser/6.2.0.10.4 (GUI) MMP/2.0' => array('model' => 'SN34'),
                               'KDDI-SN35 UP.Browser/6.2.0.9.2 (GUI) MMP/2.0' => array('model' => 'SN35'),
                               'KDDI-SN36 UP.Browser/6.2.0.10.4 (GUI) MMP/2.0' => array('model' => 'SN36'),
                               'KDDI-SN37 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'SN37'),
                               'KDDI-SN38 UP.Browser/6.2.0.11.2 (GUI) MMP/2.0' => array('model' => 'SN38'),
                               'KDDI-SN39 UP.Browser/6.2.0.11.2.1 (GUI) MMP/2.0' => array('model' => 'SN39'),
                               'KDDI-ST21 UP.Browser/6.0.8.3 (GUI) MMP/1.1' => array('model' => 'ST21'),
                               'KDDI-ST22 UP.Browser/6.0.8.3 (GUI) MMP/1.1' => array('model' => 'ST22'),
                               'KDDI-ST23 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'ST23'),
                               'KDDI-ST24 UP.Browser/6.2.0.8 (GUI) MMP/2.0' => array('model' => 'ST24'),
                               'KDDI-ST25 UP.Browser/6.2.0.8 (GUI) MMP/2.0' => array('model' => 'ST25'),
                               'KDDI-ST26 UP.Browser/6.2.0.8 (GUI) MMP/2.0' => array('model' => 'ST26'),
                               'KDDI-ST27 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'ST27'),
                               'KDDI-ST28 UP.Browser/6.2.0.10.1 (GUI) MMP/2.0' => array('model' => 'ST28'),
                               'KDDI-ST2C UP.Browser/6.2.0.11.1.2.1 (GUI) MMP/2.0' => array('model' => 'ST2C'),
                               'KDDI-ST31 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'ST31'),
                               'KDDI-TS21 UP.Browser/6.0.2.276 (GUI) MMP/1.1' => array('model' => 'TS21'),
                               'KDDI-TS25 UP.Browser/6.0.8.3 (GUI) MMP/1.1' => array('model' => 'TS25'),
                               'KDDI-TS27 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'TS27'),
                               'KDDI-TS28 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'TS28'),
                               'KDDI-TS29 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'TS29'),
                               'KDDI-TS2A UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'TS2A'),
                               'KDDI-TS2B UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'TS2B'),
                               'KDDI-TS2C UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'TS2C'),
                               'KDDI-TS2D UP.Browser/6.2.0.11.1.2.1 (GUI) MMP/2.0' => array('model' => 'TS2D'),
                               'KDDI-TS31 UP.Browser/6.2.0.8 (GUI) MMP/2.0' => array('model' => 'TS31'),
                               'KDDI-TS32 UP.Browser/6.2.0.9.1 (GUI) MMP/2.0' => array('model' => 'TS32'),
                               'KDDI-TS33 UP.Browser/6.2.0.9.1 (GUI) MMP/2.0' => array('model' => 'TS33'),
                               'KDDI-TS34 UP.Browser/6.2.0.10.2.1 (GUI) MMP/2.0' => array('model' => 'TS34'),
                               'KDDI-TS35 UP.Browser/6.2.0.10.2.1 (GUI) MMP/2.0' => array('model' => 'TS35'),
                               'KDDI-TS36 UP.Browser/6.2.0.10.2.1 (GUI) MMP/2.0' => array('model' => 'TS36'),
                               'KDDI-TS37 UP.Browser/6.2.0.10.3.3 (GUI) MMP/2.0' => array('model' => 'TS37'),
                               'KDDI-TS37 UP.Browser/6.2.0.10.3.3.1 (GUI) MMP/2.0' => array('model' => 'TS37'),
                               'KDDI-TS38 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'TS38'),
                               'KDDI-TS39 UP.Browser/6.2.0.11.2 (GUI) MMP/2.0' => array('model' => 'TS39'),
                               'KDDI-TS3A UP.Browser/6.2.0.11.2 (GUI) MMP/2.0' => array('model' => 'TS3A'),
                               'KDDI-TS3A UP.Browser/6.2.0.11.2.1 (GUI) MMP/2.0' => array('model' => 'TS3A'),
                               'KDDI-TS3B UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'TS3B'),
                               'KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'TS3C'),
                               'UP.Browser/3.01-HI01 UP.Link/3.4.5.2' => array('model' => 'HI01'),
                               'UP.Browser/3.04-KC14 UP.Link/3.4.5.9' => array('model' => 'KC14'),
                               'UP.Browser/3.04-KC15 UP.Link/3.4.5.9' => array('model' => 'KC15'),
                               'UP.Browser/3.04-KCT8 UP.Link/3.4.5.9' => array('model' => 'KCT8'),
                               'UP.Browser/3.04-ST14 UP.Link/3.4.5.9' => array('model' => 'ST14'),
                               'UP.Browser/3.04-TST4 UP.Link/3.4.5.6' => array('model' => 'TST4')
                               );

    /**#@-*/

    /**#@+
     * @access public
     */

    public function testShouldDetectUserAgentsAsEzweb()
    {
        reset($this->_profiles);
        while (list($userAgent, $profile) = each($this->_profiles)) {
            $agent = new Net_UserAgent_Mobile_EZweb($userAgent);

            $this->assertFalse($agent->isDoCoMo());
            $this->assertTrue($agent->isEZweb());
            $this->assertFalse($agent->isSoftBank());
            $this->assertFalse($agent->isWillcom());
            $this->assertFalse($agent->isNonMobile());
        }
    }

    public function testShouldProvideTheModelNameOfAUserAgent()
    {
        reset($this->_profiles);
        while (list($userAgent, $profile) = each($this->_profiles)) {
            $agent = new Net_UserAgent_Mobile_EZweb($userAgent);

            $this->assertEquals($profile['model'], $agent->getModel());
        }
    }

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    /**#@-*/

    // }}}
}

// }}}

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