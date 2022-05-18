<?php

namespace Manager;

use \Gateway\User as GWUser ;

class User {
    /**
     * Возвращает пользователей старше заданного возраста.
     * @param int $ageFrom
     * @return array
     */
    function getUsers( int $ageFrom ) : array {
        return GWUser::getByAgeFrom( $ageFrom ) ;
    }

    /**
     * Возвращает пользователей по списку имен.
     * @return array
     */
    public static function getByNames( array $names = [ ] ) : array {
		return GWUser::getByName( implode( ' ' , $name ) ) ;
    }

    /**
     * Добавляет пользователей в базу данных.
     * @param $users
     * @return array
     */
    public function addUsers( array $users = [ ] ) : array {
		return array_filter( array_map( function( array &$user ) {
			return GWUser::add( $user['name'] , $user['lastName'] , $user['age'] ) ;
		} , $users ) ) ;
    }
}
