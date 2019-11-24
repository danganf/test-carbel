<?php

namespace App\Http\Controllers;

use App\Repositories\BrandsRepository;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
    public function filter( $id=null, Request $request, BrandsRepository $brandsRepository ){

        $data = [];
        if( !empty( $id ) ){
            $data = $brandsRepository->find( $id )->toArray();
        } else {
            $data = $brandsRepository->filter();
        }

        return msgJson( $data );
    }
}
