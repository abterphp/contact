<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Bootstrappers\Http\Controllers\Website;

use AbterPhp\Contact\Constant\Env;
use AbterPhp\Contact\Http\Controllers\Website\Contact as ContactController;
use AbterPhp\Contact\Service\Execute\Contact as ContactService;
use AbterPhp\Contact\Validation\Factory\Contact as ValidatorFactory;
use AbterPhp\Framework\Email\Sender;
use AbterPhp\Framework\Exception\Config;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Routing\Urls\UrlException;
use Opulence\Routing\Urls\UrlGenerator;
use Psr\Log\LoggerInterface;

class ContactBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @return array
     */
    public function getBindings(): array
    {
        return [
            ContactController::class,
            ContactService::class,
        ];
    }

    /**
     * @param IContainer $container
     *
     * @throws \Opulence\Ioc\IocException
     */
    public function registerBindings(IContainer $container)
    {
        $service    = $this->createService($container);
        $controller = $this->createController($container, $service);

        $container->bindInstance(ContactService::class, $service);
        $container->bindInstance(ContactController::class, $controller);
    }

    /**
     * @param IContainer $container
     *
     * @return ContactService
     * @throws \Opulence\Ioc\IocException
     */
    public function createService(IContainer $container): ContactService
    {
        $senderEmail    = getenv(Env::DEFAULT_SENDER_EMAIL);
        $senderName     = getenv(Env::DEFAULT_SENDER_NAME);
        $recipientEmail = getenv(Env::DEFAULT_RECIPIENT_EMAIL);
        $recipientName  = getenv(Env::DEFAULT_RECIPIENT_NAME);

        if (!$senderEmail || !$senderName || !$recipientEmail || !$recipientName) {
            throw new Config(
                ContactService::class,
                [
                    Env::DEFAULT_SENDER_EMAIL,
                    Env::DEFAULT_SENDER_NAME,
                    Env::DEFAULT_RECIPIENT_EMAIL,
                    Env::DEFAULT_RECIPIENT_NAME,
                ]
            );
        }

        /** @var Sender $mailer */
        $mailer = $container->resolve(Sender::class);

        /** @var ValidatorFactory $validatorFactory */
        $validatorFactory = $container->resolve(ValidatorFactory::class);

        $senders    = [$senderEmail => $senderName];
        $recipients = [$recipientEmail => $recipientName];

        return new ContactService($validatorFactory, $mailer, $recipients, $senders);
    }

    /**
     * @param IContainer     $container
     * @param ContactService $service
     *
     * @return ContactController
     * @throws \Opulence\Ioc\IocException
     */
    public function createController(IContainer $container, ContactService $service): ContactController
    {
        /** @var LoggerInterface $logger */
        $logger = $container->resolve(LoggerInterface::class);

        $successPage = getenv(Env::DEFAULT_SUCCESS_PAGE);
        $errorPage   = getenv(Env::DEFAULT_ERROR_PAGE);

        if (!$successPage || !$errorPage) {
            throw new Config(ContactController::class, [Env::DEFAULT_SUCCESS_PAGE, Env::DEFAULT_ERROR_PAGE]);
        }

        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $container->resolve(UrlGenerator::class);

        try {
            $successUrl = $urlGenerator->createFromName($successPage) ?: $successPage;
        } catch (UrlException $e) {
            $successUrl = $successPage;
        }
        try {
            $errorUrl = $urlGenerator->createFromName($errorPage) ?: $errorPage;
        } catch (UrlException $e) {
            $errorUrl = $errorPage;
        }

        return new ContactController($service, $logger, $successUrl, $errorUrl);
    }
}
