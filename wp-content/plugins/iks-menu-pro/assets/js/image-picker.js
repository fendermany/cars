'use strict';

jQuery(document).ready(function ($j) {
    initImagePicker();
});

function initImagePicker() {
    var attrs = ImagePickerLocalization;
    if (attrs.length <= 1) {
        console.error('Not found localization in Image Picker');
        return;
    }

    var $ = jQuery;
    var pickerSelector = attrs['pickerSelector'];

    // Uploading files
    var file_frame;

    // For each picker on page
    $(pickerSelector).each(function () {
        var $picker = $(this);
        var $input = $picker.find('input');
        var $image = $picker.find('img');
        var $uploadButton = $picker.find(attrs['uploadButtonSelector']);
        var $removeButton = $picker.find(attrs['removeButtonSelector']);

        // Process remove button visibility
        if (!parseInt($input.val())) {
            $removeButton.length > 0 && $removeButton.hide();
        }

        // On click Upload button
        $uploadButton.on('click', function (event) {
            event.preventDefault();

            // Create the media frame, if it not exists
            if (!file_frame) {
                file_frame = wp.media.frames.downloadable_file = wp.media({
                    title: attrs['mediaTitle'],
                    button: {
                        text: attrs['mediaButtonTitle']
                    },
                    multiple: false
                });
            }

            // When an image is selected, run a callback.
            file_frame.off('select').on('select', function () {
                var attachment = file_frame.state().get('selection').first().toJSON();
                var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                $input.val(attachment.id);
                $image.attr('src', attachment_thumbnail.url);
                $removeButton.show();
            });

            // Finally, open the modal.
            file_frame.open();
        });

        // On click Remove button
        $removeButton.on('click', function () {
            $image.attr('src', attrs['placeholderURL']);
            $input.val('');
            $(this).hide();
            return false;
        });
    });
}