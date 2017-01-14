<?php

namespace CSSValidator;

abstract class Message
{
    /**
     * The address of the document trying to validate
     *
     * @var    string
     */
    protected $uri;

    /**
     * Line corresponding to the message
     *
     * Within the source code of the validated document, refers to the line
     * where the error was detected.
     *
     * @var    int
     */
    protected $line;

    /**
     * The actual message
     *
     * @var    string
     */
    protected $message;

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param int $line
     *
     * @return Message
     */
    public function setLine($line)
    {
        $this->line = $line;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

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
     * @return Message
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }


    /**
     * Constructor for a response message
     *
     * @param \DOMElement $node A dom document node.
     */
    public function __construct(\DOMElement $node = null)
    {
        if ($node) {
            foreach (get_class_vars(__CLASS__) as $var => $val) {
                $element = $node->getElementsByTagName($var);
                if ($element->length) {
                    $this->{'set' . ucfirst($var)}($element->item(0)->nodeValue);
                }
            }
        }
    }
}
