<?php

namespace CSSValidator;

class Warning extends Message
{
    /**
     * @var int
     */
    private $level;

    /**
     * {@inheritdoc}
     */
    public function __construct(\DOMElement $node)
    {
        parent::__construct($node);

        $levelElement = $node->getElementsByTagName('level');
        $this->setLevel($levelElement->item(0)->nodeValue);
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
