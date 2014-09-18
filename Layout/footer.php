          <? if(Session::isLogged()) {?>
            <div class="btn-group-vertical well btn-modules mobile">
              <? 
                $buttons = Session::$permissions;
                $buttons = array_merge($buttons,array(
                    'perfil'=>array('name'=>'Meu Perfil','level'=>'4'),
                    'login/logout'=>array('name'=>'Sair','level'=>'4'),
                ));
                foreach($buttons as $key=>$value) {
                if($value['level']>0) { ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-modules btn-lg" onclick="location.href='<?=Helper::link($key)?>'"><?=$value['name']?></button>
                </div>
              <? }
            } ?>
            </div>
          <?}?>
        </div>
        <footer class="bs-docs-footer" role="contentinfo">
            <h5>Neurosoft &copy; 2014</h5>
        </footer>
    </body>
</html>