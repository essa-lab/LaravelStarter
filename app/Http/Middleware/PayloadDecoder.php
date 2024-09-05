<?php

namespace App\Http\Middleware;

use Closure;
use \App\Http\Controllers\AESController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class PayloadDecoder
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
        if($request->isMethod('post') || $request->isMethod('put')) {
            if(preg_match('/multipart/',$request->headers->get('Content-Type')) ){
                Log::info($request);
                return $next($request);
            }
            if (strpos(Route::getCurrentRoute()->uri(),'/api/v1/chat/image')){
                return $next($request);
            }
            if (strpos(Route::getCurrentRoute()->uri(),'/api/v1/user/updateProfilePicture')){
                return $next($request);
            }
            if (strpos(Route::getCurrentRoute()->uri(),'/api/v1/user/updateProfileCover')){
                return $next($request);
            }
            if (strpos(Route::getCurrentRoute()->uri(),'/api/v1/post')){
                return $next($request);
            }
            if (strpos(Route::getCurrentRoute()->uri(),'/api/v1/shop')){
                return $next($request);
            }
            if (isset($request['payload'])) {
                $z = "abcdefghijuklmno0123456789012345";
                $aes = new AESController();
                $data = json_decode($aes::decrypt($request['payload'],$z));
                foreach ($request->all() as $key=>$value){
                    unset($request[$key]);
                }
                if (is_array($data) || is_object($data)) {
                    foreach ($data as $key => $value) {
                        //error_log($key.":".$value);
                        $request->merge(array($key => $value));
                    }
                }
                return $next($request);
            }else{
                throw new \Exception("Invalid payload");
            }
        }else {
            return $next($request);
        }
    }

}
