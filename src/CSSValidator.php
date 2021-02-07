<?php

namespace CSSValidator;

class CSSValidator
{
    /**
     * URI to the W3C validator.
     *
     * @var string
     */
    private $validatorUri = 'http://jigsaw.w3.org/css-validator/validator';

    /**
     * Options.
     *
     * @var Options
     */
    private $options;

    /**
     * Default context for http request.
     *
     * @see https://www.php.net/manual/en/context.php
     *
     * @var array
     */
    private $context = [];

    public function __construct(Options $options = null)
    {
        $this->setOptions($options ?: new Options());
    }

    public function getOptions(): Options
    {
        return $this->options;
    }

    public function setOptions(Options $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getValidatorUri(): string
    {
        return $this->validatorUri;
    }

    public function setValidatorUri(string $validatorUri): self
    {
        $this->validatorUri = $validatorUri;

        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function sendRequest(string $uri, array $context): string
    {
        $context = \array_merge($this->getContext(), $context);

        if (isset($context['http']['header'])) {
            $context['http']['header'] .= "\r\nUser-Agent: gemorroj/cssvalidator";
        } else {
            $context['http']['header'] = 'User-Agent: gemorroj/cssvalidator';
        }

        $data = @\file_get_contents($uri, false, \stream_context_create($context));
        if (false === $data) {
            throw new Exception(\error_get_last()['message']);
        }

        return $data;
    }

    /**
     * Validates a given URI.
     *
     * Executes the validator using the current parameters and returns a Response
     * object on success.
     *
     * @param string $uri The address to the page to validate ex: http://example.com/
     *
     * @throws Exception
     *
     * @return Response object Response if web service call successful
     */
    public function validateUri(string $uri): Response
    {
        $query = \http_build_query(\array_merge(
            $this->getOptions()->buildOptions(),
            ['uri' => $uri, 'output' => 'soap12']
        ));

        $context = [
            'http' => [
                'method' => 'GET',
            ],
        ];

        $data = $this->sendRequest($this->validatorUri.'?'.$query, $context);

        return $this->parseSOAP12Response($data);
    }

    /**
     * Validates the local file.
     *
     * Requests validation on the local file, from an instance of the W3C validator.
     * The file is posted to the W3C validator using multipart/form-data.
     *
     * @param string $file file to be validated
     *
     * @throws Exception
     *
     * @return Response object Response if web service call successful
     */
    public function validateFile(string $file): Response
    {
        if (true !== \file_exists($file)) {
            throw new Exception('File not found');
        }
        if (true !== \is_readable($file)) {
            throw new Exception('File not readable');
        }

        $data = @\file_get_contents($file);
        if (false === $data) {
            throw new Exception(\error_get_last()['message']);
        }

        return $this->validateFragment($data);
    }

    /**
     * Validate an html string.
     *
     * @param string $css Full css document fragment
     *
     * @throws Exception
     *
     * @return Response object Response if web service call successful
     */
    public function validateFragment(string $css): Response
    {
        $query = \http_build_query(\array_merge(
            $this->getOptions()->buildOptions(),
            ['text' => $css, 'output' => 'soap12']
        ));

        $context = [
            'http' => [
                'method' => 'GET',
            ],
        ];

        $data = $this->sendRequest($this->validatorUri.'?'.$query, $context);

        return $this->parseSOAP12Response($data);
    }

    /**
     * Parse an XML response from the validator.
     *
     * This function parses a SOAP 1.2 response xml string from the validator.
     *
     * @param string $xml the raw soap12 XML response from the validator
     *
     * @throws Exception
     *
     * @return Response object Response if parsing soap12 response successfully,
     */
    protected function parseSOAP12Response(string $xml): Response
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');

        if (false === @$doc->loadXML($xml, \LIBXML_COMPACT | \LIBXML_NONET)) {
            throw new Exception('Failed load xml');
        }

        $response = new Response();

        $cssLevelElement = $doc->getElementsByTagName('csslevel');
        $response->setCssLevel($cssLevelElement->item(0)->nodeValue);

        $element = $doc->getElementsByTagName('validity');
        $response->setValid('true' === $element->item(0)->nodeValue);

        $errors = $doc->getElementsByTagName('error');
        foreach ($errors as $error) {
            $response->addError(new Error($error));
        }

        $warnings = $doc->getElementsByTagName('warning');
        foreach ($warnings as $warning) {
            $response->addWarning(new Warning($warning));
        }

        return $response;
    }
}
