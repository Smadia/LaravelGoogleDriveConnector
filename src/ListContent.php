<?php

namespace Smadia\LaravelGoogleDrive;

use Illuminate\Support\Facades\Storage;
use Smadia\LaravelGoogleDrive\Handler\DirectoryHandler;
use Smadia\LaravelGoogleDrive\Handler\FileHandler;

class ListContent {

    private $path;

    private $listContents;

    private $filter;

    private $recursive = false;

    public function __construct($path)
    {
        $this->path = $path;

        $this->generateListContents();
    }

    public function filter($filter = null)
    {
        $this->filter = $filter;

        return $this;
    }

    private function generateListContents()
    {
        // If the directory is exists, then, get all subdirectories and files
        $ls = collect(Storage::cloud()->listContents($this->path, $this->recursive));

        if(is_callable($this->filter)) {
            $ls = $this->filter($ls);
        }

        $this->listContents = $ls;
    }

    public function dir($name, $index = 0)
    {
        $dirs = $this->listContents->where('type', '=', 'dir')
                    ->where('filename', '=', $name);

        if($index >= $dirs->count())
            die('No such directory !');

        $dir = $dirs[$dirs->keys()[$index]];

        $fileHandler = new DirectoryHandler();
        $fileHandler->path = $dir['path'];

        return $fileHandler;
    }

    public function file($filename, $extension, $index = 0)
    {
        $file = $this->listContents->where('type', '=', 'file')
                    ->where('filename', '=', $filename)
                    ->where('extension', '=', $extension);

        if($index >= $file->count())
            die('No file !');

        $file = $file[$file->keys()[$index]];

        $fileHandler = new FileHandler();
        $fileHandler->path = $file['path'];

        return $fileHandler;
    }
    
    public function toArray()
    {
        return $this->listContents->toArray();
    }

    public function toCollect()
    {
        return $this->listContents;
    }

    public function toObject()
    {
        $collect = [];

        foreach($this->listContents as $contents) {
            if($contents['type'] === 'dir') {
                $dirObject = new DirectoryHandler();
                $dirObject->path = $contents['path'];
                array_push($collect, $dirObject);
            } else if( $contents['type'] === 'file') {
                $fileObject = new FileHandler();
                $fileObject->path = $contents['path'];
                array_push($collect, $fileObject);
            }
        }

        return collect($collect);
    }

}
