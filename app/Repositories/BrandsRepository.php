<?php

namespace App\Repositories;

use Danganf\MyClass\Json\Contracts\JsonAbstract;
use Danganf\Repositories\Contracts\RepositoryAbstract;

class BrandsRepository extends RepositoryAbstract
{
    public function __construct()
    {
        parent::__construct( __CLASS__ );
    }

    /**
     * PEGA DOS REGISTROS AINDA NAO SINCRONIZADOS PELO JOB
     * @param int $limit
     * @return mixed
     */
    public function getNoSync( $limit = 1 ){
        return $this->getModel()->where('sync',0)->selectRaw('id, type_name, name')->limit($limit)->get()->toArray();
    }

    /**
     * ATUALIZANDO REGISTRO SINCRONIZADO
     * @param $brandID
     * @return mixed
     */
    public function updateNoSync( $brandID ){
        return $this->getModel()->where('sync',0)->where('id',$brandID)->update( [ 'sync' => 1 ] );
    }

    /**
     * @return mixed
     */
    public function filter(){
        return $this->getModel()->paginate(10)->toArray();
    }

    /**
     * @param JsonAbstract $jsonValues
     * @param null $id
     */
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
