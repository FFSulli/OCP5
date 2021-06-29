<?php


namespace Form\Fields;


class TextField extends AbstractFormField
{
    public function generateHtml(): string
    {
        $html = "<input type=\"text\" name=\"{$this->getName()}\" id=\"{$this->getId()}\" class=\"{$this->getFieldClasses()}\" />";

        if (null !== $this->getLabel()) {
            $html = "<label for=\"{$this->getId()}\" class=\"{$this->getLabelClasses()}\">{$this->getLabel()}</label>" . $html;
        }

        return $html;
    }
}
