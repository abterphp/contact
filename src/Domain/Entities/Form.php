<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;

class Form implements IStringerEntity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $identifier;

    /** @var string */
    protected $toName;

    /** @var string */
    protected $toEmail;

    /** @var string */
    protected $successUrl;

    /** @var string */
    protected $failureUrl;

    /** @var int */
    protected $maxBodyLength;

    /**
     * @param string $id
     * @param string $name
     * @param string $identifier
     * @param string $toName
     * @param string $toEmail
     * @param string $successUrl
     * @param string $failureUrl
     * @param int    $maxBodyLength
     */
    public function __construct(
        string $id,
        string $name,
        string $identifier,
        string $toName,
        string $toEmail,
        string $successUrl,
        string $failureUrl,
        int $maxBodyLength
    ) {
        $this->id            = $id;
        $this->name          = $name;
        $this->identifier    = $identifier;
        $this->toName        = $toName;
        $this->toEmail       = $toEmail;
        $this->successUrl    = $successUrl;
        $this->failureUrl    = $failureUrl;
        $this->maxBodyLength = $maxBodyLength;
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
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): Form
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function setIdentifier(string $identifier): Form
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getToName(): string
    {
        return $this->toName;
    }

    /**
     * @param string $toName
     *
     * @return $this
     */
    public function setToName(string $toName): Form
    {
        $this->toName = $toName;

        return $this;
    }

    /**
     * @return string
     */
    public function getToEmail(): string
    {
        return $this->toEmail;
    }

    /**
     * @param string $toEmail
     *
     * @return $this
     */
    public function setToEmail(string $toEmail): Form
    {
        $this->toEmail = $toEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getSuccessUrl(): string
    {
        return $this->successUrl;
    }

    /**
     * @param string $successUrl
     *
     * @return $this
     */
    public function setSuccessUrl(string $successUrl): Form
    {
        $this->successUrl = $successUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getFailureUrl(): string
    {
        return $this->failureUrl;
    }

    /**
     * @param string $failureUrl
     *
     * @return $this
     */
    public function setFailureUrl(string $failureUrl): Form
    {
        $this->failureUrl = $failureUrl;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxBodyLength(): int
    {
        return $this->maxBodyLength;
    }

    /**
     * @param int $maxBodyLength
     *
     * @return $this
     */
    public function setMaxBodyLength(int $maxBodyLength): Form
    {
        $this->maxBodyLength = $maxBodyLength;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getIdentifier();
    }

    /**
     * @return array|null
     */
    public function toData(): ?array
    {
        return [
            'id'              => $this->getId(),
            'name'            => $this->getName(),
            'identifier'      => $this->getIdentifier(),
            'to_name'         => $this->getToName(),
            'to_email'        => $this->getToEmail(),
            'success_url'     => $this->getSuccessUrl(),
            'failure_url'     => $this->getFailureUrl(),
            'max_body_length' => $this->getMaxBodyLength(),
        ];
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode($this->toData());
    }
}
