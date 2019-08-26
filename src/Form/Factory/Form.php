<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Form\Factory;

use AbterPhp\Admin\Form\Factory\Base;
use AbterPhp\Contact\Domain\Entities\Form as Entity;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Extra\Help;
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
     * @param ISession    $session
     * @param ITranslator $translator
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
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $this->createForm($action, $method)
            ->addDefaultElements()
            ->addName($entity)
            ->addIdentifier($entity)
            ->addToName($entity)
            ->addToEmail($entity)
            ->addSuccessUrl($entity)
            ->addFailureUrl($entity)
            ->addMaxBodyLength($entity)
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
        $input = new Input('to_email', 'to_email', $entity->getToEmail(), [], [Html5::ATTR_TYPE => Input::TYPE_EMAIL]);
        $label = new Label('to_email', 'contact:formToEmail');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addSuccessUrl(Entity $entity): Form
    {
        $input = new Input(
            'success_url',
            'success_url',
            $entity->getSuccessUrl(),
            [],
            [Html5::ATTR_TYPE => Input::TYPE_URL]
        );
        $label = new Label('success_url', 'contact:formSuccessUrl');
        $help  = new Help('contact:hintSuccessUrl');

        $this->form[] = new FormGroup($input, $label, $help);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addFailureUrl(Entity $entity): Form
    {
        $input = new Input(
            'failure_url',
            'failure_url',
            $entity->getFailureUrl(),
            [],
            [Html5::ATTR_TYPE => Input::TYPE_URL]
        );
        $label = new Label('failure_url', 'contact:formFailureUrl');
        $help  = new Help('contact:hintFailureUrl');

        $this->form[] = new FormGroup($input, $label, $help);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addMaxBodyLength(Entity $entity): Form
    {
        $input = new Input(
            'max_body_length',
            'max_body_length',
            (string)$entity->getMaxBodyLength(),
            [],
            [
                Html5::ATTR_TYPE => Input::TYPE_NUMBER,
                Html5::ATTR_MIN  => 0,
                Html5::ATTR_MAX  => 10000,
                Html5::ATTR_STEP => 100,
            ]
        );
        $label = new Label('max_body_length', 'contact:formMaxBodyLength');
        $help  = new Help('contact:hintMaxBodyLength');

        $this->form[] = new FormGroup($input, $label, $help);

        return $this;
    }
}
