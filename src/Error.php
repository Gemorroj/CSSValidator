<?php

namespace CSSValidator;

class Error extends Message
{
    /**
     * Category of error raised.
     *
     * @var string
     */
    private $errorType;

    /**
     * Context where error was occurred.
     *
     * @var string|null
     */
    private $context;

    /**
     * @var string|null
     */
    private $errorSubType;

    /**
     * @var string|null
     */
    private $skippedString;

    /**
     * {@inheritdoc}
     */
    public function __construct(\DOMElement $node)
    {
        parent::__construct($node);

        $errorTypeElement = $node->getElementsByTagName('errortype');
        $this->setErrorType($errorTypeElement->item(0)->nodeValue);

        $contextElement = $node->getElementsByTagName('context');
        if ($contextElement->length) {
            $this->setContext($contextElement->item(0)->nodeValue);
        }

        $errorSubTypeElement = $node->getElementsByTagName('errorsubtype');
        if ($errorSubTypeElement->length) {
            $this->setErrorSubType(\trim($errorSubTypeElement->item(0)->nodeValue));
        }

        $skippedStringElement = $node->getElementsByTagName('skippedstring');
        if ($skippedStringElement->length) {
            $this->setSkippedString(\trim($skippedStringElement->item(0)->nodeValue));
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
