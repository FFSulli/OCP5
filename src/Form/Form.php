<?php

namespace Form;

use Form\Fields\AbstractFormField;

class Form
{
    private array $fields;

    private string $name;

    private string $method;

    private ?string $action;

    public function add(AbstractFormField $field): self
    {
        $this->fields[] = $field;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Form
     */
    public function setName(string $name): Form
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return Form
     */
    public function setMethod(string $method): Form
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * @param string|null $action
     * @return Form
     */
    public function setAction(?string $action): Form
    {
        $this->action = $action;
        return $this;
    }
}
