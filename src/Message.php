<?php

namespace CSSValidator;

abstract class Message
{
    /**
     * The address of the document trying to validate.
     *
     * @var string
     */
    private $uri;

    /**
     * Line corresponding to the message.
     *
     * Within the source code of the validated document, refers to the line
     * where the error was detected.
     *
     * @var int
     */
    private $line;

    /**
     * The actual message.
     *
     * @var string|null
     */
    private $message;

    /**
     * @var string|null
     */
    private $type;

    /**
     * Constructor for a response message.
     *
     * @param \DOMElement $node a dom document node
     */
    public function __construct(\DOMElement $node)
    {
        $uriElement = $node->parentNode->getElementsByTagName('uri');
        $this->setUri($uriElement->item(0)->nodeValue);

        $lineElement = $node->getElementsByTagName('line');
        $this->setLine($lineElement->item(0)->nodeValue);

        $typeElement = $node->getElementsByTagName('type');
        if ($typeElement->length) {
            $this->setType($typeElement->item(0)->nodeValue);
        }

        $messageElement = $node->getElementsByTagName('message');
        if ($messageElement->length) {
            $this->setMessage(\trim($messageElement->item(0)->nodeValue));
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
