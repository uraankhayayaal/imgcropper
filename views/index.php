<?php
use yii\helpers\Html;
?>

<?= Html::activeHiddenInput($model, $widget->attribute, ['class' => 'photo-field']); ?>
<div>
	<p><?= Html::img(
	    $model->{$widget->attribute} != ''
	        ? $model->{$widget->attribute}
	        : $widget->noPhotoImage,
	    [
	    	'style' => 'max-width: 100%;',
	        'id' => $modelThumbnailId,
	        'class' => 'thumbnail',
	        'data-no-photo' => $widget->noPhotoImage,
	    ]
	); ?></p>
	<p>
		<span class="btn" onclick="getUraankhayImgDelete(<?= '\''.Html::getInputId($model, $widget->attribute).'\''; ?>)">Удалить</span>
	</p>
</div>

<p><input type="file" id="<?= $fileInputId ?>" name="file" multiple></p>
<div id="<?= $filePreviewWrapperId ?>"></div>