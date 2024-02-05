<?php


namespace Modules\Common\Traits;

use Illuminate\Http\UploadedFile;

/**
 * Métodos de informes sobre la creación y actualización del modelo.
 *
 * Trait HasImage
 */
trait HasImage
{
    public function downloadImage()
    {
        return $this->getFirstMedia('image');
    }

    public function saveImage(UploadedFile $file) : void
    {
        $this->addMedia($file)
            ->usingName('Image '.$this->name)
            ->toMediaCollection('image');
    }

    public function saveImageFromDisk($path, $disk)
    {
        $this->addMediaFromDisk($path, $disk)
            ->usingName('Image '.$this->name)
            ->preservingOriginal()
            ->toMediaCollection('image');
    }


    public function getThumbImage()
    {
        if($this->getFirstMedia('image')) {
            return $this->getFirstMedia('image')->getUrl('thumb');
        } else {
            return null;
        }
    }

}
