<?php

namespace CSSValidator\Message;

class Warning extends Message
{
    private int $level;

    public function __construct(\DOMElement $node)
    {
        parent::__construct($node);

        $this->level = (int) (self::firstValue($node, 'level') ?? 0);
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
