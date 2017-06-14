<?php

namespace CSSValidator;

class Response
{
    /**
     * The address of the document validated. In EARL terms, this is
     * the TestSubject.
     *
     * @var    string
     */
    public $uri;

    /**
     * Location of the service which provided the validation result. In EARL terms,
     * this is the Assertor.
     *
     * @var    string
     */
    public $checkedby;

    /**
     * The CSS level (or profile) in use during the validation.
     *
     * @var    string
     */
    public $csslevel;

    /**
     * The actual date of the validation.
     *
     * @var    string
     */
    public $date;

    /**
     * Whether or not the document validated passed or not formal validation
     *
     * @var    bool
     */
    public $validity;

    /**
     * Array of Error objects (if applicable)
     *
     * @var Error[]
     */
    public $errors = array();

    /**
     * Array of Warning objects (if applicable)
     *
     * @var Error[]
     */
    public $warnings = array();

    /**
     * @return string
     */
    public function getCheckedby()
    {
        return $this->checkedby;
    }

    /**
     * @param string $checkedby
     *
     * @return Response
     */
    public function setCheckedby($checkedby)
    {
        $this->checkedby = $checkedby;

        return $this;
    }

    /**
     * @return string
     */
    public function getCsslevel()
    {
        return $this->csslevel;
    }

    /**
     * @param string $csslevel
     *
     * @return Response
     */
    public function setCsslevel($csslevel)
    {
        $this->csslevel = $csslevel;

        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date
     *
     * @return Response
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param Error[] $errors
     *
     * @return Response
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     *
     * @return Response
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isValidity()
    {
        return $this->validity;
    }

    /**
     * @param boolean $validity
     *
     * @return Response
     */
    public function setValidity($validity)
    {
        $this->validity = $validity;

        return $this;
    }

    /**
     * @return Error[]
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * @param Error[] $warnings
     *
     * @return Response
     */
    public function setWarnings(array $warnings)
    {
        $this->warnings = $warnings;

        return $this;
    }


    /**
     * @param Error $error
     *
     * @return Response
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @param Warning $warning
     *
     * @return Response
     */
    public function addWarning(Warning $warning)
    {
        $this->warnings[] = $warning;

        return $this;
    }
}
