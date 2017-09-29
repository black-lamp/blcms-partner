<?php
namespace bl\cms\partner\frontend\models;

use bl\cms\partner\common\base\UploadFileModel;

/**
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class UploadImage extends UploadFileModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['file'], 'file',
                'extensions' => ['jpg', 'jpeg', 'png', 'svg'],
                'maxFiles' => 1
            ]
        ];
    }
}