<?php

$app->group('', function () {
    $this->get('/auth[/login]', 'App\Controllers\Auth\AuthController:login')->setName('auth.login');
    $this->post('/auth/logar', 'App\Controllers\Auth\AuthController:logar')->setName('auth.logar');
})->add(App\Middleware\GuestMiddleware::class);
$app->group('', function (){
    $this->get('/auth/password/change', '\App\Controllers\Auth\PasswordController:getChangePassword')->setName('auth.password.change');
    $this->post('/auth/password/change', '\App\Controllers\Auth\PasswordController:postChangePassword');
    $this->get('/auth/logout', 'App\Controllers\Auth\AuthController:logout')->setName('auth.logout');
})->add(\App\Middleware\AuthMiddleware::class);

$app->group('', function (){
    $this->get('/', '\App\Controllers\HomeController:index')->setName('home');
})->add(\App\Middleware\AuthMiddleware::class);

$app->group('/services', function (){
    $this->get('[/]', '\App\Controllers\ServicesController:index')->setName('services');
    $this->get('/add', '\App\Controllers\ServicesController:formService')->setName('services.form');
    $this->get('/edit/{id:\d+}', '\App\Controllers\ServicesController:formService');
    $this->post('/create', '\App\Controllers\ServicesController:create');
    $this->get('/read', '\App\Controllers\ServicesController:read');
    $this->post('/update', '\App\Controllers\ServicesController:update');
    $this->get('/delete/{id:\d+}', '\App\Controllers\ServicesController:delete');
    $this->get('/order/{id:\d+}', '\App\Controllers\ServicesController:getService');

    $this->get('/categories', '\App\Controllers\ServicesCatController:index')->setName('services.categories');
    $this->get('/categories/add', '\App\Controllers\ServicesCatController:formCategory');
    $this->get('/categories/edit/{id:\d+}', '\App\Controllers\ServicesCatController:formCategory');
    $this->post('/categories/create', '\App\Controllers\ServicesCatController:create');
    $this->post('/categories/update', '\App\Controllers\ServicesCatController:update');
    $this->get('/categories/delete/{id:\d+}', '\App\Controllers\ServicesCatController:delete');
})->add(\App\Middleware\AuthMiddleware::class);

$app->group('/customers', function (){
    $this->get('[/]', '\App\Controllers\CustomersController:index')->setName('customers');
    $this->get('/add', '\App\Controllers\CustomersController:formCustomer');
    $this->get('/edit/{id:\d+}', '\App\Controllers\CustomersController:formCustomer');
    $this->post('/create', '\App\Controllers\CustomersController:create');
    $this->get('/read[/{id:\d+}]', '\App\Controllers\CustomersController:read');
    $this->post('/update', '\App\Controllers\CustomersController:update');
    $this->get('/delete/{id:\d+}', '\App\Controllers\CustomersController:delete');
})->add(\App\Middleware\AuthMiddleware::class);

$app->group('/settings', function (){
    $this->get('[/]', '\App\Controllers\HomeController:index')->setName('settings');
})->add(\App\Middleware\AuthMiddleware::class);
