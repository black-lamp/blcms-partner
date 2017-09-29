<?php
namespace bl\cms\partner\common\helpers;

/**
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class FileHelper extends \yii\helpers\BaseFileHelper
{
    /**
     * Build file name
     *
     * @param string $name
     * @param string $extension
     * @param null|string $prefix
     * @return string
     */
    public static function getFileName($name, $extension, $prefix = null)
    {
        if($prefix === null) {
            return sprintf("%s.%s", $name, $extension);
        }

        return sprintf("%s-%s.%s", $prefix, $name, $extension);
    }

    /**
     * Build random filename
     *
     * @param string $name
     * @param string $extension
     * @param null|string $prefix
     * @return string
     */
    public static function getCrc32RandomName($name, $extension, $prefix = null)
    {
        $name = abs(crc32($name . microtime()));

        if($prefix === null) {
            return static::getFileName($name, $extension);
        }

        return static::getFileName($name, $extension, $prefix);
    }

    /**
     * Build path to file
     *
     * @param string $rootPath
     * @param string $fileName
     * @return string
     */
    public static function getPathToFile($rootPath, $fileName)
    {
        return static::normalizePath(sprintf("%s/%s", $rootPath, $fileName));
    }

    /**
     * Delete file
     *
     * @param string $path Full path to file
     * @return boolean returns `true` if file was successfully remove
     */
    public static function deleteFile($path)
    {
        return unlink($path);
    }
}
