<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class Contact extends ValidatorFactory
{
    /**
     * @return IValidator
     */
    public function createValidator(): IValidator
    {
        $validator = parent::createValidator();

        $validator
            ->field('from-name')
            ->required();

        $validator
            ->field('from-email')
            ->email()
            ->required();

        $validator
            ->field('subject')
            ->required();

        $validator
            ->field('body')
            ->required();

        return $validator;
    }
}
