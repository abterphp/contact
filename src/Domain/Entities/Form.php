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

    /**
     * @param string $id
     * @param string $name
     * @param string $identifier
     * @param string $toName
     * @param string $toEmail
     */
    public function __construct(string $id, string $name, string $identifier, string $toName, string $toEmail)
    {
        $this->id         = $id;
        $this->name       = $name;
        $this->identifier = $identifier;
        $this->toName     = $toName;
        $this->toEmail    = $toEmail;
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
    public function __toString(): string
    {
        return $this->getIdentifier();
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode(
            [
                'id'         => $this->getId(),
                'name'       => $this->getName(),
                'identifier' => $this->getIdentifier(),
                'to_name'    => $this->getToName(),
                'to_email'   => $this->getToEmail(),
            ]
        );
    }
}
