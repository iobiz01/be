<?php

namespace App\Controllers;
use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TestController extends BaseController
{

    public function index(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {

        $users = User::getUsers();
        dump($users);


        return $response;

    }

}