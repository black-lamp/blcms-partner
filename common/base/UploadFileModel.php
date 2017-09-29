<?php
namespace bl\cms\partner\common\base;

use Yii;
use yii\base\Model;

use bl\cms\partner\common\helpers\DirectoryHelper;
use bl\cms\partner\common\helpers\FileHelper;

/**
 * Model class for uploading file to the server
 *
 * @property \yii\web\UploadedFile $file
 * @property string $filePrefix
 * @property string $filesRoot
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class UploadFileModel extends Model
{
    /**
     * @var \yii\web\UploadedFile
     */
    public $file;
    /**
     * @var string prefix for material files
     */
    public $filePrefix;
    /**
     * @var string path where material file will be saved
     */
    public $filesRoot;


    /**
     * @inheritdoc
     * @param string $filePrefix
     * @param string $materialsRoot
     */
    public function __construct($filePrefix, $filesRoot, $config = [])
    {
        $this->filePrefix = $filePrefix;
        $this->filesRoot = FileHelper::normalizePath(Yii::getAlias($filesRoot));

        parent::__construct($config);
    }

    /**
     * Generate a unique file name
     *
     * @return string
     */
    protected function getName()
    {
        $fileName = FileHelper::getCrc32RandomName(
            $this->file->baseName, $this->file->extension, $this->filePrefix
        );

        if(DirectoryHelper::isExists($this->filesRoot . '/' . $fileName)) {
            return $this->getName();
        }

        return $fileName;
    }

    /**
     * Uploading file to the server
     *
     * @return bool|string returns path to file if saved successfully
     */
    public function upload()
    {
        if($this->validate()) {
            DirectoryHelper::create($this->filesRoot, true);

            $fileName = $this->getName();
            $path = FileHelper::getPathToFile($this->filesRoot, $fileName);

            if($this->file->saveAs($path)) {
                return $path;
            }
        }

        return false;
    }
}