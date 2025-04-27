<?php

namespace App\Controllers;
use App\Services\Mailer\Message;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @property Message $message
 */
class HomeController extends BaseController
{

    /**+
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {

        try {

            $this->message
                ->setTo('hello', 'Fabrizio')
                ->setReplyTo('hello@fabrizio.app')
                ->setCC(['user@example.com'])
                ->setBcc(['user2@example.com', 'user3@example.com'])
                ->setSubject('Grazie per averci contattato')
                ->setContentType('text')
                ->setPayload('Questo è un nuovo messaggio')
                ->send();

        } catch (\Exception $e) {

            $this->loggerService->getLogger()->error($e->getMessage());

        }

        return $this->view->render($response, '/admin/index.twig', [
            'name' => '',
        ]);

    }

}