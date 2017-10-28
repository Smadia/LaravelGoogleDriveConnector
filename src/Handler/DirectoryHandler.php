<?php

namespace Smadia\LaravelGoogleDrive\Handler;

use Illuminate\Support\Facades\Storage;
use Smadia\LaravelGoogleDrive\Handler\FileHandler;
use Smadia\LaravelGoogleDrive\Action;
use Smadia\LaravelGoogleDrive\Property;
use Smadia\LaravelGoogleDrive\ListContent;

class DirectoryHandler implements Action {

    use Property;

    /**
     * List contents of current directory
     *
     * @var Smadia\LaravelGoogleDrive\ListContent|null
     */
    private $listContents;

    private $exists = true;

    private $recursive = false;
    
    public function __construct($name = null)
    {
        $this->name = $name;
        
        $this->generateDirectoryPath();

        if($this->isExists())
            $this->listContents = new ListContent($this->path);
    }

    /**
     * This method will generate the path of requested directory name
     *
     * @return void
     */
    private function generateDirectoryPath()
    {
        // Get the list of requested directory
        // We will get the path of the director from the last dir of the array
        // e.g : the requested directory is home/image/group
        // we will explode until we have the group's path directory
        $listOfDirectory = explode('/', $this->name);

        $counter = 0;

        // Get the list contents of the root directory
        $__ls = collect(Storage::cloud()->listContents('/', $this->recursive));

        // Loop for generate the path
        while($counter < count($listOfDirectory)) {
            if($listOfDirectory[$counter] !== '') {
                $dir = $__ls->where('type', '=', 'dir')
                            ->where('filename', '=', $listOfDirectory[$counter])
                            ->first();

                if(is_null($dir)) {
                    $this->exists = false;
                    break;
                }

                $this->path = $dir['path'];
                
                $__ls = collect(Storage::cloud()->listContents($this->path, $this->recursive));
            }
            
            $counter++;
        }

        $this->defineProperties();
    }

    private function generateExists()
    {
        $ls = collect(Storage::cloud()->listContents('/', false));
        $dir = $ls->where('type', '=', 'dir');

        $this->exists = $dir->count() > 0;
    }

    public function put($filename, $contents = null)
    {
        if($this->isExists()) {
            if(is_string($filename)) {
                $this->putAsFile($filename, $contents);
            }
            else {
                $this->putAsFileHandler($filename);
            }
        }
    }

    private function putAsFileHandler(FileHandler $file)
    {
        Storage::cloud()->put(
            $this->path . '/' . $file->namex(), $file->getContents()
        );
    }

    private function putAsFile($filename, $contents)
    {
        Storage::cloud()->put(
            $this->path . '/' . $filename, $contents
        );
    }

    public function isExists()
    {
        return $this->exists;
    }

    public function rename($newname)
    {
        if($this->isExists())
            Storage::cloud()->move($this->path, $this->dirname . '/' . $newname);
    }

    public function delete()
    {
        if($this->isExists())
            Storage::cloud()->deleteDirectory($this->path);
    }

    public function dir($dirname, $index = 0)
    {
        $directory = $this->listContents->dir($dirname, $index);

        return $directory;
    }

    /**
     * Get the list content of requested directory
     *
     * @param boolean $filter
     * @return Smadia\LaravelGoogleDrive\ListContent|null
     */
    public function ls($filter = null)
    {
        if($this->isExists())
            return $this->listContents->filter($filter);

        return null;
    }

    public function file($filename, $extension, $index = 0)
    {
        return $this->listContents->file($filename, $extension, $index);
    }

    public function mkdir($dirname)
    {
        $name = $this->path . '/' . $dirname;
        $create = Storage::cloud()->makeDirectory($this->path . '/' . $dirname);

        $directory = new DirectoryHandler();
        $directory->path = $name;

        return $directory;
    }

}