<?php


namespace Form\Fields;


class ButtonField extends AbstractFormField
{
    private string $type = "submit";

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return ButtonField
     */
    public function setType(string $type): ButtonField
    {
        $this->type = $type;
        return $this;
    }

    public function generateHtml(): string
    {
        return "<button type=\"{$this->getType()}\" class=\"\">" . $this->getLabel() ?? $this->getName() . "</button>";
    }
}
