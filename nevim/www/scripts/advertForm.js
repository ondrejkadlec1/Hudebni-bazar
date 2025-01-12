$(document).ready(function(){
    const keepImages = $('#frm-advertForm-keepImages');
    const imagesList = $('#sortable');
    let uploadedFiles = [];
    const fileForm = document.getElementById('frm-advertForm-files');
    imagesList.sortable({
        update: function () {
            updateOldOrder(keepImages, imagesList);
            uploadedFiles = updateFilesOrder(uploadedFiles, imagesList);
            updateFiles(uploadedFiles, fileForm);
        }
    });
    updateOldOrder(keepImages, imagesList);

    $('#frm-advertForm-files').change(function (event) {
        const firstIndex = uploadedFiles.length
        const newFiles = Array.from(this.files);
        const imagesArray = Array.from(JSON.parse(keepImages.attr('value')));
        // display
        newFiles.forEach(function (file, index){
            const reader = new FileReader();
            reader.onload = function (event) {
                imagesList.append(`<li class="uploaded ui-sortable-handle" data-order=${firstIndex + index}><img src="${event.target.result}" class="thumb"><div class="delete">X</div></li>`);
            };
            reader.readAsDataURL(file);
            imagesArray.push('uploaded');
        });
        keepImages.attr('value', JSON.stringify(imagesArray));
        uploadedFiles = [...uploadedFiles, ...newFiles];
    });

//  deletion
    $('.previews').on('click','.delete', function (){
        if ($(this).parent().hasClass("uploaded")) {
            const order = parseInt($(this).parent().attr('data-order'), 10);
            uploadedFiles = uploadedFiles.filter((_, index) => index !== order);
            updateFiles(uploadedFiles, fileForm);
            $('.uploaded').each(function () {
                let currentOrder = $(this).attr('data-order');
                if (currentOrder > order) {
                    $(this).attr('data-order',currentOrder - 1);
                }
            });
        }
        $(this).parent().remove();
        updateOldOrder(keepImages, imagesList);
    });
});

function updateFiles(uploadedFiles, fileInput) {
    const dataTransfer = new DataTransfer();
    uploadedFiles.forEach(file => dataTransfer.items.add(file));
    fileInput.files = dataTransfer.files
}

function updateFilesOrder (uploadedFiles, list) {
    const newUploadedFiles = [];
    list.children('.uploaded').each(function () {
        newUploadedFiles.push(uploadedFiles[parseInt($(this).attr('data-order'), 10)]);
        $(this).attr('data-order', newUploadedFiles.length - 1);
    });
    return newUploadedFiles;
}

function updateOldOrder(imagesStorage, list) {
    const imagesArray = [];
    list.children().each(function () {
        if ($(this).hasClass('uploaded')) {
            imagesArray.push('uploaded');
        }
        if ($(this).hasClass('existing')) {
            imagesArray.push($(this).attr('data-id'));
        }
    });
    imagesStorage.attr('value', JSON.stringify(imagesArray));
}