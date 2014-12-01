App.Suporte = {
    Send : function(e) {
        var key = e.which || e.keyCode;
        if(key == 13) {
            App.Suporte.Search();
        }
    },
    New : function(pedido,produto,cliente) {
        BootstrapDialog.show({
            title: "Novo Suporte",
            message: $('<div></div>').load(App.BasePath+'service/suporte/editar/?pedido='+pedido+"&produto="+produto+"&cliente="+cliente),
            draggable: true
        });
    },
    View : function(suporte,produto,contato) {
        BootstrapDialog.show({
            title: "Editar Suporte",
            message: $('<div></div>').load(App.BasePath+'service/suporte/editar/'+suporte+'/?produto='+produto+'&contato='+contato),
            draggable: true
        });
    },
    NumSerie:function(obj) {
        var value = $(obj).val().toLowerCase();
        console.log(value);
    }
}