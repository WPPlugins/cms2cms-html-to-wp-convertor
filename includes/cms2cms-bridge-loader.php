<?php

class HtmlCmsBridgeLoader {

    private $uri;
    private $key;

    /**
     * @param $uri
     * @return $this
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @param $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Absolute path
     * @return string
     */
    protected function absolutePath()
    {
        return ABSPATH;
    }

    /**
     * Create bridge file
     */
    public function extract()
    {
        $bridgeUrl = sprintf('%s/key/%s/json/response', $this->uri, $this->key);

        if (false == ($fileList = file_get_contents($bridgeUrl))) {
            throw new Exception('Connection Bridge installation error.');
        }

        $bridgeFolder = $this->absolutePath().'/cms2cms';
        foreach (json_decode($fileList) as $key => $content) {
            if (false == is_dir($this->absolutePath().current(explode('/', $key)))) {
                $bridgeFolder = $this->absolutePath().current(explode('/', $key));
                if (false == @mkdir($this->absolutePath().current(explode('/', $key)))) {
                    throw new Exception('Connection Bridge installation error.');
                }
            }

            $this->write($key,$content);
            @chmod($bridgeFolder, 0777);
        }

    }

    /**
     * Check if key same
     * @param $dbKey
     * @param $host
     */
    public function checkKey($dbKey, $host)
    {
        if (false == isset($dbKey)) {
            return;
        }

        @include $this->absolutePath().'/cms2cms/key.php';
        $key = @constant('CMS2CMS_KEY');

        if (!file_exists($this->absolutePath().'/cms2cms/key.php') || !file_exists($this->absolutePath().'/cms2cms/bridge.php') || $key != $dbKey) {
            $this->setKey($dbKey);
            $this->setUri($host);
            $this->extract();
        }
    }

    /**
     * Write files
     * @param $filePath
     * @param $fileContent
     * @throws \Exception
     */
    public function write($filePath, $fileContent)
    {
        $fileName = $this->absolutePath().$filePath;

        if (true == file_exists($fileName) && true == is_writable($fileName)) {
            @file_put_contents($fileName, $fileContent);
        } else {
            $file = @fopen($fileName, "x+");
            if (false == $file || false == is_writable($fileName)) {
                throw new Exception('Connection Bridge installation error.');
            }

            @fwrite($file, $fileContent);
            fclose($file);
            @chmod($fileName, 0777);
        }
    }

}
