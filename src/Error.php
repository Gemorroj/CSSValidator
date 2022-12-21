<?php

namespace CSSValidator;

class Error extends Message
{
    /**
     * Category of error raised.
     */
    private string $errorType;

    /**
     * Context where error was occurred.
     */
    private ?string $context = null;

    private ?string $errorSubType = null;

    private ?string $skippedString = null;

    public function __construct(\DOMElement $node)
    {
        parent::__construct($node);

        $errorTypeElements = $node->getElementsByTagName('errortype');
        $this->errorType = $errorTypeElements->item(0)->nodeValue;

        $contextElements = $node->getElementsByTagName('context');
        if ($contextElements->length) {
            $this->context = $contextElements->item(0)->nodeValue;
        }

        $errorSubTypeElements = $node->getElementsByTagName('errorsubtype');
        if ($errorSubTypeElements->length) {
            $this->errorSubType = \trim($errorSubTypeElements->item(0)->nodeValue);
        }

        $skippedStringElements = $node->getElementsByTagName('skippedstring');
        if ($skippedStringElements->length) {
            $this->skippedString = \trim($skippedStringElements->item(0)->nodeValue);
        }
    }

    public function getSkippedString(): ?string
    {
        return $this->skippedString;
    }

    public function setSkippedString(?string $skippedString): self
    {
        $this->skippedString = $skippedString;

        return $this;
    }

    public function getErrorSubType(): ?string
    {
        return $this->errorSubType;
    }

    public function setErrorSubType(?string $errorSubType): self
    {
        $this->errorSubType = $errorSubType;

        return $this;
    }

    public function getErrorType(): string
    {
        return $this->errorType;
    }

    public function setErrorType(string $errorType): self
    {
        $this->errorType = $errorType;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): self
    {
        $this->context = $context;

        return $this;
    }
}
