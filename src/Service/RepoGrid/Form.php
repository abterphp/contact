<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Service\RepoGrid;

use AbterPhp\Contact\Grid\Factory\Form as GridFactory;
use AbterPhp\Contact\Orm\FormRepo as Repo;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Framework\Http\Service\RepoGrid\RepoGridAbstract;
use Casbin\Enforcer;

class Form extends RepoGridAbstract
{
    /**
     * Form constructor.
     *
     * @param Enforcer    $enforcer
     * @param Repo        $repo
     * @param FoundRows   $foundRows
     * @param GridFactory $gridFactory
     */
    public function __construct(Enforcer $enforcer, Repo $repo, FoundRows $foundRows, GridFactory $gridFactory)
    {
        parent::__construct($enforcer, $repo, $foundRows, $gridFactory);
    }
}
