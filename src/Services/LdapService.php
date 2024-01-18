<?php

namespace SimonMarcelLinden\JWT\Services;

use SimonMarcelLinden\JWT\Exceptions\ResponseException;

/**
 * LdapService class.
 *
 * This service handles LDAP authentication, providing methods for connecting to an LDAP server,
 * authenticating users, and retrieving their details.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 */
class LdapService {
	protected $ldapConnection;

	/**
	 * Constructor for LdapService.
	 *
	 * Establishes a connection with the LDAP server using the specified host and port in the configuration.
	 * Sets LDAP protocol version and referral options.
	 *
	 * @throws ResponseException If unable to connect to the LDAP server.
	 */
	public function __construct() {
		$ldapServer = config('jwt.ldap.host', 'default');
		$ldapPort = config('jwt.ldap.port', 389);

		if ($this->setLdapConnection(ldap_connect($ldapServer, $ldapPort))) {
			ldap_set_option($this->ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($this->ldapConnection, LDAP_OPT_REFERRALS, 0);
		} else {
			throw new ResponseException('Can\'t contact LDAP server', 503);
		}
	}
	public function setLdapConnection($ldapConnection) {
		$this->ldapConnection = $ldapConnection;
	}

	/**
	 * Destructor for LdapService.
	 *
	 * Closes the LDAP connection when the object is destroyed.
	 */
	public function __destruct() {
		$this->close();
	}

	/**
	 * Closes the LDAP connection.
	 */
	private function close() {
		if ($this->ldapConnection) {
			ldap_close($this->ldapConnection);
		}
	}

	/**
	 * Authenticates a user against the LDAP server.
	 *
	 * Attempts to bind to the LDAP server with the provided username and password, and retrieves user details
	 * if authentication is successful.
	 *
	 * @param string $username The LDAP username.
	 * @param string $password The LDAP password.
	 * @return array The authenticated user's details.
	 * @throws ResponseException If authentication fails or user details are not found.
	 */
	public function authenticate($username, $password) {
		if (!$this->ldapConnection) {
			throw new ResponseException('LDAP connection not established', 500);
		}

		$base = config('jwt.ldap.base', 'dc=local,dc=local');
		$dn = "cn=$username,ou=users,$base";

		if (!@ldap_bind($this->ldapConnection, $dn, $password)) {
			throw new ResponseException('Authentication failed: LDAP binding unsuccessful: ' . ldap_error($this->ldapConnection), 401);
		}

		$searchDn = "cn=$username,ou=users,$base";
		$attributes = ['uidNumber', 'uid', 'sn', 'givenName', 'mail'];

		if (($search = ldap_search($this->ldapConnection, $searchDn, "(cn=$username)", $attributes)) == false) {
			throw new ResponseException('LDAP search failed', 500);
		}

		$info = ldap_get_entries($this->ldapConnection, $search);

		if ($info['count'] > 0) {
			return [
				'id' => $info[0]['uidnumber'][0],
				'username' => $info[0]['uid'][0],
				'firstname' => $info[0]['givenname'][0],
				'lastname' => $info[0]['sn'][0],
				'email' => $info[0]['mail'][0],
			];
		} else {
			throw new ResponseException('User details not found', 404);
		}
	}
}
