<?php

namespace GravityKit\GravityExport\Save\Service;

/**
 * Service that handles encrypting and decrypting passwords.
 *
 * @since 1.0
 */
class PasswordService {
	/**
	 * Algorithm used for storing the password.
	 *
	 * @since 1.0
	 */
	private const CIPHER_ALGO = 'AES-128-ECB';

	/**
	 * Holds the hashing secret.
	 *
	 * @since 1.0
	 * @var string
	 */
	private $secret;

	/**
	 * Creates the service.
	 *
	 * @since 1.0
	 *
	 * @param string $secret The hashing secret.
	 */
	public function __construct( string $secret ) {
		$this->secret = $secret;
	}

	/**
	 * Encrypts the password using a predefined secret.
	 *
	 * @since 1.0
	 *
	 * @param string $password The password to encrypt.
	 *
	 * @return string The encrypted password.
	 */
	public function encrypt( string $password ): string {
		if ( empty( $password ) ) {
			return $password;
		}

		return openssl_encrypt( $password, self::CIPHER_ALGO, $this->secret ) ?: $password;
	}

	/**
	 * Decrypts a hash back to a password.
	 *
	 * @since 1.0
	 *
	 * @param string $encrypted_password The password to decrypt.
	 *
	 * @return string|null The decrypted password.
	 */
	public function decrypt( string $encrypted_password ): ?string {
		if ( empty( $encrypted_password ) ) {
			return null;
		}

		return openssl_decrypt( $encrypted_password, self::CIPHER_ALGO, $this->secret ) ?: null;
	}
}
