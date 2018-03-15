var Vulcan = {
    Bitly: {
        initEvents: function () {
            var _this = this;

            jQuery('#bitlyCopyUrl').on('click', function () {
                var url = jQuery(this).attr('data-href');
                _this.copyUrl(url);
            });

            jQuery('.field.urlsegment .update').on('click', function () {
                jQuery('#Form_EditForm_BitlyURL_Holder').find('.preview-holder').html("<div style='margin-top: 8px;'>URL segment has changed, waiting for you to save.</div>")
            });

            if (jQuery('.bitly-field').length > 0) {
                var $desc = jQuery('.field.urlsegment .edit-holder .form__field-description').clone();
                $desc.html("<strong>Warning</strong>: Changing your URL segment will force your Bitly URL to update and unfortunately the click count will reset as Bitly does not allow you to edit links.");
                jQuery('.field.urlsegment .edit-holder').append($desc);
            }

            jQuery('#bitlyRefresh').on('click', function () {
                _this.updateClicks();
            });
        },
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

Vulcan.Bitly.initEvents();
