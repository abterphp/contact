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
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\OrmException;

class Message
{
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

    /**
     * Message constructor.
     *
     * @param ValidatorFactory $validatorFactory
     * @param FormRepo         $formRepo
     * @param Sender           $sender
     * @param IEventDispatcher $eventDispatcher
     */
    public function __construct(
        ValidatorFactory $validatorFactory,
        FormRepo $formRepo,
        Sender $sender,
        IEventDispatcher $eventDispatcher
    ) {
        $this->validatorFactory = $validatorFactory;
        $this->formRepo         = $formRepo;
        $this->sender           = $sender;
        $this->eventDispatcher  = $eventDispatcher;
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
            return $this->form[$formIdentifier];
        }

        try {
            $form = $this->formRepo->getByIdentifier($formIdentifier);
        } catch (OrmException $e) {
            try {
                $form = $this->formRepo->getById($formIdentifier);
            } catch (OrmException $e) {
                $this->form[$formIdentifier] = null;

                return null;
            }
        }

        $this->form[$form->getId()]         = $form;
        $this->form[$form->getIdentifier()] = $form;

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
     */
    public function validateForm(string $formIdentifier, array $postData): array
    {
        /** @var Form $form */
        $form = $this->getForm($formIdentifier);
        if (null === $form) {
            return [];
        }

        $validator = $this->validatorFactory->setMaxBodyLength($form->getMaxBodyLength())->createValidator();

        if ($validator->isValid($postData)) {
            return [];
        }

        return $validator->getErrors()->getAll();
    }

    /**
     * @param string $entityId
     *
     * @return Entity
     */
    public function createEntity(string $entityId): IStringerEntity
    {
        $form = new Form('', '', '', '', '', '', '', 0);

        $message = new Entity($entityId, $form, '', '', '', '');

        return $message;
    }

    /**
     * @param string $formIdentifier
     * @param Entity $entity
     * @param array  $postData
     * @param array  $fileData
     *
     * @return IStringerEntity
     */
    public function fillEntity(
        string $formIdentifier,
        IStringerEntity $entity,
        array $postData,
        array $fileData
    ): IStringerEntity {
        $form = $this->getForm($formIdentifier);
        if (null === $form) {
            return $entity;
        }

        $entity
            ->setForm($form)
            ->setSubject($postData['subject'])
            ->setBody($postData['body'])
            ->setFromName($postData['from_name'])
            ->setFromEmail($postData['from_email']);

        return $entity;
    }
}
