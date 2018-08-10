var croppr;

function handleFileSelect(evt) {
    var file = evt.target.files; // FileList object
    var f = file[0];
    // Only process image files.
    if (!f.type.match('image.*')) { 
        alert("Image only please....");
    }else{
        var reader = new FileReader();
        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
                // Render thumbnail.
                var img = document.getElementById(uraankhay_imgcropper_filepreview_wrapper_id);
                img.innerHTML = [
                    '<p><img class="thumb" id="', uraankhay_imgcropper_id, '" title="', escape(theFile.name), '" src="', e.target.result, '" /></p>'+
                    '<p><span class="btn" onclick="getUraankhayImgCrop();">Обрезать</span></p>'
                ].join('');
                croppr = new Croppr("#"+uraankhay_imgcropper_id, uraankhay_imgcropper_settings);
            };
        })(f);
        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    }
}

document.getElementById(uraankhay_imgcropper_fileinput_id).addEventListener('change', handleFileSelect, false);

function getUraankhayImgCrop(){
    var obj = croppr.getValue();
    var formData = new FormData();
    // Attach file
    formData.append('_csrf-backend', yii.getCsrfToken());
    formData.append('x', obj.x);
    formData.append('y', obj.y);
    formData.append('width', obj.width);
    formData.append('height', obj.height);
    formData.append('file', $('input[type=file]')[0].files[0]);

    $.ajax({
        url: uraankhay_imgcropper_url,
        type: 'POST',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false, 
    }).done(function (response) {
        console.log(response);
        getUraankhayImgUpdate(response.filelink);
    });
}
function getUraankhayImgDelete(id){
    console.log(id);
    var modelPhotoInput = document.getElementById(id);
    modelPhotoInput.value = "";
    var modelThumbnail = document.getElementById(uraankhay_imgcropper_modelThumbnail_id);
    modelThumbnail.src = "";
}
function getUraankhayImgUpdate(data){
    console.log("getUraankhayImgUpdate:" + uraankhay_imgcropper_model_attribute_field_id);
    var modelPhotoInput = document.getElementById(uraankhay_imgcropper_model_attribute_field_id);
    modelPhotoInput.value = data;
    var modelThumbnail = document.getElementById(uraankhay_imgcropper_modelThumbnail_id);
    modelThumbnail.src = data;
}