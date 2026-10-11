<?php

namespace CSSValidator\Message;

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
        $this->uri = self::firstValue($node->parentNode, 'uri') ?? '';
        $this->line = (int) (self::firstValue($node, 'line') ?? 0);
        $this->type = self::firstValue($node, 'type');
        $this->message = self::firstValue($node, 'message');
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

    /**
     * Return the trimmed value of the first child element with the given name, if any.
     */
    protected static function firstValue(?\DOMNode $context, string $tagName): ?string
    {
        if (!$context instanceof \DOMElement && !$context instanceof \DOMDocument) {
            return null;
        }

        $value = $context->getElementsByTagName($tagName)->item(0)?->nodeValue;

        return null !== $value ? \trim($value) : null;
    }
}
