<?php

namespace Smadia\LaravelGoogleDrive;

use Illuminate\Support\Facades\Storage;

trait Property {

    private $allowedPropertyToGet = [
        'name',
        'extension',
        'namex',
        'size',
        'timestamp',
        'mime',
        'path',
        'contents',
        'dirname',
        'basename'
    ];
    
    private $allowedPropertyToSet = [
        'path'
    ];

    private $type;

    private $name;

    private $extension;

    private $namex;

    private $size;

    private $mime;

    private $path;

    private $timestamp;

    private $dirname;

    private $basename;

    private $contents;

    public function __set($property, $value)
    {
        if(in_array($property, $this->allowedPropertyToSet)) {
            $this->{$property} = $value;

            if($property === 'path') {
                $this->defineProperties();
            }
        }
    }

    public function __get($property)
    {
        if(in_array($property, $this->allowedPropertyToGet)) {
            if($property === 'contents' && $this->type === 'dir')
                return null;
                
            return $this->{$property};
        }

        return null;
    }

    private function defineProperties()
    {
        $file = collect(Storage::cloud()->listContents('/', true));

        $file = $file->where('path', '=', $this->path)
                    ->first();

        $this->namex = $file['filename'] . '.' . $file['extension'];
        $this->name = $file['filename'];
        $this->extension = $file['extension'];
        $this->size = $file['size'];
        $this->timestamp = $file['timestamp'];
        $this->dirname = $file['dirname'];
        $this->basename = $file['basename'];
        $this->type = $file['type'];
    }

}