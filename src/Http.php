<?php

namespace Star\utils;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Star\utils\Serializer;

class Http
{
	protected $uri;
	protected $method;
	protected $body;
	protected $query = array();
	protected $accessToken;
	protected $sslCert;
	protected $sslKey;

	public function __construct($method, $uri)
	{
		$this->uri = $uri;
		$this->method = strtoupper($method);
	}

	public static function request($method, $uri)
	{
		return new static($method, $uri);
	}

	public function withQuery(array $query)
	{
		$this->query = array_merge($this->query, $query);
		return $this;
	}

	/**
	 * Request JSON body
	 * @param  array  $body
	 */
	public function withBody(array $body)
	{
		$this->body = Serializer::jsonEncode($body);
		return $this;
	}

	/**
	 * Request XML body
	 * @param  array  $body 
	 */
	public function withXmlBody(array $body)
	{
		$this->body = Serializer::xmlEncode($body);
		return $this;
	}

	public function withAccessToken()
	{
		$this->query['access_token'] = $accessToken->getTokenStrint();
		return $this;
	}

	public function withSSLCert($sslCert, $sslKey)
	{
		$this->sslCert = $sslCert;
		$this->sslKey = $sslKey;
		return $this;
	}

	public function send($asArray = true)
	{
		$options = array();
		if ( !empty($this->query)) {
			$options['query'] = $this->body;
		}

		if ( !empty($this->body)) {
			$options['body'] = $this->body;
		}

		if ( $this->sslCert && $this->sslKey) {
			$options['cert'] = $this->sslCert;
			$options['ssl_key'] = $this->sslKey;
		}

		$response = (new Client)->request($this->method, $this->uri, $options);
		$contents = $response->getBody()->getContents();

		if ( !$asArray) {
			return $content;
		}

		$array = Serializer::parse($contetns);

		return new Collection($array);
	}
}