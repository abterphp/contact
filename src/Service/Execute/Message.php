<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Service\Execute;

use AbterPhp\Contact\Constant\Event;
use AbterPhp\Contact\Domain\Entities\Form;
use AbterPhp\Contact\Domain\Entities\Message as Entity;
use AbterPhp\Contact\Orm\FormRepo;
use AbterPhp\Contact\Validation\Factory\Message as ValidatorFactory;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Email\Sender;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\OrmException;

class Message
{
    const PHONE_NUMBER = 'contact:phoneNumber';
    const NO_PHONE     = 'contact:noPhoneNumber';

    /** @var ValidatorFactory */
    protected $validatorFactory;

    /** @var FormRepo */
    protected $formRepo;

    /** @var Sender */
    protected $sender;

    /** @var IEventDispatcher */
    protected $eventDispatcher;

    /** @var Form[] */
    protected $forms = [];

    /** @var ITranslator */
    protected $translator;

    /**
     * Message constructor.
     *
     * @param FormRepo         $formRepo
     * @param ValidatorFactory $validatorFactory
     * @param IEventDispatcher $eventDispatcher
     * @param Sender           $sender
     * @param ITranslator      $translator
     */
    public function __construct(
        FormRepo $formRepo,
        ValidatorFactory $validatorFactory,
        IEventDispatcher $eventDispatcher,
        Sender $sender,
        ITranslator $translator
    ) {
        $this->formRepo         = $formRepo;
        $this->validatorFactory = $validatorFactory;
        $this->eventDispatcher  = $eventDispatcher;
        $this->sender           = $sender;
        $this->translator       = $translator;
    }

    /**
     * @param string $formIdentifier
     *
     * @return Form|null
     * @throws \Opulence\Orm\OrmException
     */
    public function getForm(string $formIdentifier): ?Form
    {
        if (array_key_exists($formIdentifier, $this->forms)) {
            return $this->forms[$formIdentifier];
        }

        try {
            $form = $this->formRepo->getByIdentifier($formIdentifier);
        } catch (OrmException $e) {
            try {
                $form = $this->formRepo->getById($formIdentifier);
            } catch (OrmException $e) {
                return null;
            }
        }

        $this->forms[$form->getIdentifier()] = $form;

        return $form;
    }

    /**
     * @param Entity $message
     *
     * @return int
     */
    public function send(Entity $message): int
    {
        $this->eventDispatcher->dispatch(Event::MESSAGE_READY, $message);

        $form       = $message->getForm();
        $recipients = [$form->getToEmail() => $form->getToName()];

        $replyToAddresses = [$message->getFromEmail() => $message->getFromName()];
        $fromAddresses    = $replyToAddresses;

        $result = $this->sender->send(
            $message->getSubject(),
            $message->getBody(),
            $recipients,
            $fromAddresses,
            $replyToAddresses
        );

        $this->eventDispatcher->dispatch(Event::MESSAGE_SENT, $message);

        return $result;
    }

    /**
     * @return array
     */
    public function getFailedRecipients(): array
    {
        return $this->sender->getFailedRecipients();
    }

    /**
     * @param string $formIdentifier
     * @param array  $postData
     *
     * @return array errors
     * @throws OrmException
     */
    public function validateForm(string $formIdentifier, array $postData): array
    {
        /** @var Form $form */
        $form = $this->getForm($formIdentifier);
        if (null === $form) {
            throw new \InvalidArgumentException();
        }

        $validator = $this->validatorFactory
            ->setMaxBodyLength($form->getMaxBodyLength())
            ->createValidator();

        if ($validator->isValid($postData)) {
            return [];
        }

        return $validator->getErrors()->getAll();
    }

    /**
     * @param string $entityId
     *
     * @return Entity|IStringerEntity
     */
    public function createEntity(string $entityId): IStringerEntity
    {
        $form = new Form('', '', '', '', '', '', '', 0);

        $message = new Entity($entityId, $form, '', '', '', '');

        return $message;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string          $formIdentifier
     * @param IStringerEntity $entity
     * @param array           $postData
     * @param array           $fileData
     *
     * @return IStringerEntity|Entity
     */
    public function fillEntity(
        string $formIdentifier,
        IStringerEntity $entity,
        array $postData,
        array $fileData
    ): IStringerEntity {
        assert($entity instanceof Entity, new \InvalidArgumentException('Invalid entity'));

        $form = $this->getForm($formIdentifier);
        if (null === $form) {
            return $entity;
        }

        $subject = $postData['subject'];

        $body = $postData['body'] . PHP_EOL . PHP_EOL . $this->translator->translate(static::PHONE_NUMBER) . ': ';
        $body .= $postData['from_phone'] ? $postData['from_phone'] : $this->translator->translate(static::NO_PHONE);

        $name  = $postData['from_name'];
        $email = $postData['from_email'];

        $entity
            ->setForm($form)
            ->setSubject($subject)
            ->setBody($body)
            ->setFromName($name)
            ->setFromEmail($email);

        return $entity;
    }
}
