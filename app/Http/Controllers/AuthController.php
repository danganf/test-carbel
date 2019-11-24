<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Carbon\Carbon;
use Danganf\MyClass\MyCript;
use Danganf\MyClass\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function auth( Request $request, Validator $validator, UserRepository $userRepository, MyCript $myCript ){

        $login    = $request->header('x-auth-login');
        $password = $request->header('x-auth-password');
        $msgError = null;

        if ( $validator->valid( ['login' => $login, 'password' => $password], [ 'login', 'password' ] ) ) {
            if( $userRepository->auth( $login, $password ) ) {
                return msgJson([
                    'sucess' => true,
                    'token' => [
                        'label' => config('app.label_header_auth'),
                        'value' => $myCript->encode( config('app.key_crypt'),
                                                     json_encode( [ 'login' => $login, 'time' => Carbon::now()->toDateTimeString() ] )
                                                   )
                    ]
                ]);
            } else {
                $msgError = \Lang::get('auth.failed');
            }
        } else {
            $msgError = $validator->error();
        }

        return msgErroJson($msgError, 401);

    }
}
