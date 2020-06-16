<?php

namespace App\Models\Adapters;

use Nesk\Puphpeteer\Puppeteer;

abstract class BaseAdapter
{
    public $url;
    public $adapterName;
    public $containerNumber;

    protected $browser;
    protected $page;

    public function __construct(string $containerNumber)
    {
        $this->containerNumber = $containerNumber;

        $this->openPage();
    }

    /**
     *
     */
    public function openPage()
    {
        $puppeteer = new Puppeteer();
        $this->browser = $puppeteer->launch([
            'headless' => false,
            'log_browser_console' => true,
            'args' => ['--no-sandbox', '--disable-setuid-sandbox'],
            'defaultViewport' => [
                'width' => 1200,
                'height' => 600
            ]
        ]);

        $this->page = $this->browser->newPage();
        $this->page->goto($this->url);
    }

    public function makeScreenshot()
    {
        $this->page->screenshot([
            'path' => 'screenshot-' . $this->adapterName . '.png'
        ]);
    }

    protected function appendAdapterName(array $data)
    {
        if (!empty($data)) {
            foreach ($data as $key => $row) {
                $data[$key]['source'] = $this->adapterName;
            }
        }

        return $data;
    }

    abstract public function processToTracking();
    abstract public function getData();
}
