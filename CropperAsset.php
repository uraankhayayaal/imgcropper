<?php

namespace uraankhay\imgcropper;

use yii\web\AssetBundle;

class CropperAsset extends AssetBundle
{
    public $sourcePath = '@bower/croppr/';

    public $js = [
        'dist/croppr.min.js'
    ];

    public $css = [
        'dist/croppr.min.css'
    ];
    public $depends = [
        'uraankhay\imgcropper\CropperAppAsset',
    ];
}