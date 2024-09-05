<?php

namespace App\Http\Middleware;

use App\Models\AuthApiKey\ApiKey;
use App\Models\AuthApiKey\ApiKeyAccessEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizeApiKey
{
    const AUTH_HEADER = 'X-API-KEY';

    /**
     * Handle the incoming request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header(self::AUTH_HEADER);
        $apiKey = ApiKey::getByKey($header);

        if ($apiKey instanceof ApiKey) {
            Auth::login($apiKey);
            $this->logAccessEvent($request, $apiKey);

            return $next($request);
        }

        return response([
            'status' => 401,
            'message' => [
                'error' => 'Unauthorized. ',
            ],
        ], 401);
    }

    /**
     * Log an API key access event
     */
    protected function logAccessEvent(Request $request, ApiKey $apiKey)
    {
        $event = new ApiKeyAccessEvent;
        $event->api_key_id = $apiKey->id;
        $event->ip_address = $request->ip();
        $event->url = $request->fullUrl();
        $event->save();
    }
}
