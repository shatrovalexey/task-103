<?php
use PDO ;

/**
* Подключение к СУБД
*/
class Dba {
	/**
	 * @var \PDO $dbh - подключение к СУБД
	 */
	protected static $dbh ;

	/**
	 * @var string $dsn - DSN для полкючения к СУБД
	 */
	protected static $dsn ;

	/**
	 * @var string $user - пользователь для полкючения к СУБД
	 */
	protected static $user ;

	/**
	 * @var string $password - пароль для полкючения к СУБД
	 */
	protected static $password ;

	/**
	* Настройка подключенния к СУБД
	* @param string $dsn - DSN для полкючения к СУБД
	* @param string $user - пользователь для полкючения к СУБД
	* @param string $password - пароль для полкючения к СУБД
	*/
	public static function prepare( string $dsn , string $user , string $password ) {
		static::$dsn = $dsn ;
		static::$user = $user ;
		static::$password = $password ;

		return static::class ;
	}

	/**
	 * Реализация singleton
	 * @return PDO
	 */
	public static function getInstance( ) : PDO {
		if ( ! empty( static::$dbh ) ) {
			return static::$dbh ;
		}

		static::$dbh = new \PDO( static::$dsn , static::$user , static::$password ) ;

		return static::$dbh ;
	}
}
