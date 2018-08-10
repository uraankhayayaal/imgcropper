Yii2 image cropper based on Croppr.js
=====================================
Yii2 image cropper based on Croppr.js

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist uraankhay/yii2-imgcropper "*"
```

or add

```
"uraankhay/yii2-imgcropper": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \uraankhay\imgcropper\Cropper::widget(); ?>
```

```php
<?= $form->field($model, 'photo')->widget(\uraankhay\imgcropper\Cropper::className(), [
        'aspectRatio' => 500/700,
        'maxSize' => [700, 500, 'px'],
        'minSize' => [10, 10, 'px'],
        'startSize' => [100, 100, '%'],
    ]); ?>
```