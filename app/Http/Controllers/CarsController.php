<?php

namespace App\Http\Controllers;

use App\Repositories\CarsRepository;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    public function detail( $sku, CarsRepository $carsRepository ){
        return msgJson( $carsRepository->findBy( 'sku', $sku )->toArray() );
    }

    public function filter( $type, Request $request, CarsRepository $carsRepository ){

        // ESSE undefined VEM SOMENTE NO SWAGGER... NAO TIVE TEMPO PRA VER PQ ESTAVA SENDO ENVIADO QDO
        // NAO PASSAVA OS PARAMENTROS... COLOQUEI O REPLACE PRA RESOLVER, MAS SEI Q TERIA Q VOLTAR E ARRUMAR
        // DE UMA FORMA MELHOR E DEFINITIVA

        $brand  = str_replace('undefined','', $request->route('brand'));
        $model  = str_replace('undefined','', $request->route('model'));

        $filter = array_add( $request->all(), 'type' , $type );
        if( !empty( $brand ) ) {
            $filter = array_add($filter, 'brand', $brand);
        }
        if( !empty( $model ) ) {
            $filter = array_add($filter, 'model', $model);
        }

        return msgJson( $carsRepository->filter( $filter ) );
    }
}
