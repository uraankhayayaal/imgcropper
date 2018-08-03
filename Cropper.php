<?php

namespace uraankhay\imgcropper;

use yii\helpers\Json;

/**
 * This is just an example.
 */
class Cropper extends \yii\base\Widget
{
	public $elementId = 'crop';

	public $noPhotoImage = [];

	public $pluginOptions;
	/*
	 * Constrain the crop region to an aspect ratio.
	 * Type: Number 
	 * Default: null
	 * Example: aspectRatio: 1 (Square)
	 */
	public $aspectRatio = null;

	/*
	 * Constrain the crop region to a maximum size.
	 * Type: [width, height, unit?]
	 * Default: null
	 * Example: maxSize: [50, 50, '%'] (Max size of 50% of the image)
	 * Note: unit accepts a value of 'px' or '%'. Defaults to 'px'.
	 */
	public $maxSize = null;

	/*
	 * Constrain the crop region to a minimum size.
	 * Type: [width, height, unit?]
	 * Default: null
	 * Example: maxSize: [100, 100, 'px'] (Min width and height of 100px)
	 * Note: unit accepts a value of 'px' or '%'. Defaults to 'px'.
	 */
	public $minSize = "[50, 50, 'px']";

	/*
	 * The starting size of the crop region when it is initialized.
	 * Type: [width, height, unit?]
	 * Default: [100, 100, '%'] (A starting crop region as large as possible)
	 * Example: startSize: [50, 50] (A starting crop region of 50% of the image size)
	 * Note: unit accepts a value of 'px' or '%'. Defaults to '%'.
	 */
	public $startSize = "[100, 100, '%']";

	/*
	 * A callback function that is called when the user starts modifying the crop region.
	 * Type: Function 
	 * Arguments: value = {x, y, width, height}
	 * Example: 
	 * onCropStart: function(value) {
	 *		console.log(value.x, value.y, value.width, value.height);
	 * }
	 */
	public $onCropStart;

	/*
	 * A callback function that is called when the crop region changes.
	 * Type: Function 
	 * Arguments: value = {x, y, width, height}
	 * Example: 
	 * onUpdate: function(value) {
	 *		console.log(value.x, value.y, value.width, value.height);
	 * }
	 */
	public $onCropMove;

	/*
	 * A callback function that is called when the user stops modifying the crop region.
	 * Type: Function 
	 * Arguments: value = {x, y, width, height}
	 * Example: 
	 * onCropEnd: function(value) {
	 *		console.log(value.x, value.y, value.width, value.height);
	 * }
	 */
	public $onCropEnd;

	/*
	 * A callback function that is called when the Croppr instance is fully initialized.
	 * Type: Function 
	 * Arguments: The Croppr instance
	 * Example: 
	 * onInitialize: function(instance) {
	 *		// do things here
	 * }
	 */
	public $onInitialize;

	/*
	 * Define how the crop region should be calculated.
	 * Type: String 
	 * Possible values: "real", "ratio", or "raw"
	 * real returns the crop region values based on the size of the image's actual sizes. This ensures that the crop region values are the same regardless if the Croppr element is scaled or not. 
	 * ratio returns the crop region values as a ratio between 0 to 1. e.g. For example, an x, y position at the center will be {x: 0.5, y: 0.5}.
	 * raw returns the crop region values as is based on the size of the Croppr element.
	 */
	public $returnMode = "real";

    public function run()
    {
        $this->registerClientAssets();

        return $this->render('index', [
        ]);
    }

    /**
     * Register widget asset.
     */
    public function registerClientAssets()
    {
        $view = $this->getView();
        $assets = CropperAsset::register($view);

        if ($this->noPhotoImage == '') {
            $this->noPhotoImage = $assets->baseUrl . '/img/nophoto.png';
        }

        $settings = array_merge([
            'aspectRatio' => $this->aspectRatio,
            'maxSize' => $this->maxSize,
            'minSize' => $this->minSize,
            'startSize' => $this->startSize,
            'onCropStart' => $this->onCropStart,
            'onCropMove' => $this->onCropMove,
            'onCropEnd' => $this->onCropEnd,
            'onInitialize' => $this->onInitialize,
            'returnMode' => $this->returnMode,
        ], $this->pluginOptions);

        if(is_numeric($this->aspectRatio)) {
                $settings['aspectRatio'] = $this->aspectRatio;
        }

        if ($this->onCropEnd)
            $settings['onCropEnd'] = $this->onCropEnd;

        $view->registerJs('
croppr = new Croppr("#' . $this->elementId . '",
  	' . Json::encode($settings) . '
);
		', 
			$view::POS_READY
        );

        $view->registerJs('
var croppr;
function getCrop(){
	var obj = croppr.getValue();
	return JSON.stringify(obj);
}
        	', 
			$view::POS_END
        );
    }
}
