<?php
/**
 * This file contains the base class for utilizing an instance of the
 * W3 CSS Validator.
 *
 * PHP versions 5
 *
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 */
namespace CSSValidator;

/**
 * A simple class for utilizing the W3C CSS Validator service.
 *
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 */
class Error extends Message
{
    /**
     * Category of error raised.
     *
     * @var    string
     */
    protected $errortype;

    /**
     * Context where error was occured.
     *
     * @var    string
     */
    protected $context;

    /**
     * CSS property where error is raised.
     *
     * @var    string
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
    public function setContext($context)
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
    public function setErrortype($errortype)
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
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }
}
