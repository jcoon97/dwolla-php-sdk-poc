<?php

declare(strict_types=1);

namespace Dwolla\Http;

use GuzzleHttp\RequestOptions;

final class GuzzleOptions
{
    /**
     * @var string[][]
     */
    private array $options = [];

    private function __construct()
    {
    }

    /**
     * @return string[][]
     */
    public function build(): array
    {
        return $this->options;
    }

    /**
     * @template T of JsonSerializable
     * @param string $key
     * @param T|string[]|null $arr
     * @return void
     */
    public function mergeOptions(string $key, $arr)
    {
        if ($arr === null) {
            return;
        }

        $this->options[$key] = array_key_exists($key, $this->options)
            ? array_merge($this->options[$key], $arr)
            : $arr;
    }

    /**
     * @return static
     */
    public static function new(): self
    {
        return new self();
    }

    /**
     * @param string[]|null $formParams
     * @return $this
     */
    public function withFormParams(?array $formParams): self
    {
        $this->mergeOptions(RequestOptions::FORM_PARAMS, $formParams);
        return $this;
    }

    /**
     * @param string[]|null $headers
     * @return $this
     */
    public function withHeaders(?array $headers): self
    {
        $this->mergeOptions(RequestOptions::HEADERS, $headers);
        return $this;
    }

    /**
     * @template T of JsonSerializable
     * @param T|string[]|null $json
     * @return $this
     */
    public function withJson($json): self
    {
        $this->mergeOptions(RequestOptions::JSON, $json);
        return $this;
    }

    /**
     * @param string[]|null $query
     * @return $this
     */
    public function withQuery(?array $query): self
    {
        $this->mergeOptions(RequestOptions::QUERY, $query);
        return $this;
    }
}