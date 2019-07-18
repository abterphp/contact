<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;

class Message implements IStringerEntity
{
    /** @var string */
    protected $id;

    /** @var Form */
    protected $form;

    /** @var string */
    protected $subject;

    /** @var string */
    protected $body;

    /** @var string */
    protected $fromName;

    /** @var string */
    protected $fromEmail;

    /**
     * @param string $id
     * @param Form   $form
     * @param string $subject
     * @param string $body
     * @param string $fromName
     * @param string $fromEmail
     */
    public function __construct(
        string $id,
        Form $form,
        string $subject,
        string $body,
        string $fromName,
        string $fromEmail
    ) {
        $this->id        = $id;
        $this->form      = $form;
        $this->subject   = $subject;
        $this->body      = $body;
        $this->fromName  = $fromName;
        $this->fromEmail = $fromEmail;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form): Message
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject(string $subject): Message
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody(string $body): Message
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * @param string $fromName
     *
     * @return $this
     */
    public function setFromName(string $fromName): Message
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * @param string $fromEmail
     *
     * @return $this
     */
    public function setFromEmail(string $fromEmail): Message
    {
        $this->fromEmail = $fromEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s - %s', $this->getFromName(), $this->getSubject());
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode(
            [
                'id'         => $this->getId(),
                'form'       => $this->getForm(),
                'subject'    => $this->getSubject(),
                'body'       => $this->getBody(),
                'from_name'  => $this->getFromName(),
                'from_email' => $this->getFromEmail(),
            ]
        );
    }
}
