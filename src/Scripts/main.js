(function(){
    'use strict';

    window.jQuery = window.$ = require('jquery');
    require('bootstrap');
    require('jquery-ui/datepicker');
    require('jquery.ui.widget');
    require('jquery.iframe-transport');
    require('jquery.fileupload');

    var message = 'Hello App';
    console.log(message);

    $(".datepicker").datepicker({
        dateFormat: "yy-mm-dd"
    });

    $("#myModal").on('shown.bs.modal', function (e) {
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd"
        });
    })

    $("#myModal").on('hidden.bs.modal', function (e) {
        $(this).removeData();
    });

        $('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo('#files');
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

})();
