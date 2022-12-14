jQuery(document).ready(function($) {
    $("#reviewsForm").submit(function (event) {
        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "post",
            url: url,
            data: form.serialize()
        });
    });
});