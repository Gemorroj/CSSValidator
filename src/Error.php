<?php

namespace CSSValidator;

class Error extends Message
{
    /**
     * Category of error raised.
     *
     * @var string
     */
    protected $errortype;

    /**
     * Context where error was occured.
     *
     * @var string
     */
    protected $context;

    /**
     * CSS property where error is raised.
     *
     * @var string
     */
    protected $property;

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     *
     * @return Error
     */
    public function setContext($context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrortype()
    {
        return $this->errortype;
    }

    /**
     * @param string $errortype
     *
     * @return Error
     */
    public function setErrortype($errortype): self
    {
        $this->errortype = $errortype;

        return $this;
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param string $property
     *
     * @return Error
     */
    public function setProperty($property): self
    {
        $this->property = $property;

        return $this;
    }
}
