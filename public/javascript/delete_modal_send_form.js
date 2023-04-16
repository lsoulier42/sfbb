$(function () {
    let deleteModal = $("#deleteModal");
    let confirmButton = $('#modalConfirm');
    deleteModal.on('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let route = button.getAttribute('data-bs-route');
        confirmButton.attr("data-target", route);
    });
    confirmButton.on('click', function (event) {
        event.preventDefault();
        let url = window.location.origin + $(this).data('target');
        let formData = {
            token: $(this).data('csrf')
        }
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            dataType: "json",
            encode: true
        }).done(function () {
            location.reload();
        }).fail(function () {
            location.reload();
        })
    });
});