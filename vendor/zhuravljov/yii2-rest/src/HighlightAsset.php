<?php

namespace zhuravljov\yii\rest;

use yii\web\AssetBundle;

/**
 * Class RestAsset
 *
 * @see https://github.com/isagalaev/highlight.js
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class HighlightAsset extends AssetBundle
{
    public $sourcePath = '@zhuravljov/yii/rest/assets/hilight';
    public $css = [
        'default.min.css',
    ];
    public $js = [
        'highlight.min.js',
    ];
    public $depends = [
    ];
}