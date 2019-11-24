<?php

namespace App\Repositories;

use Danganf\MyClass\Json\Contracts\JsonAbstract;
use Danganf\Repositories\Contracts\RepositoryAbstract;

class ModelsRepository extends RepositoryAbstract
{
    public function __construct()
    {
        parent::__construct( __CLASS__ );
    }

    public function createOrUpdate(JsonAbstract $jsonValues, $id=null)
    {
        $instanceModel = $this->getModel();
        foreach ( $jsonValues->toArray() as $key=>$value ){
            $instanceModel->{$key} = $value;
        }

        // TRY/CATCH APENAS PARA O CASO DE RODAR MAIS DE UMA VEZ E JA EXISTIR A MARCA CADASTRADA
        // DEVIDO AO TEMPO DO TEST, NAO IREI FAZER UMA VERIFICACAO SE EXISTI O REGISTRO ANTES DE SALVAR
        try{
            $instanceModel->save();
        } catch (\Exception $e){

        }
    }
}
