App = {
    BasePath : '',
    Util:{
        Moeda:function(num) {
            num = num + '';
            var frac = num.split(".");
            var number = "";
            if(frac[1]) {
                number += ","+App.Util.Pad(frac[1],2,true);
            } else {
                number += ",00";
            }
            number = frac[0]
            .replace(/([0-9]{3}$)/g,".$1") // mil
            .replace(/([0-9]{3}\.)/,".$1") // milhão
            .replace(/([0-9]{3}\.)/,".$1") // bilhão
            .replace(/([0-9]{3}\.)/,".$1") // trilhão
            + number;
            return "R$ " + number;
        },
        Pad:function(n,width,left) {
            var num = (n+'').substr(0,width), left = left || false;
            nleft='',nright='';
            if(left) {
                nleft = num;
            } else {
                nright = num;
            }
            return num.length >= width ? num : nleft + new Array(width - num.length + 1).join('0') + nright;
        }
    },
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
    Usuario: {},
    Perfil: {},
    Endereco: {
        Lista : [],
        Salvar : function() {
            var obj = {};
            obj.logradouro = $("#logradouro").val();
            obj.numero = $("#numero").val();
            obj.cep = $("#cep").val();
            obj.observacao = $("#referencia").val();
            obj.cidade = $("#cidade").val();
            obj.estado = $("#estado").val();
            obj.bairro = $("#bairro").val();
            this.Lista.push(obj);
            console.log(this.Lista);
        },
        BuscarCep:function(num) {
            var cep = num.split('.').join('');
            if(cep.length>7) {
                $("#formEndereco")[0].reset();
                $.get('http://apps.widenet.com.br/busca-cep/api/cep.json?code='+cep,function(j) {
                    if(j.address) {
                        $("#logradouro").val(j.address);
                        $("#bairro").val(j.district);
                        $("#cidade").val(j.city);
                        $("#estado").val(j.state);
                        $("#cep").val(j.code);
                        $("#numero").focus();
                    } else {
                        $("#cep").val(cep);
                        $("#logradouro").focus();
                    }
                });
            }
        }
    }
}