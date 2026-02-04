<?php

use Illuminate\Support\Facades\Route;

// ...existing routes...

$router->get('/auth/registerTeacher', 'AuthController@registerTeacher');
$router->post('/auth/registerTeacher', 'AuthController@registerTeacher');

// ...existing routes...