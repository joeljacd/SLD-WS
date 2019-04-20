<?php 


// $router->group(['prefix' => 'api/v1'],function() use ($router){
    $router->group(['namespace' => 'Seguridad'],function() use ($router){
        $router->get('logout','LoginController@logout');
        $router->post('login','LoginController@login');
    });
// });