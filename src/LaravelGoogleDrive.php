<?php

namespace Smadia\LaravelGoogleDrive;

use Illuminate\Support\Facades\Storage;
use Smadia\LaravelGoogleDrive\Handlers\DirectoryHandler;
use Smadia\LaravelGoogleDrive\Handlers\FileHandler;

class LaravelGoogleDrive {

    private $rootDirectory = '/';

    /**
     * @var DirectoryHandler
     */
    private $directoryHandler;

    public function __construct()
    {
        $this->directoryHandler = new DirectoryHandler();
        $this->directoryHandler->path = $this->rootDirectory;
    }

    /**
     * Make a new directory
     *
     * @param string $dirname
     * @return DirectoryHandler
     */
    public function mkdir($dirname)
    {
        Storage::cloud()->makeDirectory($dirname);

        return new DirectoryHandler($dirname);
    }

    /**
     * Go to specified directory
     *
     * @param string $dirname
     * @param int $index
     * @return void
     */
    public function dir($dirname, $index = 0)
    {
        return new DirectoryHandler($dirname, $index);
    }

    /**
     * Create a new file
     *
     * @param string $nameWithExtension
     * @param string $fileContents
     * @return void
     */
    public function put($nameWithExtension, $fileContents)
    {
        $file = new FileHandler(
            $nameWithExtension, $fileContents
        );

        $this->directoryHandler->put($file);
    }

    /**
     * Get the list contents of root directory
     *
     * @param mixed $filter
     * @return void
     */
    public function ls($filter = null)
    {
        return $this->directoryHandler->ls($filter);
    }

    /**
     * Get specified filename from root directory
     *
     * @param string $fileName
     * @param mixed $filter
     * @return void
     */
    public function file($fileName, $extension, $index = 0)
    {
        return $this->directoryHandler->file($fileName, $extension, $index);
    }

}
