<?php

namespace App\Http\Controllers;

use App\Repositories\ModelsRepository;
use Illuminate\Http\Request;

class ModelsController extends Controller
{
    public function filter( $id=null, Request $request, ModelsRepository $modelsRepository ){

        $data = [];
        if( !empty( $id ) ){
            $data = $modelsRepository->find( $id )->toArray();
        } else {
            $data = $modelsRepository->filter( $request->all() );
        }

        return msgJson( $data );
    }
}
