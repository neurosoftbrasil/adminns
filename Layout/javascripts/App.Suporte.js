App.Suporte = {
    SearchBinder:undefined,
    Search : function() {
        var url = [],
        nome = $("#nome").val(),
        documento = $('#documento').val(),
        notafiscal = $('#notafiscal').val();
        
        if($.trim(nome) != "") {
            url.push('nome='+nome);
        }
        if($.trim(documento) != "") {
            url.push('documento='+documento);
        }
        if($.trim(notafiscal) != "") {
            url.push('notafiscal='+notafiscal);
        }
        if(!App.Suporte.SearchBinder || url.length > 0 ) {
            $("#results").html('<tr><td colspan="6" style="text-align:center"><span class="loading">Carregando</span></td></tr>');
            $.post('/adminns/service/suporte/search',url.join("&"),function(d) {
                App.Suporte.SearchBinder = d;
                $('#results').html(d);
            });
        }
    },
    Send : function(e) {
        var key = e.which || e.keyCode;
        if(key == 13) {
            App.Suporte.Search();
        }
    }
}