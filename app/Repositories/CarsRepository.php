<?php

namespace App\Repositories;

use App\Model\Cars;
use Danganf\MyClass\Json\Contracts\JsonAbstract;
use Danganf\Repositories\Contracts\RepositoryAbstract;
use Illuminate\Database\Eloquent\Builder;

class CarsRepository extends RepositoryAbstract
{
    public function __construct()
    {
        parent::__construct( __CLASS__ );
    }

    /**
     * @param $filterArr
     * @return mixed
     */
    public function filter( $filterArr ){

        $querie = $this->getModel()
                    ->join('brands as b', function ($join) {
                        $join->on('cars.brand_id', '=', 'b.id');
                    })
                    ->join('models as m', function ($join) {
                        $join->on('cars.model_id', '=', 'm.id');
                    });

        $selectRaw = 'cars.*';
        $selectRaw .= ', b.type_name, b.name as brand_name';
        $selectRaw .= ', m.name as model_name, m.name as model_name';

        $this->prepareFilter( $querie, $filterArr );

        return $querie->selectRaw($selectRaw)
                    ->orderByRaw('b.order, cars.name')
                    ->paginate( (int) array_get( $filterArr, 'total_register', 5 ) )
                    ->toArray();
    }

    /**
     * TRATANDO FILTROS RECEBIDOS PELO REQUEST
     * @param Builder $querie
     * @param $filterArr
     */
    private function prepareFilter( Builder &$querie, $filterArr ){
        $where = "b.type_name='".$filterArr['type']."'";

        if( !empty( array_get( $filterArr, 'brand' ) ) )
            $where .= " AND b.name='".$filterArr['brand']."'";

        if( !empty( array_get( $filterArr, 'model' ) ) )
            $where .= " AND m.name='".$filterArr['model']."'";

        if( !empty( array_get( $filterArr, 'exchange_type' ) ) )
            $where .= " AND cars.exchange_type='".$filterArr['exchange_type']."'";

        if( !empty( array_get( $filterArr, 'year_of' ) ) )
            $where .= " AND cars.year_of_manufacture LIKE '".$filterArr['year_of']."-%'";

        if( !empty( array_get( $filterArr, 'year_until' ) ) )
            $where .= " AND cars.year_of_manufacture LIKE '%-".$filterArr['year_until']."'";

        $querie->whereRaw( $where );
    }

    /**
     * @param JsonAbstract $jsonValues
     * @param null $id
     */
    public function createOrUpdate(JsonAbstract $jsonValues, $id=null)
    {
        foreach ( $jsonValues->toArray() as $row ){

            $resultModel = $this->getModel()->models()->getRelated()
                            ->selectRaw('id')->where('brand_id', $row['brand_id'])->where('name', $row['model'])
                            ->first();

            if( $resultModel ){
                $model                      = new Cars();
                $model->brand_id            = $row['brand_id'];
                $model->model_id            = $resultModel->id;
                $model->sku                 = $row['sku'];
                $model->bodyType            = $row['bodyType'];
                $model->name                = $row['name'];
                $model->description         = $row['description'];
                $model->mileage             = $row['mileageFromOdometer'];
                $model->price               = $row['price'];
                $model->price_valid_until   = $row['priceValidUntil'];
                $model->availability        = $row['availability'];
                $model->subtitle            = $row['card-subtitle'];
                $model->year_of_manufacture = array_get($row,'Ano de fabricação');
                $model->exchange_type       = array_get($row, 'Tipo de câmbio');
                $model->accessories         = array_get($row,'accessories');
                $model->image               = $row['image'];
                $model->url                 = $row['url'];

                // TRY/CATCH APENAS PARA O CASO DE RODAR MAIS DE UMA VEZ E JA EXISTIR A MARCA CADASTRADA
                // DEVIDO AO TEMPO DO TEST, NAO IREI FAZER UMA VERIFICACAO SE EXISTI O REGISTRO ANTES DE SALVAR
                try {
                    $model->save();
                } catch (\Exception $e){

                }
            }

        }
    }
}
