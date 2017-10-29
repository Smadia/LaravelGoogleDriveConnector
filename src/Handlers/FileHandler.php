<?php

namespace Smadia\LaravelGoogleDrive\Handlers;

use Illuminate\Support\Facades\Storage;
use Smadia\LaravelGoogleDrive\Handlers\DirectoryHandler;
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

    /**
     * Check whether the file exists
     *
     * @return boolean
     */
    public function isExist()
    {
        return $this->exists;
    }

    /**
     * Generate whether the file or directory is exists
     *
     * @return void
     */
    private function generateExist()
    {
        $file = Storage::cloud()->get($this->filePath);

        if(is_null($file)) {
            $this->exists = false;
        }
    }

    /**
     * Rename current file
     *
     * @param string $newname
     * @return void
     */
    public function rename($newname)
    {
        Storage::cloud()->move($this->path, $this->dirname . '/' . $newname);
    }

    /**
     * Delete current file
     *
     * @return void
     */
    public function delete()
    {
        if($this->isExist())
            Storage::cloud()->delete($this->filePath);
    }

    /**
     * Get the parent directory
     *
     * @return DirectoryHandler
     */
    public function getDir()
    {
        $directoryHandler = new DirectoryHandler();
        $directoryHandler->path = $this->dirname;

        return $directoryHandler;
    }

}