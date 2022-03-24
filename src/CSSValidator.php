<?php

namespace CSSValidator;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CSSValidator
{
    /**
     * URI to the W3C validator.
     *
     * @see https://github.com/w3c/css-validator
     *
     * @var string
     */
    private $validatorUri = 'https://jigsaw.w3.org/css-validator/validator';

    /**
     * Options.
     *
     * @var Options
     */
    private $options;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct(Options $options = null, HttpClientInterface $httpClient = null)
    {
        $this->setOptions($options ?: new Options());
        if (!$httpClient) {
            $httpClient = HttpClient::createForBaseUri($this->getValidatorUri(), [
                'headers' => [
                    'User-Agent' => 'gemorroj/cssvalidator',
                ],
            ]);

            // https://symfony.com/doc/current/http_client.html#retry-failed-requests
            $httpClient = new RetryableHttpClient($httpClient);
        }
        $this->setHttpClient($httpClient);
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

    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    public function setHttpClient(HttpClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
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
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when $throw is true and the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx when $throw is true
     * @throws ServerExceptionInterface      On a 5xx when $throw is true
     */
    public function validateUri(string $uri): Response
    {
        $response = $this->getHttpClient()->request('GET', '', [
            'query' => \array_merge(
                $this->getOptions()->buildOptions(),
                ['uri' => $uri, 'output' => 'soap12']
            ),
        ]);

        return $this->parseSOAP12Response($response->getContent());
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
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when $throw is true and the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx when $throw is true
     * @throws ServerExceptionInterface      On a 5xx when $throw is true
     */
    public function validateFile(string $file): Response
    {
        if (!\file_exists($file)) {
            throw new Exception('File not found');
        }
        if (!\is_readable($file)) {
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
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when $throw is true and the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx when $throw is true
     * @throws ServerExceptionInterface      On a 5xx when $throw is true
     */
    public function validateFragment(string $css): Response
    {
        $response = $this->getHttpClient()->request('GET', '', [
            'body' => $css,
            'query' => \array_merge(
                $this->getOptions()->buildOptions(),
                ['text' => $css, 'output' => 'soap12']
            ),
        ]);

        return $this->parseSOAP12Response($response->getContent());
    }

    /**
     * Parse an XML response from the validator.
     *
     * This function parses a SOAP 1.2 response xml string from the validator.
     *
     * @param string $xml the raw soap12 XML response from the validator
     *
     * @throws Exception
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
