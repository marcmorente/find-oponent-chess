<?php

class HandleMultipleFiles
{
    private $files;
    private $arrayOfFile;
    private $allFiles;

    public function __construct($files)
    {
        $this->files = $files;
    }

    public function arrayFiles()
    {
        $i = 0;
        while (count($this->files['name']) != $i) {
            foreach ($this->files as $key => $file) {
                $this->arrayOfFile[$key] = $file[$i];
            }
            $this->allFiles[] = $this->arrayOfFile;
            $i++;
        }

        return $this->allFiles;
    }
}