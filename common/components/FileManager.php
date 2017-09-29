<?php
namespace bl\cms\partner\common\components;

use Yii;
use yii\base\Object;

use bl\cms\partner\common\helpers\FileHelper;

/**
 * File manager for work with files in partner module
 *
 * @property string $filesRoot
 * @property string $filePrefix
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class FileManager extends Object
{
    /**
     * @var string
     */
    public $filesRoot = '@frontend/web/files';
    /**
     * @var string
     */
    public $filePrefix = 'file';


    /**
     * Get full path to file by file name
     *
     * @param string $fileName
     * @return string
     */
    public function getPathToFile($fileName)
    {
        $root = Yii::getAlias($this->filesRoot);

        return FileHelper::getPathToFile($root, $fileName);
    }

    /**
     * Delete file by file name
     *
     * @param string $fileName
     * @return boolean
     */
    public function deleteFile($fileName)
    {
        $root = Yii::getAlias($this->filesRoot);

        return FileHelper::deleteFile(FileHelper::getPathToFile($root, $fileName));
    }
}