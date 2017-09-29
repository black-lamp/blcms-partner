<?php
namespace bl\cms\partner\common\interfaces;

/**
 * Status interface for models with status column
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
interface StatusInterface
{
    /**
     * @return integer
     */
    public function statusCode();
}