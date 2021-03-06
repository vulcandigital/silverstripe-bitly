jQuery.entwine('ss', function ($) {
    jQuery('#bitlyCopyUrl').entwine({
        onclick: function () {
            var url = jQuery(this).attr('data-href');
            Vulcan.Bitly.copyUrl(url);
        }
    });

    jQuery('.bitly-field').entwine({
        onmatch: function () {
            var $desc = jQuery('.field.urlsegment .edit-holder .form__field-description').clone();
            $desc.html("<strong>Warning</strong>: Changing your URL segment will force your Bitly URL to update and unfortunately the click count will reset as Bitly does not allow you to edit links.");
            jQuery('.field.urlsegment .edit-holder').append($desc);
        }
    });

    jQuery('.field.urlsegment .update').entwine({
        onclick: function () {
            jQuery('#Form_EditForm_BitlyURL_Holder').find('.preview-holder').html("<div style='margin-top: 8px;'>URL segment has changed, waiting for you to save.</div>")
        }
    });

    jQuery('#bitlyRefresh').entwine({
        onclick: function () {
            Vulcan.Bitly.updateClicks();
        }
    });
});

var Vulcan = {
    Bitly: {
        copyUrl: function (url) {
            var input = document.createElement('input');
            input.setAttribute('value', url);
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            jQuery('#bitlyCopyUrl').html("Copied!");
            setTimeout(function () {
                jQuery('#bitlyCopyUrl').html("Copy URL")
            }, 2000)
        },
        updateClicks: function () {
            var id = jQuery('#bitlyCopyUrl').attr('data-bitly-id');
            var $refreshBtn = jQuery('#bitlyRefresh');

            jQuery.ajax({
                url: '/vd-bitly/refresh',
                data: {
                    id: id
                },
                beforeSend: function () {
                    $refreshBtn.html("Loading..").prop('disabled', true)
                },
                success: function (response) {
                    $refreshBtn.html(response.success ? response.data.clicks + " clicks" : 'Error');
                    $refreshBtn.prop('disabled', false);
                }
            })
        }
    }
};