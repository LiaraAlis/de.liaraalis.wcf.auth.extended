<?php
namespace wcf\system\user\authentication;
use wcf\data\user\group\UserGroup;
use wcf\data\user\UserAction;
use wcf\data\user\User;
use wcf\data\user\UserEditor;
use wcf\data\user\UserProfileAction;
use wcf\system\exception\SystemException;
use wcf\system\exception\UserInputException;
use wcf\util\HeaderUtil;
use wcf\util\PasswordUtil;
use wcf\system\database\MySQLDatabase;
use wcf\system\database\PostgreSQLDatabase;
use wcf\util\LDAPUtil;
use wcf\util\UserUtil;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;

/**
 * @author      Jan Altensen (Stricted) / Alexander Pankow (LiaraAlis)
 * @copyright   2013-2014 Jan Altensen (Stricted) / 2014 Alexander Pankow (LiaraAlis)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.liaraalis.wcf.auth.extended
 * @category    Community Framework
 */
class UserIMAPAuthentication extends UserAbstractAuthentication {

	/**
	 * Checks the given user data.
	 *
	 * @param	string		$loginName
	 * @param 	string		$password
	 * @return	boolean
	 */
	protected function login ($loginName, $password) {
		if(!extension_loaded("imap")) {
			throw new SystemException("Can not find IMAP extension.");
		}
		if($this->isValidEmail($loginName)) {
			$options = '{'.AUTH_TYPE_IMAP_HOST.':'.AUTH_TYPE_IMAP_PORT.AUTH_TYPE_IMAP_BASEOPTIONS.'}';
			$conn = @imap_open($options, $loginName, $password, OP_HALFOPEN);
			if($conn) {
				$this->email = $loginName;
				@imap_close($conn);
				return true;
			}
			@imap_close($conn);
			if(AUTH_CHECK_WCF) {
				return $this->checkWCFUser($loginName, $password);
			}
		}
		return false;
	}
}
?>
