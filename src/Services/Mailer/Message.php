<?php

namespace App\Services\Mailer;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

class Message
{

    protected ContainerInterface $container;

    protected array $to = [];
    protected array $cc = [];
    protected array $bcc = [];
    protected string $replyTo;
    protected string $subject;
    protected string $contentType;
    protected string $templateHTML;
    protected string|array $payload;
    protected array $files = [];

    public function __construct(ContainerInterface $c)
    {
        $this->container = $c;
    }

    /**
     * Imposta il destinatario
     * @param string $to
     * @param string $name
     * @return $this
     */
    public function setTo(string $to, string $name): static
    {
        $this->to['address'] = $to;
        $this->to['name'] = $name;
        return $this;
    }

    /**
     * Aggiungi per copia
     * Set array ['user1@example.com','user2@example.com']
     * @param array $cc
     * @return $this
     */
    public function setCC(array $cc): static
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * Aggiungi copia per conoscenza nascosta
     * Set array ['user1@example.com','user2@example.com']
     * @param array $bcc
     * @return $this
     */
    public function setBcc(array $bcc): static
    {
        $this->bcc = $bcc;
        return $this;
    }

    /**
     * Aggiungi replyTo
     * @param string $replyTo
     * @return $this
     */
    public function setReplyTo(string $replyTo): static
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    /**
     * Aggiungi il soggetto della mail
     * @param string $subject
     * @return $this
     */
    public function setSubject(string $subject): static
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Definisci il formato del contenuto: text o html
     * @param string $contentType
     * @return $this
     * @throws Exception
     */
    public function setContentType(string $contentType): static
    {

        if($contentType === 'text' || $contentType === 'html') {

            $this->contentType = $contentType;
            return $this;

        }

        throw new Exception('Content type must be either text or html');

    }

    /**
     * Set template HTML
     * @param string $path
     * @return $this
     */
    public function setTemplateHTML(string $path): static
    {
        $this->templateHTML = $path;
        return $this;
    }

    /**
     * Aggiungi payload
     *
     * @param string|array $payload
     * @return $this
     */
    public function setPayload(string|array $payload): static
    {

        $this->payload = $payload;
        return $this;

    }

    /**
     * Aggiungi allegati
     * Set array...
     * @param array $files
     * @return $this
     */
    public function setFiles(array $files): static
    {
        $this->files = $files;
        return $this;
    }

    /**
     *
     * @throws Exception
     */
    private function render(): string
    {

        if($this->contentType === 'text') {

            if(is_string($this->payload)) {
                return $this->payload;
            }

            throw new Exception('Payload must be a string if you are using sending as text');

        }

        if($this->contentType === 'html') {

            if(!$this->templateHTML) {
                throw new Exception('HTML template is required for html content type');
            }

            if(!is_array($this->payload)) {
                throw new Exception('Payload must be array if you are using sending as HTML');
            }

            try {

                return $this->container->get('view')->fetch($this->templateHTML, $this->payload);

            } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {

                throw new Exception($e->getMessage());

            }

        }

        return '';

    }

    /**
     * Send Mail
     * @throws Exception
     */
    public function send(): void
    {

        if (empty($this->to) || empty($this->subject)) {
            throw new Exception("To and subject must be set before sending the message.");
        }

        $message = (new Email())
            ->from(new Address($_ENV['MAIL_FROM_ADDRESS'],$_ENV['MAIL_FROM_NAME']))
            ->to(new Address($this->to['address'], $this->to['name']))
            ->subject($this->subject);

        if($this->replyTo) {
            $message->replyTo($this->replyTo);
        }

        if($this->cc) {
            $message->cc(implode(',', $this->cc));
        }

        if($this->bcc) {
            $message->bcc(implode(',', $this->bcc));
        }

        if($this->contentType === 'html') {
            $message->html($this->render());
        }

        if($this->contentType === 'text') {
            $message->text($this->render());
        }

        if($this->files) {

            foreach ($this->files as $item) {
                $message->addPart(new DataPart(new File($item->file, $item->getClientFilename())));
            }

        }

        try {

            $this->container->get('mailer')->send($message);

        } catch (NotFoundExceptionInterface|ContainerExceptionInterface|TransportExceptionInterface $e) {

            throw new Exception($e->getMessage());

        }

    }

}