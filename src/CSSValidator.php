<?php

namespace CSSValidator;

use CSSValidator\Message\Error;
use CSSValidator\Message\Warning;
use CSSValidator\Options\Options;
use Symfony\Component\HttpClient\HttpClient;
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
     */
    private string $validatorUri;

    private Options $options;
    private HttpClientInterface $httpClient;

    public function __construct(
        ?Options $options = null,
        ?HttpClientInterface $httpClient = null,
        string $validatorUri = 'https://jigsaw.w3.org/css-validator/validator',
    ) {
        $this->options = $options ?? new Options();
        $this->httpClient = $httpClient ?? HttpClient::create();
        $this->validatorUri = $validatorUri;
    }

    public function getOptions(): Options
    {
        return $this->options;
    }

    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    public function getValidatorUri(): string
    {
        return $this->validatorUri;
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
     * @throws RedirectionExceptionInterface On a 3xx when the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx
     * @throws ServerExceptionInterface      On a 5xx
     */
    public function validateUri(string $uri): Response
    {
        return $this->request('GET', \array_merge($this->buildQuery(), ['uri' => $uri]));
    }

    /**
     * Validates the local file.
     *
     * Requests validation on the local file, from an instance of the W3C validator.
     *
     * @param string $file file to be validated
     *
     * @throws Exception
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx
     * @throws ServerExceptionInterface      On a 5xx
     */
    public function validateFile(string $file): Response
    {
        $f = @\fopen($file, 'r');
        if (false === $f) {
            throw new Exception(\error_get_last()['message'] ?? \sprintf('Unable to open file "%s".', $file));
        }

        try {
            $css = \stream_get_contents($f);
        } finally {
            \fclose($f);
        }

        if (false === $css) {
            throw new Exception(\sprintf('Unable to read file "%s".', $file));
        }

        return $this->validateFragment($css);
    }

    /**
     * Validate a css string.
     *
     * @param string $css Full css document fragment
     *
     * @throws Exception
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx
     * @throws ServerExceptionInterface      On a 5xx
     */
    public function validateFragment(string $css): Response
    {
        return $this->request('POST', \array_merge($this->buildQuery(), ['text' => $css]));
    }

    /**
     * Send a request to the validator and parse its SOAP12 response.
     *
     * The validator reads the parameters from the query string on GET and from a
     * multipart/form-data body on POST, so they cannot be mixed between the two.
     *
     * @param array<string, string> $params
     *
     * @throws Exception
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx
     * @throws ServerExceptionInterface      On a 5xx
     */
    private function request(string $method, array $params): Response
    {
        $options = [
            'headers' => [
                'User-Agent' => 'gemorroj/cssvalidator',
            ],
        ];
        if ('GET' === $method) {
            $options['query'] = $params;
        } else {
            $options['headers']['Content-Type'] = 'multipart/form-data';
            $options['body'] = $params;
        }

        $response = $this->getHttpClient()->request($method, $this->getValidatorUri(), $options);

        try {
            $content = $response->getContent();
        } catch (ClientExceptionInterface|ServerExceptionInterface $e) {
            $fault = self::extractFaultMessage($e->getResponse()->getContent(false));
            if (null !== $fault) {
                throw new Exception($fault, 0, $e);
            }

            throw $e;
        }

        return $this->parseSOAP12Response($content);
    }

    /**
     * Build the common query string arguments for the validator.
     *
     * @return array<string, string>
     */
    private function buildQuery(): array
    {
        return \array_merge(
            $this->getOptions()->buildOptions(),
            ['output' => 'soap12']
        );
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
        $doc = self::loadXml($xml);

        $fault = self::faultMessage($doc);
        if (null !== $fault) {
            throw new Exception($fault);
        }

        $response = new Response();

        $cssLevel = $doc->getElementsByTagName('csslevel')->item(0);
        if (null !== $cssLevel) {
            $response->setCssLevel($cssLevel->nodeValue);
        }

        $validity = $doc->getElementsByTagName('validity')->item(0);
        if (null !== $validity) {
            $response->setValid('true' === $validity->nodeValue);
        }

        foreach ($doc->getElementsByTagName('error') as $error) {
            $response->addError(new Error($error));
        }

        foreach ($doc->getElementsByTagName('warning') as $warning) {
            $response->addWarning(new Warning($warning));
        }

        return $response;
    }

    /**
     * @throws Exception
     */
    private static function loadXml(string $xml): \DOMDocument
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');

        if (false === @$doc->loadXML($xml, \LIBXML_COMPACT | \LIBXML_NONET)) {
            throw new Exception('Failed load xml');
        }

        return $doc;
    }

    /**
     * Return the SOAP fault reason, or null when the document is not a fault.
     */
    private static function faultMessage(\DOMDocument $doc): ?string
    {
        $fault = $doc->getElementsByTagName('Fault')->item(0);
        if (null === $fault) {
            return null;
        }

        $reason = $fault->getElementsByTagName('Text')->item(0);

        return null !== $reason ? \trim($reason->nodeValue) : 'The validator reported a SOAP fault.';
    }

    /**
     * @return string|null the SOAP fault reason, or null when the body is not a parseable fault
     */
    private static function extractFaultMessage(string $xml): ?string
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');

        if (false === @$doc->loadXML($xml, \LIBXML_COMPACT | \LIBXML_NONET)) {
            return null;
        }

        return self::faultMessage($doc);
    }
}
