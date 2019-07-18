<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Http\Controllers\Website;

use AbterPhp\Contact\Domain\Entities\Form;
use AbterPhp\Contact\Domain\Entities\Message;
use AbterPhp\Contact\Service\Execute\Message as MessageService;
use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Routing\Controller;
use Psr\Log\LoggerInterface;

class Contact extends Controller
{
    const LOG_MSG_VALIDATION_ERROR = 'Validating contact message failed.';
    const LOG_MSG_SENDING_ERROR    = 'Sending contact message to %s failed.';

    /** @var MessageService */
    protected $messageService;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * Contact constructor.
     *
     * @param MessageService $service
     */
    public function __construct(MessageService $service, LoggerInterface $logger)
    {
        $this->messageService = $service;
        $this->logger         = $logger;
    }

    /**
     * @return Response
     */
    public function submit(string $formIdentifier): Response
    {
        $postData = $this->request->getPost()->getAll();

        $errors = $this->messageService->validateForm($formIdentifier, $postData);

        if ($errors) {
            $this->logger->info(static::LOG_MSG_VALIDATION_ERROR, $errors);

            $url = $this->messageService->getForm($formIdentifier)->getFailureUrl();

            return $this->redirectTo($url);
        }

        /** @var Message $message */
        $message = $this->messageService->createEntity('');
        $message = $this->messageService->fillEntity($formIdentifier, $message, $postData, []);

        $url = $message->getForm()->getSuccessUrl();
        if ($this->messageService->send($message) < 1) {
            $url = $form->getFailureUrl();
        }

        foreach ($this->messageService->getFailedRecipients() as $recipient) {
            $this->logger->info(sprintf(static::LOG_MSG_SENDING_ERROR, $recipient), $errors);
        }

        return $this->redirectTo($url);
    }

    /**
     * @param string $url
     *
     * @return Response
     */
    private function redirectTo(string $url): Response
    {
        $response = new RedirectResponse($url);
        $response->send();

        return $response;
    }
}
