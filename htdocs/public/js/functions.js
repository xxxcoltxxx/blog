$(document).ready(function() {
    $(document).on("click", ".new-window", function(e) {
        e.preventDefault();
        var th = $(this);
        var height = th.data("height") ? th.data("height") : 400;
        var width = th.data("width") ? th.data("width") : 700;
        var left = (screen.width - width) / 2;
        var top = (screen.height - height ) / 2;
        window.open(
            th.attr("href"),
            th.data("title"),
            "height=" + height + ",width=" + width + ",resizable=true,left=" + left + ",top=" + top
        );
    });

    $(document).on("click", ".confirm", function(e) {
        var message = $(this).data("message");
        message = message ? message : "Вы уверены?";
        if (!confirm(message)) {
            e.preventDefault();
        }
    });
});

