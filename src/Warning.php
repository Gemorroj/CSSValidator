<?php

namespace CSSValidator;

class Warning extends Message
{
    private int $level;

    public function __construct(\DOMElement $node)
    {
        parent::__construct($node);

        $levelElements = $node->getElementsByTagName('level');
        $this->level = $levelElements->item(0)->nodeValue;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }
}
