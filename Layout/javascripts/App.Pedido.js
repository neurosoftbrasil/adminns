App.Pedido = {
    Produtos : [],
    Cliente : {},
    Soma : 0,
    Desconto : 0,
    Saldo : 0,
    AdicionaItem : function(item) {
        App.Pedido.Produtos[item.input.replace("produto","")] = item;
        this.Soma = 0;
        for(var i=0;i<this.Produtos.length;i++) {
            this.Soma += Number(this.Produtos[i].preco);
        }
        $("#montante").val(App.Util.Moeda(this.Soma));
        this.Descontar($("#desconto").val());
    },
    AdicionaCliente : function() {
        
    },
    Descontar : function(num) {
        console.log(num);
        var number = num == ""? 0+"" : num+"";
        number = Number(number.replace(',','.'))/100;
        this.Desconto = number;
        this.Saldo = (this.Soma*(1-this.Desconto));
        $("#saldo").val(App.Util.Moeda(this.Saldo));
    },
    EnderecoTemplate : function(num,option) {
        return ''+
        '<div class="checkbox">'+
            '<label>'+
                '<input type="radio" value="'+num+'" name="endereco">'+
                option +
            '</label>'+
        '</div>';
    },
    RendenizarEnderecos : function(arr) {
        $('#enderecoEscolha').html(html).css('display','none');
        var html = "<h5 style='margin-bottom:0'><label>Endereço *</label></h5>";
        $('#enderecoEscolha').html(html);
        for(var i=0;i<arr.length;i++) {
            var option = ""+arr[i].logradouro+" "+arr[i].numero+"<br/>"+arr[i].cep+" - "+arr[i].bairro+"<br/>"+arr[i].cidade+"/"+arr[i].estado+"";
            html += this.EnderecoTemplate(i,option);
        }
        if(arr.length==0) {
            html += "Não há endereços cadastrados.</br>";
        }
        html += '<div class="row marginbottom">'+
        '<div class="col-md-12">'+
        '<a href="'+App.BasePath+'service/endereco/inserir" data-toggle="modal" data-target="#remoteModal" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Adicionar endereço</a>' +
        '</div>'+
        '</div>';
        $('#enderecoEscolha').html(html).css('display','block');
    },
    ProdutosTemplate : function (num) {
        return ''+
        '<div class="form-group cliente_group">'+
        '<input id="produto'+num+'" class="form-control inputTypeAhead" type="text"'+
        'placeholder="Digite o nome ou código do produto"  value="" name="produto'+num+'"/>'+
        '<input type="hidden" id="produtoId'+num+'" name="'+num+'Id" value=""/>'+
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
        if(this.Produtos.length==0) $('#produtos').html('');
        $('#produtos').append(this.ProdutosTemplate(this.Produtos.length));
    },
    CalculaFrete : function() {
        $.post(App.BasePath+'pedido/calculafrete',{produtos:this.Produtos},function(data) {
            console.log(data);
        });
    }
}