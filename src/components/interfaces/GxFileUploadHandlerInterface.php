<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\interfaces;

/**
 * This is interfaces classs which defines method for GxFileUploadHandlerTrait
 */
interface GxFileUploadHandlerInterface
{

    /**
     * Runs saving of uploaded files
     * @return boolean
     */
    public function runUpload();
    
    /**
     * Retrieves filename
     * @param \yii\web\UploadedFile $file
     */
    public function getFileName(\yii\web\UploadedFile $file);
    
    /**
     * Retrieves file path destination
     * @param \yii\web\UploadedFile $file
     * @return string
     */
    public function getFilePath(\yii\web\UploadedFile $file, $createDir = false);
    
    /**
     * Retrieves class property holding uploded file or files
     */
    public function getFileHolder();

    /**
     * Retrieves upload dir path where files will be saved
     */
    public function getUploadDir();
    
    
}
