<?php

namespace Memo;

class Github
{
    protected $client;
    protected $url;
    protected $option;
    protected $exception;
    protected $paginate;
    
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        $this->url = 'https://api.github.com';
    }

    public function option($option)
    {
        $this->option = $option;

        return $this;
    }

    public function showException()
    {
        $this->exception = true;

        return $this;
    }

    public function request($type, $name, $target = null)
    {
        $this->url .= '/' . $type . '/' . $name;
        if ($target) $this->url .= '/' . $target;

        return $this;
    }

    public function paginate($per_page = 10, $page = null)
    {
        $this->paginate = '?per_page=' . $per_page;
        if ($page) $this->paginate .= '&page=' . $page;

        return $this;
    }

    public function getBody()
    {
        if (!$this->getResponse()) return;

        return (object) [
            'url' => $this->url . $this->paginate,
            'response' => json_decode($this->getResponse()->getBody())
        ];
    }

    public function getHeaderLine($field)
    {
        if (!$this->getResponse()) return;

        return (object) [
            'url' => $this->url . $this->paginate,
            'response' => json_decode($this->getResponse()->getHeaderLine($field))
        ];
    }

    protected function getResponse()
    {
        try {
            $response = $this->client->get($this->url . $this->paginate, $this->option);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = ($this->exception) ? $e->getResponse() : null;
        }

        return $response;
    }
}
