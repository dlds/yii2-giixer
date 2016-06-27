<?php

namespace dlds\giixer\components\traits;

trait GxFileUploadTrait {

    /**
     * Runs saving of uploaded files
     * @return boolean
     */
    public function runUpload()
    {
        $files = $this->getFileHolder();

        if (!is_array($files))
        {
            $files = [$files];
        }

        if ($this->validate())
        {
            $paths = [];

            foreach ($files as $key => $file)
            {
                $paths[$key] = $this->getFilePath($file);

                $file->saveAs($paths[$key], true);
            }

            return (1 == count($paths)) ? array_pop($paths) : $paths;
        }

        return false;
    }

    /**
     * Retrieves filename
     * @param \yii\web\UploadedFile $file
     */
    protected function getFileName(\yii\web\UploadedFile $file)
    {
        return sprintf('%s/%s', $file->baseName, $file->extension);
    }

    /**
     * Retrieves file path destination
     * @param \yii\web\UploadedFile $file
     * @return string
     */
    protected function getFilePath(\yii\web\UploadedFile $file, $createDir = false)
    {
        $dir = $this->getUploadDir();

        if (!file_exists($dir))
        {
            @mkdir($dir, 0755, true);
        }

        return sprintf('%s/%s', $dir, $this->getFileName($file));
    }

    /**
     * Retrieves class property holding uploded file or files
     */
    abstract protected function getFileHolder();

    /**
     * Retrieves upload dir path where files will be saved
     */
    abstract protected function getUploadDir();
}