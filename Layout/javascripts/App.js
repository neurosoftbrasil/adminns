App = {
    Modal: {
        Show: function(heading, question, okText, callback, parameters, cancelText) {
            var cancel = cancelText ? cancelText : 'Cancelar';
            var confirmModal =
                    $('<div class="modal fade" id="modalBinder" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">' +
                    '<div class="modal-dialog">' +
                    '<div class="modal-content">' +
                    '<div class="modal-header">' +
                    '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>' +
                    '<h4 class="modal-title" id="ModalLabel">' + heading + '</h4>' +
                    '</div>' +
                    '<div class="modal-body">' +
                    '<p>' + question + '</p>' +
                    '</div>' +
                    '<div class="modal-footer">' +
                    '<button type="button" class="btn btn-default" data-dismiss="modal">' + cancel + '</button>' +
                    '<button type="button" id="okButton" class="btn btn-primary" class="callbackBinder">' + okText + '</button>' +
                    '</div>');
            confirmModal.find('#okButton').click(function(event) {
                callback(parameters);
                confirmModal.modal('hide');
                $('.modal.hide.fade').remove();
            });
            confirmModal.modal('show');
        }
    },
    Usuario: {
    }
}