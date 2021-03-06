<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class Message extends ValidatorFactory
{
    /** @var int */
    protected $maxBodyLength = 0;

    /**
     * @param int $maxBodyLength
     *
     * @return $this
     */
    public function setMaxBodyLength(int $maxBodyLength): Message
    {
        $this->maxBodyLength = $maxBodyLength;

        return $this;
    }

    /**
     * @return IValidator
     */
    public function createValidator(): IValidator
    {
        $validator = parent::createValidator();

        $validator
            ->field('from_name')
            ->required();

        $validator
            ->field('from_email')
            ->email()
            ->required();

        $validator
            ->field('subject')
            ->required();

        $validator
            ->field('body')
            ->required();

        if ($this->maxBodyLength > 0) {
            $validator
                ->field('body')
                ->maxLength($this->maxBodyLength);
        }

        return $validator;
    }
}
