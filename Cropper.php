<?php

namespace uraankhay\imgcropper;

use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * This is just an example.
 */
class Cropper extends \yii\widgets\InputWidget
{
	public $elementId = 'uraankhay-imgcropper-crop';
    public $fileInputId = 'uraankhay-imgcropper-fileinput-id';
    public $filePreviewWrapperId = 'uraankhay-imgcropper-filepreview-wrapper-id';
    public $modelThumbnailId = 'uraankhay-imgcropper-model-thumbnail-id';
    public $uploadUrl = 'uploadImg';

	public $noPhotoImage;

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
	public $minSize = [50, 50, 'px'];

	/*
	 * The starting size of the crop region when it is initialized.
	 * Type: [width, height, unit?]
	 * Default: [100, 100, '%'] (A starting crop region as large as possible)
	 * Example: startSize: [50, 50] (A starting crop region of 50% of the image size)
	 * Note: unit accepts a value of 'px' or '%'. Defaults to '%'.
	 */
	public $startSize = [50, 50, '%'];

	/*
	 * A callback function that is called when the user starts modifying the crop region.
	 * Type: Function 
	 * Arguments: value = {x, y, width, height}
	 * Example: 
	 * onCropStart: function(value) {
	 *		console.log(value.x, value.y, value.width, value.height);
	 * }
	 */
	public $onCropStart = "function(value) {console.log(value.x, value.y, value.width, value.height);}";

	/*
	 * A callback function that is called when the crop region changes.
	 * Type: Function 
	 * Arguments: value = {x, y, width, height}
	 * Example: 
	 * onCropStart: function(value) {
	 *		console.log(value.x, value.y, value.width, value.height);
	 * }
	 */
	public $onCropMove = '(data) => { console.log("move", data); }';

	/*
	 * A callback function that is called when the user stops modifying the crop region.
	 * Type: Function 
	 * Arguments: value = {x, y, width, height}
	 * Example: 
	 * onCropEnd: function(value) {
	 *		console.log(value.x, value.y, value.width, value.height);
	 * }
	 */
	public $onCropEnd = '(data) => { console.log("end", data); }';

	/*
	 * A callback function that is called when the Croppr instance is fully initialized.
	 * Type: Function 
	 * Arguments: The Croppr instance
	 * Example: 
	 * onInitialize: function(instance) {
	 *		// do things here
	 * }
	 */
	public $onInitialize = '(instance) => { console.log(instance); }';

	/*
	 * Define how the crop region should be calculated.
	 * Type: String 
	 * Possible values: "real", "ratio", or "raw"
	 * real returns the crop region values based on the size of the image's actual sizes. This ensures that the crop region values are the same regardless if the Croppr element is scaled or not. 
	 * ratio returns the crop region values as a ratio between 0 to 1. e.g. For example, an x, y position at the center will be {x: 0.5, y: 0.5}.
	 * raw returns the crop region values as is based on the size of the Croppr element.
	 */
	public $returnMode = "real";

	// public function init()
	// {
	// 	parent::init();
	// 	if ($this->label == '') {
 //            $this->label = "Image crop";
 //        }
	// }

    public function run()
    {
        $this->uploadUrl = Url::to(['news/uploadImg']);
        $this->registerClientAssets();

        return $this->render('index', [
        	'elementId' => $this->elementId,
        	'fileInputId' => $this->fileInputId,
        	'filePreviewWrapperId' => $this->filePreviewWrapperId,
        	'modelThumbnailId' => $this->modelThumbnailId,
        	'model' => $this->model,
            'widget' => $this
        ]);
    }

    /**
     * Register widget asset.
     */
    public function registerClientAssets()
    {
        $view = $this->getView();

        $settings = '
            aspectRatio: '. Json::encode($this->aspectRatio) .',
            maxSize: '. Json::encode($this->maxSize) .',
            minSize: '. Json::encode($this->minSize) .',
            startSize: '. Json::encode($this->startSize) .',
            onCropStart: '. $this->onCropStart .',
            onCropMove: '. $this->onCropMove .',
            onCropEnd: '. $this->onCropEnd .',
            onInitialize: '. $this->onInitialize .',
            returnMode: '. Json::encode($this->returnMode) .',
        ';

        $view->registerJs('
        	var uraankhay_imgcropper_settings = {' . $settings . '}; 
        	var uraankhay_imgcropper_id = '.Json::encode($this->elementId).'; 
        	var uraankhay_imgcropper_fileinput_id = '.Json::encode($this->fileInputId).';
        	var uraankhay_imgcropper_model_attribute_field_id = '.Json::encode(Html::getInputId($this->model, $this->attribute)).';
        	var uraankhay_imgcropper_modelThumbnail_id = '.Json::encode($this->modelThumbnailId).'; 
        	var uraankhay_imgcropper_url = '.Json::encode($this->uploadUrl).';
        	var uraankhay_imgcropper_filepreview_wrapper_id = '.Json::encode($this->filePreviewWrapperId).';
        ', $view::POS_BEGIN);

        $assets = CropperAsset::register($view);
        if ($this->noPhotoImage == '') {
            $this->noPhotoImage = $assets->baseUrl . '/img/nophoto.png';
        }
    }
}
