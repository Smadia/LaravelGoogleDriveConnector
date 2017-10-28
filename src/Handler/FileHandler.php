<?php

namespace Smadia\LaravelGoogleDrive\Handler;

use Illuminate\Support\Facades\Storage;
use Smadia\LaravelGoogleDrive\Handler\DirectoryHandler;
use Smadia\LaravelGoogleDrive\Property;
use Smadia\LaravelGoogleDrive\Action;

class FileHandler implements Action {

    use Property;

    private $exists = true;

    public function __construct($nameWithExtension = null, $fileContents = null)
    {
        if(!is_null($nameWithExtension) && !is_null($fileContents)) {
            $this->namex = $nameWithExtension;
            $this->contents = $fileContents;
        }
    }

    public function isExists()
    {
        return $this->exists;
    }

    private function generateExist()
    {
        $file = Storage::cloud()->get($this->filePath);

        if(is_null($file)) {
            $this->exists = false;
        }
    }

    public function rename($newname)
    {
        Storage::cloud()->move($this->path, $this->dirname . '/' . $newname);
    }

    public function delete()
    {
        if($this->isExists())
            Storage::cloud()->delete($this->filePath);
    }

    public function getDir()
    {
        return new DirectoryHandler(
            $this->dirname
        );
    }

}