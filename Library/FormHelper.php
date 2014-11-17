<?

class FormHelper {

    private static $formName;
    private static $validations = array();

    const EMAIL = "/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/";
    const NOT_EMPTY = "/[^()]/";
    const ONLY_NUMBERS = "/\d+/";
    const IS_SELECTED = "/^[1-9]\d*$/";
    const DATE = "/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/";
    const TIME = "/[0-9]{2}\:[0-9]{2}/";
    const DATE_TIME = "/[0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}\:[0-9]{2}/";
    
    public static function create($id = "MyForm", $action = "", $options = array()) {
        self::$formName = $id;
        $html = '<section id="' . $id . '_section">' . "\n";
        $html .= '<form role="form" id="' . $id . '" name="' . $id . '" action="' . $action . '" ';
        if (!isset($options['method'])) {
            $html .= 'method="POST" '; // Metodo POST por padrão
        }
        $html .= Helper::printParams($options);
        $html .= '>' . "\n";
        echo $html;
    }

    public static function end($hijackEnter = 'true') {
        $html = '</form>' . "\n" . '</section>' . "\n";
        echo $html;
        if ($hijackEnter) {
            ?>
            <script type="text/javascript">
                $(document).keypress(function(e) {
                    var key = e.which || e.keyCode;
                    if (key == 13) {
                        $("#<?= self::$formName ?>").submit();
                    }
                });
            </script>
            <?
        }
        echo '<p id="' . self::$formName . '_message"></p>';
    }
    public static function button($id, $label = "Enviar",$options = array()) {
        
        $html  = "<button type='button' class='button button-md";
        if(isset($options['class'])) {
            $html .= " ".$options['class']." ";
            unset($options['class']);
        } else {
            $html .= " ";
        }
        $html .= "' ";
        $html .= Helper::printParams($options);
        $html .= ">$label</button>";
        echo $html;
    }
    public static function typeAhead($idName,$label,$ajaxPath,$options) {
    ?><div class="<?=$idName?>_group">
        <label for="<?=$idName?>"><?=$label?></label><br/>
        <input id="<?=$idName?>" class=" <? 
            if(isset($options['class'])) {
                echo $options['class'];
                unset($options['class']);
            }
        ?>" type="text"
        <?
            echo Helper::printParams($options);
        ?> value="<?=Request::value($idName)?>" name="<?=$idName?>"/>
        <input type='hidden' id="<?=$idName?>Id" name="<?=$idName?>Id" value="<?=Request::value($idName."Id")?>"/>
    </div>  
    <script type="text/javascript">
        $('#<?=$idName?>').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            source: function (query, process) {
                return $.get(App.BasePath+'service/<?=$ajaxPath?>?<?=$idName?>=' + query, function (data) {
                    res = JSON.parse(data);
                    return process(res.results);
                });
            }
        });
        $('#<?=$idName?>').on('typeahead:selected', function(evt, item) {
            $('#<?=$idName?>Id').val(item.id);
        });
    </script><?
    }
    public static function checkbox($idName, $label = '', $fields = array()) {
        ?><div class="checkbox <?= $idName ?>_group"><?
                if (gettype($fields)=='string') {
                    ?><label>
                    <input type="checkbox" id="<?= $idName ?>" name="<?= $idName ?>" <?=$fields=="1"?"checked":"";?>/>
            <?= $label ?>
                </label><?
                } else if(count($fields)>0) {
                    foreach ($fields as $key => $label) {
                        ?><label>
                            <input type="checkbox" id="<?= $key ?>" name="<?= $key ?>"/>
                        <?= $label ?>
                    </label><?
                }
        }
        ?></div><?
    }

    public static function input($idName, $label = '', $value = '', $options = array()) {
        if (!isset(self::$formName)) {
            die("Utilize o FormHelper::create()");
        }
        if(isset($options['labelClass'])) {
            $labelClass=$options['labelClass'];
            unset($options['labelClass']);
        } else {
            $labelClass="";
        }
        $html = '<div class=" ' . $idName . '_group" class="'.$labelClass.'">' . "\n";
        if ($label != false) {
            $html .= '<label for="' . $idName . '" class="'.$labelClass.'">' . $label . '</label>' . "\n";
        }
        $html .= '<input id="' . $idName . '" name="' . $idName . '" class="';

        // css classes
        if (isset($options['class'])) {
            $html .= $options['class'];
            unset($options['class']);
        }
        $html .= '" value="' . $value . '" ';
        // type "text" como padrão
        if (!isset($options['type'])) {
            $html .= 'type="text" ';
        }

        // validações para ajax

        if (isset($options['validation'])) {
            $tmp = self::$validations;
            $tmp = array_merge($tmp, array($idName => $options['validation']));
            unset($options['validation']);
            self::$validations = $tmp;
        }
        $html .= Helper::printParams($options);

        $html .= '/>' . "\n";
        $html .= '</div>' . "\n";
        echo $html;
    }

    public static function password($idName, $label = '', $value = '', $options = array()) {
        $pass = array('type' => 'password');
        $opt = array_merge($options, $pass);
        self::input($idName, $label, $value, $opt);
    }
    public static function textarea($idName,$label = '',$value='',$options=array()) {
        $html = "<div class='row'><div class='col-md-12'>";
        $html .= '<div class=" ' . $idName . '_group">' . "\n";
        if(isset($options['labelClass'])) {
            $labelClass=$options['labelClass'];
            unset($options['labelClass']);
        } else {
            $labelClass="";
        }
        if ($label != '') {
            $html .= '<label for="' . $idName . '" class="'.$labelClass.'">' . $label . '</label>' . "\n";
        }
        $html .= "<textarea id='$idName' name='$idName' class='' ";
        if(!isset($options['cols'])) {
            $options['cols'] = 50;
        }
        if(!isset($options['rows'])) {
            $options['rows'] = 2;
        }
        if(isset($options['class'])) {
            $html .= $options['class'];
            unset($options['class']);
        }
        $html .= "' ";
        $html .= Helper::printParams($options).">";
        $html .= $value;
        $html .= "</textarea></div></div>";
        echo $html;
    }
    public static function submit($id, $label = "Enviar", $options = array()) {
        if (!isset(self::$formName)) {
            die("Utilize o FormHelper::create()");
        }
        $method = "send"; // o padrao é respeitar o action do form
        $action;

        if (isset($options['method'])) {
            $method = $options['method'];
            unset($options['method']);

            if (isset($options['action'])) {
                $action = $options['action'];
                unset($options['action']);
            }

            if (strtolower($method) == "ajax" && !$action) { // validações ajax
                die('Formulário Ajax precisa de link. Crie um método em ' . Helper::controllerName(Request::get('controller')) . ' printando JSON.');
            }
        }

        $html = '<button class="';
        $ajax = "";
        if (isset($options['class'])) {
            $html .= $options['class'];
            unset($options['class']);
        } else {
            $html .= 'btn-default ';
        }
        $html .= '" ';
        if ($method == "ajax") {
            $html .= ' type="button" ';
            $html .= ' onclick="' . self::$formName . '_Sender(event)"';
        }
        
        $html .= Helper::printParams($options);
        $html .= ">$label</button>" . "\n";
        if ($method == "ajax") {
            ?>
            <style>
                #<?= self::$formName; ?>_message {
                    max-width:400px;
                }
            </style>
            <script type="text/javascript">
                $('#<?= self::$formName ?>').attr('onsubmit', '<?= self::$formName ?>_Sender(event)');
                function <?= self::$formName ?>_Sender(e) {
                    e.preventDefault();
            <?
            if (count(self::$validations) > 0) {
                ?>
                        var erros = [];
                        /*
             
                    <?
                     Util::prints(self::$validations);
                    ?>
             
                    */
                <?
                foreach (self::$validations as $key => $value) {
                    if ($value == "NotEmpty") {
                        ?>
                                if ($.trim($('#<?= $key ?>').val()) == "") {
                                    erros.push({
                                        "field": "<?= $key ?>",
                                        "error": "O campo <strong><?= $key ?></strong> deve ser preenchido."
                                    });
                                }
                        <?
                    }
                    if (isset($value['regex'])) {
                        ?>
                                if (!$.trim($('#<?= $key ?>').val()).match(<?= $value['regex'] ?>i)) {
                                    erros.push({
                                        "field": "<?= $key ?>",
                                        "error": "<?= $value['message'] ?>"
                                    });
                                }
                        <?
                    }
                }
            }
            ?>
                    $("input,textarea,select,div").removeClass('has-error');
                    $("#<?= self::$formName ?>_message").html('').removeClass('bg-danger');
            <? if (count(self::$validations) > 0) { ?> 
            if (erros.length == 0) {
            <? } ?>
                        $.post("<?= $action ?>", $("#<?= self::$formName ?>").serialize(), function(data) {
                            var json = JSON.parse(data);
                            $("#<?= self::$formName ?>_message").removeClass('bg-danger').removeClass('bg-success');
                            switch (json.status) {
                                case 'error':
                                    $("#<?= self::$formName ?>_message").addClass('bg-danger');
                                    var html = json.details.length > 1 ? "H&aacute; erros no formul&aacute;rio." : json.message;
                                    $("#<?= self::$formName ?>_message").html(html);
                                    break;
                                default:
                                    $("#<?= self::$formName ?>_message").html(json.message).addClass("bg-" + json.status);
                                    if (json.redirect) {
                                        location.href = json.redirect;
                                    }
                                break;
                            }
                        });
            <? if (count(self::$validations) > 0) { ?>
                } else {
                            $("#" + erros[0].field).focus();
                            for (i = 0; i < erros.length; i++) {
                                $("." + erros[i].field + "_group").addClass('has-error');
                            }
                            if (erros.length > 1) {
                                $("#<?= self::$formName; ?>_message").html("Existem erros no formulário.").addClass('bg-danger');
                            } else {
                                $("#<?= self::$formName; ?>_message").html(erros[0].error).addClass('bg-danger');
                            }
                        }
            <? } ?>
                }
            </script>
            <?
        }
        echo $html;
    }
    public static function selectFromTable($tableField,$description,$label=false,$value='',$options=array(),$conditions = false) {
        $cols = explode(".",$tableField);
        $table = $cols[0];
        $field = $cols[1];
        $tableField = $table."_".$field;
        
        global $db;
        $query = "select $field,$description from $table";
        if($conditions) {
            $query .= " where ".Helper::printParams($conditions);
        }
        $res = $db->query($query);
        $html = '<div class=" ' . $tableField . '_group">' . "\n";
        if(isset($options['labelClass'])) {
            $labelClass=$options['labelClass'];
            unset($options['labelClass']);
        } else {
            $labelClass="";
        }
        if ($label != false) {
            $html .= '<label for="' . $table . '" class="'.$labelClass.'">' . $label . '</label>' . "\n";
        }
        if (isset($options['validation'])) {
            $tmp = self::$validations;
            $tmp = array_merge($tmp, array($tableField => $options['validation']));
            unset($options['validation']);
            self::$validations = $tmp;
        }
        
        
        $html .= "<select id='$tableField' name='$tableField' class=''";
        $html .= Helper::printParams($options);
        $html .= "><option value='0'>Selecione</option>";
        foreach($res as $r) {
            $html .= "<option value='".$r[$field]."' ".($value==$r[$field]?"selected":"").">".$r[$description]."</option>";
        }
        $html .= "</select>";
        $html .= "</div>";
        echo $html;
    }
    public static function submitAjax($label, $action, $options = array()) {
        self::submit(self::$formName . "_submit", $label, array_merge($options, array(
            'method' => 'ajax',
            'action' => Helper::link('service/' . Request::get('controller') . "/" . $action)
                        )
                )
        );
    }
    public static function select($idName, $label, $value, $options) {
        ?>
            <select id="" name="">
                <? for($i=0;$i<count($options);$i++){?>
                    <option value="<?=$i?>" <?=$value==$i?" selected":""?>><?=$options[$i]?></option>
                <? } ?>
            </select>
        <?
    }
    public static function startGroup($options = array()) {
        $html = '<div class=" ';
        // css classes
        if (isset($options['class'])) {
            $html .= $options['class'];
            unset($options['class']);
        }
        $html .= '" ';
        echo Helper::printParams($options);
        $html .= ">" . "\n";

        echo $html;
    }

    public static function endGroup() {
        echo "</div>" . "\n";
    }

}