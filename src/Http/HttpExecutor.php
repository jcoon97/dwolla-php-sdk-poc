<?php

declare(strict_types=1);

namespace Dwolla\Http;

use Dwolla\Environment;
use Dwolla\Response;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use JsonMapper\Enums\TextNotation;
use JsonMapper\JsonMapperFactory;
use JsonMapper\JsonMapperInterface;
use JsonMapper\Middleware\CaseConversion;

final class HttpExecutor
{
    /**
     * @var GuzzleClient
     */
    private GuzzleClient $httpClient;

    /**
     * @var JsonMapperInterface
     */
    private JsonMapperInterface $jsonMapper;

    public function __construct(Environment $environment)
    {
        $this->httpClient = new GuzzleClient(["base_uri" => $environment->getUrl()]);
        $this->jsonMapper = (new JsonMapperFactory())->bestFit();
        $this->jsonMapper->push(new CaseConversion(TextNotation::KEBAB_CASE(), TextNotation::CAMEL_CASE()));
        $this->jsonMapper->push(new CaseConversion(TextNotation::UNDERSCORE(), TextNotation::CAMEL_CASE()));
    }

    /**
     * @template T of object
     * @param T|null $deserializeAs
     * @param string $method
     * @param string $url
     * @param GuzzleOptions $options
     * @return Response<T>
     * @throws GuzzleException
     */
    public function execute($deserializeAs, string $method, string $url, GuzzleOptions $options): Response
    {
        $options->mergeOptions(RequestOptions::HEADERS, self::getUserAgentHeader());
        $response = $this->httpClient->request($method, $url, $options->build());
        $jsonBody = json_decode((string)$response->getBody());

        if ($deserializeAs !== null) {
            $jsonBody = $this->jsonMapper->mapObject($jsonBody, $deserializeAs);
        }
        return new Response($jsonBody, $response->getHeaders(), $response->getStatusCode());
    }

    /**
     * @return string[]
     */
    public static function getUserAgentHeader(): array
    {
        $contents = file_get_contents(__DIR__ . "/../../composer.json");
        $json = json_decode($contents);

        return [
            "User-Agent" => ("dwolla-php-sdk/" . $json->version)
        ];
    }
}