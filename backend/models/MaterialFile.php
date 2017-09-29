<?php
namespace bl\cms\partner\backend\models;

use bl\cms\partner\common\base\UploadFileModel;

/**
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class MaterialFile extends UploadFileModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['file'], 'file',
//                'extensions' => ['txt', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'mp4', ],
                'maxFiles' => 1
            ]
        ];
    }
}