<?php

namespace CSSValidator;

abstract class Message
{
    /**
     * The address of the document trying to validate.
     */
    private string $uri;

    /**
     * Line corresponding to the message.
     *
     * Within the source code of the validated document, refers to the line
     * where the error was detected.
     */
    private int $line;

    /**
     * The actual message.
     */
    private ?string $message = null;

    private ?string $type = null;

    /**
     * Constructor for a response message.
     *
     * @param \DOMElement $node a dom document node
     */
    public function __construct(\DOMElement $node)
    {
        $uriElements = $node->parentNode->getElementsByTagName('uri');
        $this->uri = $uriElements->item(0)->nodeValue;

        $lineElements = $node->getElementsByTagName('line');
        $this->line = $lineElements->item(0)->nodeValue;

        $typeElements = $node->getElementsByTagName('type');
        if ($typeElements->length) {
            $this->type = $typeElements->item(0)->nodeValue;
        }

        $messageElements = $node->getElementsByTagName('message');
        if ($messageElements->length) {
            $this->message = \trim($messageElements->item(0)->nodeValue);
        }
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function setLine(int $line): self
    {
        $this->line = $line;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }
}
