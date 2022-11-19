<?php

namespace Tests\Feature;

use Illuminate\Routing\Route;

trait TraitRoute
{

    public function gerRoute(string $nameRoute):string {

        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $url = $routes->getByName($nameRoute);

        return $url->uri;
    }

}
