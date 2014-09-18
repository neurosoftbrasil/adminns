<?

class FormHelper {
	private static $formName;
	private static $validations = array();

	const EMAIL = "^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$";
	const NOT_EMPTY = "[^()]";
	const ONLY_NUMBERS = "";
	const IS_SELECTED = "^[1-9]\d*$";
	const DATE = "[0-9]{2}\/[0-9]{2}\/[0-9]{4}";
	const TIME = "[0-9]{2}\:[0-9]{2}";
	const DATE_TIME = "[0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}\:[0-9]{2}";

	public static function create($id="MyForm",$action="",$options=array()) {
		self::$formName = $id;
		$html  = '<section id="'.$id.'_section">'."\n";
    	$html .= '<form role="form" id="'.$id.'" name="'.$id.'" action="'.$action.'" ';
    	if(!isset($options['method'])) {
    		$html .= 'method="POST" '; // Metodo POST por padrão
    	}
    	$html .= Helper::printParams($options);
    	$html .= '>'."\n";
    	echo $html;
	}
	public static function end() {
		$html = '</form>'."\n".'</section>'."\n";
		echo $html;
		?>
			<script type="text/javascript">
				$(document).keypress(function(e) {
					var key = e.which || e.keyCode;
					if(key == 13) {
						$("#<?=self::$formName?>").submit();
					}
				});
			</script>
		<?
	}
	public static function input($idName, $label='',$value='',$options=array()) {
		if(!isset(self::$formName)) {
			die("Utilize o FormHelper::create()");
		}
		$html  = '<div class="form-group '.$idName.'_group">'."\n";
		if($label != false) {
			$html .= '<label for="'.$idName.'">'.$label.'</label>'."\n";	
		}
		$html .= '<input id="'.$idName.'" name="'.$idName.'" class="form-control ';
		
		// css classes
		if(isset($options['class'])) {
			$html .= $options['class'];
			unset($options['class']);
		}
		$html .= '" ';
		// type "text" como padrão
		if(!isset($options['type'])) {
			$html .= 'type="text" ';
		}

		// validações para ajax

		if(isset($options['validation'])) {
			$tmp = self::$validations;
			$tmp = array_merge($tmp,array($idName=>$options['validation']));
			unset($options['validation']);
			self::$validations = $tmp;
		}
		$html .= Helper::printParams($options);

		$html .= '/>'."\n";
        $html .= '</div>'."\n";
        echo $html;
	}
	public static function password($idName,$label='',$value='',$options=array()) {
		$pass = array('type'=>'password');
		$opt = array_merge($options,$pass);
		self::input($idName,$label,$value,$opt);
	}
	public static function submit($id,$label="Enviar",$options=array()) {
		if(!isset(self::$formName)) {
			die("Utilize o FormHelper::create()");
		}
		$method = "send"; // o padrao é respeitar o action do form
		$action;

		if(isset($options['method'])) {
			$method = $options['method'];
			unset($options['method']);

			if(isset($options['action'])) {
				$action = $options['action'];
				unset($options['action']);
			}

			if(strtolower($method) == "ajax" && !$action) { // validações ajax
				die('Formulário Ajax precisa de link. Crie um método em '.Helper::controllerName(Request::get('controller')).' printando JSON.');
			}
		}

		$html  = '<button class="btn ';
		$ajax = "";
		if(isset($options['class'])) {
			$html .= $options['class'];
			unset($options['class']);
		} else {
			$html .= 'btn-default ';
		}
		$html .= '" ';	
		if($method == "ajax") {
			$html .= ' type="button" ';
			$html .= ' onclick="'.self::$formName.'_Sender(event)"';
		}

		$html .= Helper::printParams($options);
		$html .= ">$label</button>"."\n";
		if($method == "ajax") {
			?>
				<style>
					#<?=self::$formName;?>_message {
						max-width:400px;
					}
				</style>
				<script type="text/javascript">
					$('#<?=self::$formName?>').attr('onsubmit','<?=self::$formName?>_Sender(event)');
					function <?=self::$formName?>_Sender(e) {
						e.preventDefault();
						<?
							if(count(self::$validations)>0) {
								?>
									var erros = [];
								<? foreach(self::$validations as $key=>$value) {
									if($value == "NotEmpty") {
								?>
										if($.trim($('#<?=$key?>').val())=="") {
											erros.push({
												"field":"<?=$key?>",
												"error":"O campo <strong><?=$key?></strong> deve ser preenchido."
											});
										}
								<?		
									}
									if(isset($value['regex'])) {
								?>
									if(!$.trim($('#<?=$key?>').val()).match(/<?=$value['regex']?>/i)) {
										erros.push({
											"field":"<?=$key?>",
											"error":"<?=$value['message']?>"
										});
									}
								<?
									}
								}
							}

							?>
							$(".form-group").removeClass('has-error');
							$("#<?=self::$formName?>_message").html('').removeClass('bg-danger');

							<? if(count(self::$validations)>0) {?> if(erros.length==0) { <?}?>
								$.post("<?=$action?>",$("#<?=self::$formName?>").serialize(),function(data) {});
							<? if(count(self::$validations)>0) {?> } else {
								$("#"+erros[0].field).focus();
								for(i=0;i<erros.length;i++) {
									$("."+erros[i].field+"_group").addClass('has-error');
								}
								if(erros.length>1) {
									$("#<?=self::$formName;?>_message").html("Há erros no formulário.").addClass('bg-danger');	
								} else {
									$("#<?=self::$formName;?>_message").html(erros[0].error).addClass('bg-danger');
								}
							} 
						<? } ?>
					}
				</script>
			<?
		}
		echo $html;
		echo '<p id="'.self::$formName.'_message"></p>';
	}
	public static function submitAjax($label,$action,$options=array()) {
		self::submit(self::$formName."_submit",$label,
			array_merge($options,
				array(
					'method'=>'ajax',
					'action'=> Helper::link('service/'.Request::get('controller')."/".$action)
				)
			)
		);
	}
	public static function startGroup($options=array()) {
		$html  = '<div class="form-group ';
		// css classes
		if(isset($options['class'])) {
			$html .= $options['class'];
			unset($options['class']);
		}
		$html .= '" ';
		echo Helper::printParams($options);
		$html .= ">"."\n";

		echo $html;
	}
	public static function endGroup() {
		echo "</div>"."\n";
	}
}












