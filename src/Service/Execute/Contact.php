<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Service\Execute;

use AbterPhp\Contact\Validation\Factory\Contact as ValidatorFactory;
use AbterPhp\Framework\Email\Sender;
use Opulence\Validation\IValidator;

class Contact
{
    /** @var ValidatorFactory */
    protected $validatorFactory;

    /** @var Sender */
    protected $mailer;

    /** @var string[] */
    protected $recipients;

    /** @var string[] */
    protected $senders;

    /** @var IValidator */
    protected $validator;

    /**
     * Contact constructor
     *
     * @param ValidatorFactory $validatorFactory
     * @param Sender           $mailer
     * @param string[]         $recipients
     * @param string[]         $senders
     */
    public function __construct(ValidatorFactory $validatorFactory, Sender $mailer, array $recipients, array $senders)
    {
        $this->validatorFactory = $validatorFactory;
        $this->mailer           = $mailer;
        $this->recipients       = $recipients;
        $this->senders          = $senders;
    }

    /**
     * @param array $postData
     *
     * @return int
     */
    public function send(array $postData): int
    {
        $replyTo = [$postData['from-email'] => $postData['from-name']];

        return $this->mailer->send(
            $postData['subject'],
            $postData['body'],
            $this->recipients,
            $this->senders,
            $replyTo
        );
    }

    /**
     * @return array
     */
    public function getFailedRecipients(): array
    {
        return $this->mailer->getFailedRecipients();
    }

    /**
     * @param array $postData
     *
     * @return array
     */
    public function validateForm(array $postData): array
    {
        if ($this->getValidator()->isValid($postData)) {
            return [];
        }

        return $this->validator->getErrors()->getAll();
    }

    /**
     * @return IValidator
     */
    protected function getValidator(): IValidator
    {
        if ($this->validator) {
            return $this->validator;
        }

        $this->validator = $this->validatorFactory->createValidator();

        return $this->validator;
    }
}
