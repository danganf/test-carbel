<?php

namespace App\Repositories;

use Danganf\MyClass\Json\Contracts\JsonAbstract;
use Danganf\Repositories\Contracts\RepositoryAbstract;

class UserRepository extends RepositoryAbstract
{
    public function __construct()
    {
        parent::__construct( __CLASS__ );
    }

    /**
     * @param $login
     * @param $pass
     * @return bool
     */
    public function auth( $login, $pass ){
        return $login == 'carbel' && $pass=='carbel123#' ? TRUE : FALSE;
    }

    public function createOrUpdate(JsonAbstract $jsonValues, $id=null)
    {
        //
    }
}
