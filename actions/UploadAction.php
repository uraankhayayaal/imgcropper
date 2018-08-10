<?php

namespace uraankhay\imgcropper\actions;

use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;
use Yii;

class UploadAction extends Action
{
    public $path;
    public $url;
    public $uploadParam = 'file';
    public $maxSize = 2097152;
    public $extensions = 'jpeg, jpg, png, gif';
    public $jpegQuality = 100;
    public $pngCompressionLevel = 1;
    public $width = 100;
    public $height = 100;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->url === null) {
            throw new InvalidConfigException('MISSING_ATTRIBUTE');
        } else {
            $this->url = rtrim($this->url, '/') . '/';
        }
        if ($this->path === null) {
            throw new InvalidConfigException('MISSING_ATTRIBUTE');
        } else {
            $this->path = rtrim(Yii::getAlias($this->path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName($this->uploadParam);
            $model = new DynamicModel(compact($this->uploadParam));
            $model->addRule($this->uploadParam, 'image', [
                'maxSize' => $this->maxSize,
                'tooBig' => 'TOO_BIG_ERROR',
                'extensions' => explode(', ', $this->extensions),
                'wrongExtension' => 'EXTENSION_ERROR'
            ])->validate();

            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError($this->uploadParam)
                ];
            } else {
                $model->{$this->uploadParam}->name = uniqid() . '.' . $model->{$this->uploadParam}->extension;
                $request = Yii::$app->request;

                $width = $request->post('width', $this->width);
                $height = $request->post('height', $this->height);
                $x = $request->post('x', 0);
                $y = $request->post('y', 0);

                $image = Image::crop(
                    $file->tempName . $request->post('filename'),
                    intval($width),
                    intval($height),
                    [$x<0?0:$x, $y<0?0:$y]
                )/*->resize(
                    new Box($width, $height)
                )*/;

                if (!file_exists($this->path) || !is_dir($this->path)) {
                    $result = [
                        'error' => 'ERROR_NO_SAVE_DIR']
                    ;
                } else {
                    $saveOptions = ['jpeg_quality' => $this->jpegQuality, 'png_compression_level' => $this->pngCompressionLevel];
                    if ($image->save($this->path . $model->{$this->uploadParam}->name, $saveOptions)) {
                        $result = [
                            'filelink' => $this->url . $model->{$this->uploadParam}->name
                        ];
                    } else {
                        $result = [
                            'error' => 'ERROR_CAN_NOT_UPLOAD_FILE'
                        ];
                    }
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;

            return $result;
        } else {
            throw new BadRequestHttpException('ONLY_POST_REQUEST');
        }
    }
}
