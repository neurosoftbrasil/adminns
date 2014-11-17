App = {
    Events: {
        Load:function() {
           
        }
    },
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
            num = frac[0];
            if(num.length>4) {
                num = num.replace(/(\d+)(\d{3})/,"$1.$2");
                num = num.replace(/(\d+)(\d{3})/,"$1.$2");
                num = num.replace(/(\d+)(\d{3})/,"$1.$2");
            }
            return "R$ " + num + number;
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
        Buscar : function() {
            var pesq = $('#buscarCliente').val();
            console.log(pesq);
            $.post(App.BasePath+'service/cliente/buscar',{"pesquisa":pesq},function(data) {
                $('#listaCliente').html(data);
            });
        },
        SalvarEndereco:function(id) {
            var Cliente = App.Util.LerCookie('cliente');
            var ident = id || false;
            if(ident) {
                Cliente.id = ident;
                App.Util.EscreverCookie('cliente',Cliente);
            }
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
                if(Cliente && Cliente.enderecos) {
                    App.Cliente.Info.contatos = Cliente.contatos;
                }
                App.Util.EscreverCookie('cliente',App.Cliente.Info);
                $('#formCliente').submit();
            }
        },
        Salvar : function() {
            var cliente = App.Util.LerCookie('cliente');
            console.log(cliente);
            $.post(App.BasePath+'service/cliente/salvar',cliente,function(data) {
                var j = JSON.parse(data);
                if(j.status == "success" && j.redirect) {
                    location.href = j.redirect;
                }
            });
        }
    },
    Contato : {
        Email:[],
        Telefone:[],
        Editar : function(num) {
            $("#remoteModal .modal-content").load(App.BasePath+"/service/contato/editar/"+num);
            $("#remoteModal").modal();
        },
        Remover : function(num) {
            var cliente = App.Util.LerCookie('cliente');
            var contatos = cliente.contatos, cont = [];
            for(var i=0;i<contatos.length;i++) {
                if(i != num) {
                    cont.push(contatos[i]);
                }
            }
            cliente.contatos = cont;
            App.Util.EscreverCookie('cliente',cliente);
            this.Rendenizar();
        },
        EmailTemplate : function (num) {
            return ''+
            '<div class="form-group email_group email_'+num+'">'+
            '<input id="email'+num+'" class="form-control inputTypeAhead" type="text"'+
            'placeholder="Digite o e-mail"  value="" name="email'+num+'" onblur="App.Contato.SalvarEmail(this)"/>'+
            '</div>';
        },
        AdicionaEmail : function() {
            if($("#email"+this.Email.length).val() != "") {
                $('#email').append(this.EmailTemplate(this.Email.length));
            }
        },
        SalvarEmail : function(obj) {
            var email = $(obj).val();
            if(email.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
                var emails = [];
                for(var i=0;i<this.Email.length;i++) {
                    if(email != this.Email[i]) {
                        emails.push(this.Email[i]);
                    }
                }
                this.Email = emails;
                this.Email.push(email);
            } else {
                $(obj).val('');
                $(obj).focus();
            }
        },
        TelefoneTemplate : function (num) {
            return ''+
            '<div class="form-group telefone_group telefone_'+num+'">'+
            '<input id="telefone'+num+'" class="form-control inputTypeAhead" type="text"'+
            'placeholder="+55 (00) 0000-00000"  value="" name="telefone'+num+'" onblur="App.Contato.SalvarTelefone(this)"/>'+
            '</div>';
        },
        AdicionaTelefone : function() {
            if($("#telefone"+this.Telefone.length).val() != "") {
                $('#telefone').append(this.TelefoneTemplate(this.Telefone.length));
            }
        },
        SalvarTelefone : function(obj) {
            var tel = $(obj).val();
            if($.trim(tel) != "") {
                var tels = [];
                for(var i=0;i<this.Telefone.length;i++) {
                    if(tel != this.Telefone[i]) {
                        tels.push(this.Telefone[i]);
                    }
                }
                this.Telefone = tels;
                this.Telefone.push(tel);
            } else {
                $(obj).val('');
                $(obj).focus();
            }
        },
        Rendenizar : function() {
            var cookie = App.Util.LerCookie('cliente');
            var ct = cookie.contatos;
            var html = "";
            for(var i=0;i<ct.length;i++) {
                html += "<div class='hover'><div class='col-md-12'><li><b>"+ct[i].nome+"</b>";
                
                html += "</div>";
                html += "<div class='col-md-6'><h6>E-mails:</h6>";
                html += "<ul>";
                var c = ct[i];
                for(var j=0;j<c.emails.length;j++) {
                    html += "<li>"+c.emails[j]+"</li>";
                }
                html += "</ul></div>";
                html += "<div class='col-md-6'><h6>Telefones:</h6>";
                html += "<ul>";
                for(var j=0;j<c.tels.length;j++) {
                    html += "<li>"+c.tels[j]+"</li>";
                }
                html += "</ul><br/>";
                html += "</div>";
                html += "<div style='clear:both'>";
                html += "<a style='margin:0 5px' href='javascript:void(0)' class='btn btn-default btn-sm' onclick='App.Contato.Editar(\""+ct[i].id+"\")'><span class='glyphicon glyphicon-pencil'></span> Editar</a>";
                html += "<a href='javascript:void(0)' class='btn btn-default btn-sm' onclick='App.Contato.Remover("+i+")'><span class='glyphicon glyphicon-pencil'></span> Remover</a>";
                html += "</div></div>";
            }
            $("#contatosLista").html(html);
        },
        Salvar : function () {
            var errors = [];
            var obj = {};
            obj.nome = $('#nome').val();
            obj.cargo = $('#cargo').val();
            obj.aniversario = $("#aniversario").val();
            obj.contato_tipo_id = $('#contato_tipo_id').val();
            obj.cliente_endereco_id = $('#cliente_endereco_id').val();
            obj.referencia = $('#referencia').val();
            obj.complemento = $('#complemento').val();
            
            if($.trim(obj.nome) == "") {
                errors.push("nome");
            }
            if($.trim(obj.contato_tipo_id) == 0) {
                errors.push("contato_tipo_id");
            }
            if(this.Email.length==0) {
                errors.push('email0');
            }
            if(this.Telefone.length==0) {
                errors.push('telefone0');
            }
            if(errors.length == 0) {
                obj.emails = [];
                obj.tels = [];
                for(var i=0;i<this.Email.length;i++) {
                    obj.emails.push($("#email"+i).val());
                }
                for(var i=0;i<this.Telefone.length;i++) {
                    obj.tels.push($('#telefone'+i).val());
                }
                var cookie = App.Util.LerCookie('cliente');
                if(!cookie.contatos) {
                    cookie.contatos = [];
                }
                var cont = [];
                
                for(var i=0;i<cookie.contatos.length;i++) {
                    if(
                       obj.nome != cookie.contatos[i].nome &&
                       obj.contato_tipo_id != cookie.contatos[i].contato_tipo_id &&
                       obj.cliente_endereco_id != cookie.contatos[i].cliente_endereco_id
                    ) {
                        cont.push(cookie.contatos[i]);
                    }
                }
                cookie.contatos = cont;
                cookie.contatos.push(obj);
                App.Util.EscreverCookie('cliente',cookie);
                App.Contato.Rendenizar();
                
                $("#remoteModal").modal('hide');
            } else {
                $('.form-group').removeClass('has-error');
                $('.'+errors[0]+'_group').addClass('has-error');
                $('#'+errors[0]).focus();
            }
        }
    },
    Endereco : {
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
            obj.referencia = $("#referencia").val();
            obj.complemento = $("#complemento").val();
            obj.cidade = $("#cidade").val();
            obj.estado = $("#estado").val();
            obj.bairro = $("#bairro").val();
            obj.cliente_endereco_tipo_id = $("#cliente_endereco_tipo_id").val();
            
            if($.trim(obj.logradouro) == "") {
                erros.push('logradouro');
            }
            if($.trim(obj.numero) == "") {
                erros.push('numero');
            }
            if($.trim(obj.cep) == "") {
                erros.push('cep');
            }
            if($.trim(obj.cidade) == "") {
                erros.push('cidade');
            }
            if($.trim(obj.estado) == "") {
                erros.push('estado');
            }
            if($.trim(obj.bairro) == "") {
                erros.push('bairro');
            }
            if(obj.cliente_endereco_tipo_id == "0") {
                erros.push('cliente_endereco_tipo_id');
            }
            if(erros.length==0) {
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
                $("#remoteModal").modal('hide');
                this.Rendenizar();
            } else {
                $('#'+erros[0]).focus();
                this.Rendenizar();
            }
            console.log(Cliente);
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

    },
    Usuario: {},
    Perfil: {},
    Pedido : {}
}
