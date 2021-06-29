<?php


namespace Form\Fields;


class TextareaField extends AbstractFormField
{
    public function generateHtml(): string
    {
        $html = "<textarea name=\"{$this->getName()}\" id=\"{$this->getId()}\" class=\"{$this->getFieldClasses()}\"></textarea>";

        if (null !== $this->getLabel()) {
            $html = "<label for=\"{$this->getId()}\" class=\"{$this->getLabelClasses()}\">{$this->getLabel()}</label>" . $html;
        }

        return $html;
    }
}
