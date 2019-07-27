<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Http\Controllers\Api;

use AbterPhp\Contact\Service\Execute\Form as RepoService;
use AbterPhp\Framework\Config\EnvReader;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Framework\Http\Controllers\ApiAbstract;
use Psr\Log\LoggerInterface;

class Form extends ApiAbstract
{
    const ENTITY_SINGULAR = 'form';
    const ENTITY_PLURAL   = 'forms';

    /**
     * Form constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     * @param FoundRows       $foundRows
     * @param EnvReader       $envReader
     */
    public function __construct(
        LoggerInterface $logger,
        RepoService $repoService,
        FoundRows $foundRows,
        EnvReader $envReader
    ) {
        parent::__construct($logger, $repoService, $foundRows, $envReader);
    }
}
