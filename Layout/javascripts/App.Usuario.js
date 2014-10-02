App.Usuario = {
    resetarSenha : function(nome, id) {
        if (App.Modal.Show("Resetar senha", 'Deseja realmente resetar a senha de <strong>' + nome + '</strong> para "neurosoft"?', "Resetar senha", function() {
            location.href = App.BasePath+'usuario/resetarsenha/' + id;
        }));
    }
}