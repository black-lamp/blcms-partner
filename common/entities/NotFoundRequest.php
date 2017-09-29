<?php
namespace bl\cms\partner\common\entities;

use bl\cms\partner\common\interfaces\StatusInterface;

/**
 * Not found request class
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class NotFoundRequest implements StatusInterface
{
    /**
     * @inheritdoc
     */
    public function statusCode()
    {
        return null;
    }
}