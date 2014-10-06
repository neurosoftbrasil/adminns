App = {
    BasePath : '',
    SiteKey : '',
    Util:{
        ConstruirObjeto:function(str) {
            var obj = {};
            if(str.match(/[\&]/)) {
            } else if(str.match(/[;]/)){
               var params = str.split(';');
               for(var i=0;i<params.length;i++) {
                   var param = params[i].split("=");
                   var strg = "obj."+$.trim(param[0])+" = \""+param[1]+"\"";
                   eval(strg);
               }
            }
            return obj;
        },
        LerCookie : function(str) {
            var obj = App.Util.ConstruirObjeto(document.cookie);
            if(obj[str] != undefined) {
                return JSON.parse(unescape(obj[str]));
            }
            return obj;
        },
        EscreverCookie : function(str,obj) {
            var nome = App.SiteKey;
            var data = new Date((new Date().getTime()) + (1000 * 60 * 60 * 24)).toUTCString();
            var cookie = str+"="+escape(JSON.stringify(obj))+";";
            cookie += "expires="+data+";";
            cookie += "path=/";
            document.cookie = cookie;
        },
        FormatarDocumento : function(obj) {
            var value = $(obj).val();
            value = $.trim(value.split('.').join('').split('/').join('').split('-').join(''));
            var ret = "";
            switch(value.length) {
                case 11:
                    ret += value.substring(0,3)+".";
                    ret += value.substring(3,6)+".";
                    ret += value.substring(6,9)+"-";
                    ret += value.substring(9,11);
                break;
                case 14:
                    ret += value.substring(0,2)+".";
                    ret += value.substring(2,5)+".";
                    ret += value.substring(5,8)+"/";
                    ret += value.substring(8,12)+"-";
                    ret += value.substring(12,14);
                break;
                default:
                    ret = value;
                break;
            }
            $(obj).val(ret);
        },
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
    Cliente: {
        Recuperar:function() {
            var json = App.Util.LerCookie('cliente');
            $('#nome').val(json.Nome);
            $('#documento').val(json.Documento);
            $('#site').val(json.Site);
        },
        Info:{
            Nome:"",
            Documento:"",
            Site:""
        },
        SalvarEndereco:function() {
            var Cliente = App.Util.LerCookie('cliente');
            if(Cliente.enderecos.length == 0) {
                $('#errorMessage').removeClass('hide');
                $('#errorMessage').html('O cliente deve ter pelo menos um endereço.');
            } else {
                location.href = App.BasePath+"cliente/contato";
            }
        },
        Submit:function() {
            var erros = [];
            var Nome = $.trim($('#nome').val());
            var Documento = $.trim($('#documento').val());
            var Site = $.trim($('#site').val());
            if(Nome == "") {
                 $('.nome_group').addClass('has-error');
                 erros.push('nome');
            }
            if(
                !Documento.match(/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[0-9]{2}$/) &&
                !Documento.match(/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\/[0-9]{4}\-[0-9]{2}$/)
            ) {
                 $('.documento_group').addClass('has-error');
                 erros.push('documento');
            }
            if(erros.length>0) {
                $('.haerros').removeClass('hide');
                $('#'+erros[0]).focus();
            } else {
                this.Info.Nome = Nome;
                this.Info.Documento = Documento;
                this.Info.Site = Site;
                var Cliente = App.Util.LerCookie('cliente');
                if(Cliente && Cliente.enderecos) {
                    App.Cliente.Info.enderecos = Cliente.enderecos;
                }
                App.Util.EscreverCookie('cliente',App.Cliente.Info);
                $('#formCliente').submit();
            }
        }
    },
    Endereco: {
        Salvar : function() {
            $('#errorMessage').removeClass('hide');
            $('#errorMessage').addClass('hide');
            var erros = [];
            var Cliente = App.Util.LerCookie('cliente');
            if(!Cliente.enderecos) {
                Cliente.enderecos = [];
            }
            
            var lista = Cliente.enderecos;
            var obj = {};
            obj.logradouro = $("#logradouro").val();
            obj.numero = $("#numero").val();
            obj.cep = $("#cep").val();
            obj.observacao = $("#referencia").val();
            obj.cidade = $("#cidade").val();
            obj.estado = $("#estado").val();
            obj.bairro = $("#bairro").val();
            
            var newlista = [];
            for(var i=0;i<lista.length;i++) {
                if(
                    obj.logradouro != lista[i].logradouro &&
                    obj.numero != lista[i].numero &&
                    obj.cep != lista[i].cep
                ) {
                    newlista.push(lista[i]);
                }
            }
            Cliente.enderecos = newlista;
            Cliente.enderecos.push(obj);
            if(Cliente.enderecos.length != 0) {
                App.Util.EscreverCookie('cliente',Cliente);
            }
            this.Rendenizar();
        },
        Rendenizar:function() {
            var Cliente = App.Util.LerCookie('cliente');
            if(!Cliente.enderecos) {
                Cliente.enderecos = [];
            }
            var lista = Cliente.enderecos;
            var html = "";
            for(var i=0;i<lista.length;i++) {
                var obj = lista[i];
                html += "<li class='col-md-4'>";
                html += obj.logradouro + ", " + obj.numero + "<br/>";
                html += obj.bairro + ", CEP " + obj.cep + "<br/>";
                html += obj.cidade + "/" + obj.estado+"<br/>";
                html += "<a href='javascript:App.Endereco.Remover("+i+")'>Remover</a></li>";
            }
            if(lista.length==0) {
                html = "<li>Nenhum endereço cadastrado.</li>";
            }
            $("#enderecosLista").html(html);
        },
        Remover:function(num) {
            var Cliente = App.Util.LerCookie('cliente');
            if(!Cliente.enderecos) {
                Cliente.enderecos = [];
            }
            var lista = Cliente.enderecos;
            var list = [];
            for(var i=0;i<lista.length;i++) {
                if(i != num) {
                    list.push(lista[i]);
                }
            }
            Cliente.enderecos = list;
            App.Util.EscreverCookie('cliente',Cliente);
            this.Rendenizar();
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
                        $("#cepinvalido").addClass('hide');
                    } else {
                        $("#cep").val(cep);
                        $("#logradouro").focus();
                        $("#cepinvalido").removeClass('hide');
                    }
                });
            }
        }
    }
}
