<?php
namespace bl\cms\partner\common\db;

use yii\db\ActiveQuery;

use bl\cms\partner\common\entities\OfferStatus;

/**
 * This is the ActiveQuery class for [[\bl\cms\partner\common\entities\CommercialOffer]].
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class CommercialOfferQuery extends ActiveQuery
{
    /**
     * Select record with status `On reservation`
     *
     * @return self
     */
    public function onReservation()
    {
        return $this->andWhere(['statusId' => OfferStatus::STATUS_ON_RESERVATION]);
    }

    /**
     * Select record with status `Ordering`
     *
     * @return self
     */
    public function onOrder()
    {
        return $this->andWhere(['statusId' => OfferStatus::STATUS_ORDERING]);
    }
}