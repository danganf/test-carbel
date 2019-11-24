<?php
namespace App\Http\Middleware;

use Closure;
use Danganf\MyClass\JsonBasic;
use Illuminate\Support\Facades\App;

class ValidAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $message      = \Lang::get('auth.token_failed');
        $sessionToken = $request->header(config('app.label_header_auth'));

        if ( $sessionToken) {

            $myCript        = App::make("\Danganf\MyClass\MyCript");
            $jsonSession    = $myCript->decode(config('app.key_crypt'),$sessionToken);
            $sessionDecript = json_decode( $jsonSession ,true);

            // VERIFICANDO SE O TOKEN ERA VALIDO
            if ( is_array( $sessionDecript ) ) {
                return $next($request);
            }
        }

        return msgErroJson($message);

    }
}
