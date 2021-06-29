<?php

namespace Form\Fields;

class AbstractFormField
{
    private string $label;

    private string $name;

    private string $placeholder;

    private string $id;

    private string $value;

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return AbstractFormField
     */
    public function setLabel(string $label): AbstractFormField
    {
        $this->label = $label;
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
     * @return AbstractFormField
     */
    public function setName(string $name): AbstractFormField
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     * @return AbstractFormField
     */
    public function setPlaceholder(string $placeholder): AbstractFormField
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return AbstractFormField
     */
    public function setId(string $id): AbstractFormField
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return AbstractFormField
     */
    public function setValue(string $value): AbstractFormField
    {
        $this->value = $value;
        return $this;
    }
}
