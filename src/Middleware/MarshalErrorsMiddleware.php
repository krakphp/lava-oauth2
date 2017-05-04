<?php

namespace Krak\LavaOAuth2\Middleware;

use function iter\reduce;

class MarshalErrorsMiddleware
{
    private $mw;

    public function __construct($mw) {
        $this->mw = $mw;
    }

    public function handle($req, $next) {
        $should_marshal_error = true;
        $next = $next->chain(function($req, $next) use (&$should_marshal_error) {
            $should_marshal_error = false;
            return $next($req);
        });
        $next = $next->chain($this->mw);
        $resp = $next($req);

        if (!$should_marshal_error) {
            return $resp;
        }

        if ($resp->getStatusCode() < 400) {
            return $resp;
        }

        $body = (string) $resp->getBody();
        $body = json_decode($body, true);
        $code = $body['error'];
        $message = $body['message'];
        unset($body['error'], $body['message']);
        $new_resp = $next->abort($resp->getStatusCode(), $code, $message, $body)->render($req);

        return reduce(function($acc, $header, $key) {
            return $acc->withHeader($key, $header);
        }, $resp->getHeaders(), $new_resp);
    }
}
