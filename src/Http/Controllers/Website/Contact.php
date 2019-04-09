<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Http\Controllers\Website;

use AbterPhp\Contact\Service\Execute\Contact as ContactService;
use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Routing\Controller;
use Psr\Log\LoggerInterface;

class Contact extends Controller
{
    const LOG_MSG_VALIDATION_ERROR = 'Validating contact message failed.';
    const LOG_MSG_SENDING_ERROR    = 'Sending contact message to %s failed.';

    /** @var ContactService */
    protected $contactService;

    /** @var LoggerInterface */
    protected $logger;

    /** @var string */
    protected $successUrl;

    /** @var string */
    protected $errorUrl;

    /**
     * Contact constructor.
     *
     * @param ContactService $service
     */
    public function __construct(ContactService $service, LoggerInterface $logger, string $successUrl, string $errorUrl)
    {
        $this->contactService = $service;
        $this->logger         = $logger;
        $this->successUrl     = $successUrl;
        $this->errorUrl       = $errorUrl;
    }

    /**
     * @return Response
     */
    public function send(): Response
    {
        $postData = $this->request->getPost()->getAll();

        $errors = $this->contactService->validateForm($postData);

        if ($errors) {
            $this->logger->info(static::LOG_MSG_VALIDATION_ERROR, $errors);

            return $this->redirectTo($this->errorUrl);
        }

        $url = $this->successUrl;
        if ($this->contactService->send($postData) < 1) {
            $url = $this->errorUrl;
        }

        foreach ($this->contactService->getFailedRecipients() as $recipient) {
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
