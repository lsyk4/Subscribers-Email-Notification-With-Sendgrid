<?php

/**
  * HTTP Client library
  *
  * PHP version 5.4
  *
  * @author    Matt Bernier <dx@sendgrid.com>
  * @author    Elmer Thomas <dx@sendgrid.com>
  * @copyright 2016 SendGrid
  * @license   https://opensource.org/licenses/MIT The MIT License
  * @version   GIT: <git_id>
  * @link      http://packagist.org/packages/sendgrid/php-http-client
  */

namespace SendGrid;

/**
  * Quickly and easily access any REST or REST-like API.
  */
class Client
{
    /** @var string */
    protected $host;
    /** @var array */
    protected $headers;
    /** @var string */
    protected $version;
    /** @var array */
    protected $path;
    /** @var array */
    protected $curlOptions;
    /** @var array */
    private $methods;

    /**
      * Initialize the client
      *
      * @param string $host        the base url (e.g. https://api.sendgrid.com)
      * @param array  $headers     global request headers
      * @param string $version     api version (configurable)
      * @param array  $path        holds the segments of the url path
      * @param array  $curlOptions extra options to set during curl initialization
      */
    public function __construct($host, $headers = null, $version = null, $path = null, $curlOptions = null)
    {
        $this->host = $host;
        $this->headers = $headers ?: [];
        $this->version = $version;
        $this->path = $path ?: [];
        $this->curlOptions = $curlOptions ?: [];
        // These are the supported HTTP verbs
        $this->methods = ['delete', 'get', 'patch', 'post', 'put'];
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return array
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getCurlOptions()
    {
        return $this->curlOptions;
    }

    /**
      * Make a new Client object
      *
      * @param string $name name of the url segment
      *
      * @return Client object
      */
    private function buildClient($name = null)
    {
        if (isset($name)) {
            $this->path[] = $name;
        }
        $client = new Client($this->host, $this->headers, $this->version, $this->path);
        $this->path = [];
        return $client;
    }

    /**
      * Build the final URL to be passed
      *
      * @param array $queryParams an array of all the query parameters
      *
      * @return string
      */
    private function buildUrl($queryParams = null)
    {
        $path = '/' . implode('/', $this->path);
        if (isset($queryParams)) {
            $path .= '?' . http_build_query($queryParams);
        }
        return sprintf('%s%s%s', $this->host, $this->version ?: '', $path);
    }

    /**
      * Make the API call and return the response. This is separated into
      * it's own function, so we can mock it easily for testing.
      *
      * @param string $method  the HTTP verb
      * @param string $url     the final url to call
      * @param array  $body    request body
      * @param array  $headers any additional request headers
      *
      * @return Response object
      */
    public function makeRequest($method, $url, $body = null, $headers = null)
    {
      $args = array(
          'headers' => array(
              'Authorization' => 'Bearer '.get_option('SubscribersEmailNotification_SGAPIKEY'),
              'Content-Type' => 'application/json'
          ),
          'body' => wp_json_encode($body),
      );
      if($method === "post"){
        $response = wp_remote_post($url, $args);
      }elseif($method === "get"){
        $response = wp_remote_get($url, $args);
      }
      $responseBody = $response["body"];
      $responseHeaders = $response["headers"];
      $statusCode = $response["response"]["code"];
      return new Response($statusCode, $responseBody, $responseHeaders);
    }

    /*
      * Add variable values to the url.
      * (e.g. /your/api/{variable_value}/call)
      * Another example: if you have a PHP reserved word, such as and,
      * in your url, you must use this method.
      *
      * @param string $name name of the url segment
      *
      * @return Client object
      */
    public function _($name = null)
    {
        return $this->buildClient($name);
    }

    /**
      * Dynamically add method calls to the url, then call a method.
      * (e.g. client.name.name.method())
      *
      * @param string $name name of the dynamic method call or HTTP verb
      * @param array  $args parameters passed with the method call
      *
      * @return Client or Response object
      */
    public function __call($name, $args)
    {
        $name = strtolower($name);

        if ($name === 'version') {
            $this->version = $args[0];
            return $this->_();
        }

        if (in_array($name, $this->methods, true)) {
            $body = isset($args[0]) ? $args[0] : null;
            $queryParams = isset($args[1]) ? $args[1] : null;
            $url = $this->buildUrl($queryParams);
            $headers = isset($args[2]) ? $args[2] : null;
            return $this->makeRequest($name, $url, $body, $headers);
        }

        return $this->_($name);
    }
}
