<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class Form extends ValidatorFactory
{
    /**
     * @return IValidator
     */
    public function createValidator(): IValidator
    {
        $validator = parent::createValidator();

        $validator
            ->field('id')
            ->uuid();

        $validator
            ->field('name')
            ->required();

        $validator
            ->field('to_name')
            ->required();

        $validator
            ->field('to_email')
            ->email()
            ->required();

        return $validator;
    }
}
