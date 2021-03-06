<?php
namespace bl\cms\partner\common\helpers;

/**
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class DirectoryHelper
{
    /**
     * Check if directory existence
     *
     * @param string $path
     * @return bool returns `true` if directory exists
     */
    public static function isExists($path)
    {
        return file_exists($path);
    }

    /**
     * Create directory
     *
     * @param string $path
     * @param bool $recursive
     * @param bool $force
     * @param int $mode
     * @return bool
     */
    public static function create($path, $recursive = false, $force = false, $mode = 0777)
    {
        if($force) {
            self::delete($path);
        }

        return (!self::isExists($path)) ? mkdir($path, $mode, $recursive) : false;
    }

    /**
     * Delete directory
     *
     * @param string $path
     * @return bool
     */
    public static function delete($path)
    {
        return self::isExists($path) ? rmdir($path) : false;
    }
}
