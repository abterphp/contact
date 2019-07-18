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

        if ($this->maxBodyLength) {
            $validator
                ->field('body')
                ->max($this->maxBodyLength)
                ->required();
        } else {
            $validator
                ->field('body')
                ->required();
        }

        return $validator;
    }
}
