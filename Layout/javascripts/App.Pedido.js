App.Pedido = {
    Produtos : [],
    Cliente : {},
    Soma : 0,
    Desconto : 0,
    Saldo : 0,
    Buscar : function() {
        var val = $('#buscarPedido').val();
        $.post(App.BasePath+'service/pedido/buscar',{'pesquisa':val},function(data) {
            $("#listaPedidos").html(data);
        });
    },
    AdicionaItem : function(item) {
        App.Pedido.Produtos[item.input.replace("produto","")] = item;
        this.Soma = 0;
        for(var i=0;i<this.Produtos.length;i++) {
            this.Soma += Number(this.Produtos[i].preco);
        }
        $("#montante").val(App.Util.Moeda(this.Soma));
        this.Descontar($("#desconto").val());
    },
    Descontar : function(num) {
        var number = num == ""? 0+"" : num+"";
        number = Number(number.replace(',','.'))/100;
        this.Desconto = number;
        this.Saldo = (this.Soma*(1-this.Desconto));
        $("#saldo").val(App.Util.Moeda(this.Saldo));
    },
    OptionTemplate : function(name,num,option,checked) {
        var isChecked = checked?"checked":"";
        return ''+
        '<div class="checkbox">'+
            '<label>'+
                '<input type="radio" value="'+num+'" name="'+name+'" '+isChecked+ ">"+
                option +
            '</label>'+
        '</div>';
    },
    RendenizarContatos : function(arr,value) {
        var html = "<h5 style='margin-bottom:0'><label>Contato *</label></h5>";
        $('#contatoEscolha').html(html);
        var checked = undefined;
        var Cliente = App.Util.LerCookie('cliente');
        if(arr.length==0) {
            if(Cliente.contatos.length>0) {
                var cont = Cliente.contatos;
                for (var i=0;i<cont.length;i++) {
                    checked = cont[i].id == Cliente.contatoId?true:false;
                    var option = "<strong>"+cont[i].nome+"</strong><br/>"+cont[i].email+"<br/>"+cont[i].telefone;
                    html += this.OptionTemplate('contato',i,option,checked);
                }
            } else {
                html += "Não há contatos cadastrados.";
            }
        } else {
            for(var i=0;i<arr.length;i++) {
                checked = arr[i].contato_id == Cliente.contatoId?true:false;
                var option = arr[i];
                option = "<strong>"+arr[i].nome+"</strong><br/>"+arr[i].email+"<br/>"+arr[i].telefone;
                html += this.OptionTemplate('contato',i,option,checked);
            }
        }
        html += '<div class="row marginbottom">' + 
        '<div class="col-md-12">' +
        '<a href="'+App.BasePath+'service/contato/inserir" data-toggle="modal" data-target="#remoteModal" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Adicionar contato</a>' +
        '</div>' + 
        '</div>';
        $("#contatoEscolha").html(html).css('display','block');
    },
    RendenizarEnderecos : function(arr,value) {
        var html = "<h5 style='margin-bottom:0'><label>Endereço *</label></h5>";
        $('#enderecoEscolha').html(html);
        var checked = undefined;
        for(var i=0;i<arr.length;i++) {
            checked = arr[i].id == Cliente.enderecoId?true:false;
            var option = ""+arr[i].logradouro+" "+arr[i].numero+"<br/>"+arr[i].cep+" - "+arr[i].bairro+"<br/>"+arr[i].cidade+"/"+arr[i].estado+"";
            html += this.OptionTemplate('endereco',i,option,checked);
        }
        html += '<div class="row marginbottom">'+
        '<div class="col-md-12">'+
        '<a href="'+App.BasePath+'service/endereco/inserir" data-toggle="modal" data-target="#remoteModal" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Adicionar endereço</a>' +
        '</div>'+
        '</div>';
        $('#enderecoEscolha').html(html).css('display','block');
    },
    ProdutosTemplate : function () {
        var num = arguments[0];
        var id = "",value = "";
        if(arguments[1]) {
            id = arguments[1].id;
            value = arguments[1].codigo+" - "+arguments[1].nome;
        }
        return ''+
        '<div class="form-group cliente_group">'+
        '<input id="produto'+num+'" class="form-control inputTypeAhead" type="text"'+
        'placeholder="Digite o nome ou código do produto"  value="'+value+'" name="produto'+num+'"/>'+
        '<input type="hidden" id="produtoId'+num+'" name="'+num+'Id" value="'+id+'"/>'+
        '</div>'+
        '<script type="text/javascript">'+
        '$("#produto'+num+'").typeahead({hint: true,highlight: true,minLength: 1},'+
        '{source: function (query, process) {'+
        'return $.get(App.BasePath+"service/pedido/buscarproduto?produto=" + query, function (data) {'+
        'res = JSON.parse(data);'+
        'return process(res.results);});}});'+
        '$("#produto'+num+'").on("typeahead:selected", function(evt, item) {'+
        'item.input = $(this).attr("id");'+
        'App.Pedido.AdicionaItem(item);'+
        '$("#montante").val(App.Util.Moeda(App.Pedido.Soma));'+
        '$("#produtoId'+num+'").val(item.id);'+
        '});'+
        '</script>';
    },
    AdicionaProduto : function() {
        var args = arguments;
        if(args.length==0) {
            if(this.Produtos.length==0) $('#produtos').html('');
            $('#produtos').append(this.ProdutosTemplate(this.Produtos.length));
        } else {
            var prods = args[0];
            if(this.Produtos.length==0) $('#produtos').html('');
            for(var i=0;i<prods.length;i++) {
                $('#produtos').append(this.ProdutosTemplate(this.Produtos.length,prods[i]));
            }
        }
    },
    CalculaFrete : function() {
        $.post(App.BasePath+'pedido/calculafrete',{produtos:this.Produtos},function(data) {
            console.log(data);
        });
    }
}