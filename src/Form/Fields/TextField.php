<?php


namespace Form\Fields;


class TextField extends AbstractFormField
{
    public function generateHtml(): string
    {
        $html = "<input type=\"text\" name=\"{$this->getName()}\" id=\"{$this->getId()}\" />";

        if (null !== $this->getLabel()) {
            $html = "<label for=\"{$this->getId()}\">{$this->getLabel()}</label>" . $html;
        }

        $html = "<div class=\"mt-2\">{$html}</div>";

        return $html;
    }
}
