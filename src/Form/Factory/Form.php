<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Form\Factory;

use AbterPhp\Contact\Domain\Entities\Form as Entity;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Component\Option;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\MultiSelect;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Factory\Base;
use AbterPhp\Framework\Form\Factory\IFormFactory;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

class Form extends Base
{
    /**
     * Form constructor.
     *
     * @param ISession      $session
     * @param ITranslator   $translator
     */
    public function __construct(ISession $session, ITranslator $translator)
    {
        parent::__construct($session, $translator);
    }

    /**
     * @param string       $action
     * @param string       $method
     * @param string       $showUrl
     * @param IEntity|null $entity
     *
     * @return IForm
     */
    public function create(string $action, string $method, string $showUrl, ?IEntity $entity = null): IForm
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(IFormFactory::ERR_MSG_ENTITY_MISSING);
        }

        $this->createForm($action, $method)
            ->addDefaultElements()
            ->addName($entity)
            ->addIdentifier($entity)
            ->addToName($entity)
            ->addToEmail($entity)
            ->addDefaultButtons($showUrl);

        $form = $this->form;

        $this->form = null;

        return $form;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addName(Entity $entity): Form
    {
        $input = new Input('name', 'name', $entity->getName());
        $label = new Label('name', 'contact:formName');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addIdentifier(Entity $entity): Form
    {
        $this->form[] = new Input(
            'identifier',
            'identifier',
            $entity->getIdentifier(),
            [],
            [Html5::ATTR_TYPE => Input::TYPE_HIDDEN]
        );

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addToName(Entity $entity): Form
    {
        $input = new Input('to_name', 'to_name', $entity->getToName());
        $label = new Label('to_name', 'contact:formToName');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addToEmail(Entity $entity): Form
    {
        $input = new Input('to_email', 'to_email', $entity->getToEmail());
        $label = new Label('to_email', 'contact:formToEmail');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }
}
