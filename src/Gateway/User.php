<?php

namespace Gateway;

use Dba ;

class User {
	/**
	* @const int LIMIT - максимальное количество записей, которое следует вернуть
	*/
	const LIMIT = 10 ;

    /**
     * Возвращает список пользователей старше заданного возраста.
     * @param int $ageFrom
     * @return array
     */
    public static function getByAgeFrom( int $ageFrom ) : array {
        $sth = Dba::getInstance()->prepare( '
SELECT
	`u1`.`id` ,
	`u1`.`name` ,
	`u1`.`lastName` ,
	`u1`.`from` ,
	`u1`.`age` ,
	`u1`.`settings`->>"$.key" AS `key`
FROM
	`Users` AS `u1`
WHERE
	( `u1`.`age` > :age )
LIMIT :limit ;
		' );
        $sth->execute( [
			':age' => $ageFrom ,
			':limit' => static::LIMIT ,
		] ) ;

		return $sth->fetchAll( \PDO::FETCH_ASSOC ) ;
    }

    /**
     * Возвращает пользователя по имени.
     * @param string $name
     * @return array
     */
    public static function getByName( string $name ) : ?array {
        $sth = Dba::getInstance( )->prepare( '
SELECT
	`u1`.`id` ,
	`u1`.`name` ,
	`u1`.`lastName` ,
	`u1`.`from`,
	`u1`.`age` ,
	`u1`.`settings`
FROM
	`Users` AS `u1`
WHERE
	( MATCH( `u1`.`name` ) AGAINST( :user  IN NATURAL LANGUAGE MODE ) ) ;
		' ) ;
        $sth->execute( [
			':user' => $name ,
		] ) ;

        return array_map( function( array $row ) {
			$row[ 'settings' ] = @json_decode( $row[ 'settings' ] ) ;

			return $row ;
		} , $sth->fetchAll( \PDO::FETCH_ASSOC ) ) ;
    }

    /**
     * Добавляет пользователя в базу данных.
     * @param string $name
     * @param string $lastName
     * @param int $age
     * @return string
     */
    public static function add( string $name , string $lastName , int $age ) : ?int {
		$dbh = Dba::getInstance( ) ;

		try {
		    $dbh->prepare( '
INSERT INTO
	`Users`
SET
	`name` := :name ,
	`lastName` := :lastName ,
	`age` := :age ;
		' )->execute( [
			':name' => $name ,
			':age' => $age ,
			':lastName' => $lastName ,
			] ) ;
		} catch ( \Exception $exception ) {
			return null ;
		}

        return $dbh->lastInsertId( ) ;
    }
}
