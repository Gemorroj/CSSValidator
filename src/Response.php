<?php

namespace CSSValidator;

use CSSValidator\Message\Error;
use CSSValidator\Message\Warning;
use CSSValidator\Options\ProfileType;

class Response
{
    /**
     * The CSS level (or profile) in use during the validation.
     */
    private string $cssLevel = ProfileType::Css3->value;

    /**
     * Whether or not the document validated passed or not formal validation.
     */
    private bool $valid = false;

    /**
     * Array of Error objects (if applicable).
     *
     * @var Error[]
     */
    private array $errors = [];

    /**
     * Array of Warning objects (if applicable).
     *
     * @var Warning[]
     */
    private array $warnings = [];

    public function getCssLevel(): string
    {
        return $this->cssLevel;
    }

    public function setCssLevel(string $cssLevel): self
    {
        $this->cssLevel = $cssLevel;

        return $this;
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param Error[] $errors
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * @return Warning[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * @param Warning[] $warnings
     */
    public function setWarnings(array $warnings): self
    {
        $this->warnings = $warnings;

        return $this;
    }

    public function addError(Error $error): self
    {
        $this->errors[] = $error;

        return $this;
    }

    public function addWarning(Warning $warning): self
    {
        $this->warnings[] = $warning;

        return $this;
    }
}
