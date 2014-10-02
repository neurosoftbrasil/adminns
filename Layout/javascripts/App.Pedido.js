App.Pedido = {
    Produtos : [],
    ProdutosHTML : function (num) {
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
        'App.Pedido.Produtos.push(item);'+
        '$("#produtoId'+num+'").val(item.id);'+
        '});'+
        '</script>';
    },
    Form : function(num) {
        switch(num) {
            case 1:
                
            break;
        }
    },
    AdicionaProduto : function() {
        if(this.Produtos.length==0) $('#produtos').html('');
        $('#produtos').append(this.ProdutosHTML(this.Produtos.length));
        
    }
}