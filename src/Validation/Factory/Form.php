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
            ->forbidden();

        $validator
            ->field('identifier');

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

        $validator
            ->field('success_url')
            ->url()
            ->required();

        $validator
            ->field('failure_url')
            ->url()
            ->required();

        $validator
            ->field('max_body_length')
            ->numeric()
            ->required();

        return $validator;
    }
}
