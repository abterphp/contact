<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Http\Controllers\Api;

use AbterPhp\Contact\Service\Execute\Message as MessageService;
use AbterPhp\Framework\Config\Provider as ConfigProvider;
use AbterPhp\Framework\Http\Controllers\ApiDataTrait;
use AbterPhp\Framework\Http\Controllers\ApiIssueTrait;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Routing\Controller;
use Psr\Log\LoggerInterface;

class Message extends Controller
{
    use ApiIssueTrait;
    use ApiDataTrait;

    const ENTITY_SINGULAR = 'message';
    const ENTITY_PLURAL   = 'messages';

    const LOG_MSG_CREATE_FAILURE = 'Creating %1$s failed.';

    const LOG_CONTEXT_EXCEPTION  = 'Exception';
    const LOG_PREVIOUS_EXCEPTION = 'Previous exception #%d';

    /** @var LoggerInterface */
    protected $logger;

    /** @var MessageService */
    protected $messageService;

    /**
     * Message constructor.
     *
     * @param LoggerInterface $logger
     * @param MessageService  $messageService
     * @param ConfigProvider  $configProvider
     */
    public function __construct(
        LoggerInterface $logger,
        MessageService $messageService,
        ConfigProvider $configProvider
    ) {
        $this->logger         = $logger;
        $this->messageService = $messageService;
        $this->problemBaseUrl = $configProvider->getProblemBaseUrl();
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        try {
            $data = $this->getCreateData();

            $formIdentifier = $data['form_id'];

            $errors = $this->messageService->validateForm($formIdentifier, $data);

            if (count($errors) > 0) {
                $msg = sprintf(static::LOG_MSG_CREATE_FAILURE, static::ENTITY_SINGULAR);

                return $this->handleErrors($msg, $errors);
            }

            $entity = $this->messageService->createEntity('');
            $entity = $this->messageService->fillEntity($formIdentifier, $entity, $data, []);

            $entity = $this->messageService->send($entity);
        } catch (\Exception $e) {
            $msg = sprintf(static::LOG_MSG_CREATE_FAILURE, static::ENTITY_SINGULAR);

            return $this->handleException($msg, $e);
        }

        return $this->handleCreateSuccess($entity);
    }

    /**
     * @return Response
     */
    protected function handleCreateSuccess(): Response
    {
        $response = new Response();
        $response->setStatusCode(ResponseHeaders::HTTP_NO_CONTENT);

        return $response;
    }

    /**
     * @param string $entityId
     *
     * @return Response
     */
    public function get(string $entityId): Response
    {
        return $this->handleNotImplemented();
    }

    /**
     * @return Response
     */
    public function list(): Response
    {
        return $this->handleNotImplemented();
    }

    /**
     * @param string $entityId
     *
     * @return Response
     */
    public function update(string $entityId): Response
    {
        return $this->handleNotImplemented();
    }

    /**
     * @param string $entityId
     *
     * @return Response
     */
    public function delete(string $entityId): Response
    {
        return $this->handleNotImplemented();
    }
}
