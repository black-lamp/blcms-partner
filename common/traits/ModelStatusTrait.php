<?php
namespace bl\cms\partner\common\traits;

/**
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
trait ModelStatusTrait
{
    /**
     * Update entity status in database
     *
     * @param integer $status status ID
     * @param boolean $save if set `true` data will be save to database
     */
    public function updateStatus($status, $save) {
        $this->statusId = $status;

        if ($save) {
            $this->update(false);
        }
    }
}