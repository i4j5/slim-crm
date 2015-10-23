<?php

namespace Http\Middleware;


class Auth {

    public function __invoke($request, $response, $next) {

        if ( !\Auth::instance()->logged_in() ) {
            return $response->withRedirect('/login');
        }

        $response = $next($request, $response);


        return $response;
    }

}