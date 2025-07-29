<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog  modal-lg" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="evento/action/eventoAdd.php" onsubmit="return validaForm(this);">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agendar Consulta</h4>
			  </div>
			  <div class="modal-body">
				
				  <div class="row">
						<div class="col-md-5">						
							<div class="form-group"> 
								<label>Paciente</label> 
								<select class="form-control sel2" id="cliente" name="cliente" style="width:95%;" required> 

									<?php 
									$query2 = $pdo->query("SELECT * FROM pacientes ORDER BY nome asc");
									$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
									$total_reg2 = @count($res2);
									if($total_reg2 > 0){
										for($i2=0; $i2 < $total_reg2; $i2++){
											foreach ($res2[$i2] as $key => $value){}
												echo '<option value="'.$res2[$i2]['id'].'">'.$res2[$i2]['nome'].' - '.$res2[$i2]['cpf'].'</option>';
										}
									}
									?>


								</select>    
							</div>						
						</div>


						<div class="col-md-4 ">
							<div class="form-group">
							<label>Profissional </label> 			
								<select class="form-control sel2" id="funcionario_modal" name="funcionario" style="width:95%;" onchange="mudarFuncionarioModal()"> 
									<?php if($id_func == ""){ ?>
									<option value="">Selecione um Profissional</option>
									<?php 
									$query2 = $pdo->query("SELECT * FROM usuarios where atendimento = 'Sim' ORDER BY id desc");
									$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
									$total_reg2 = @count($res2);
									if($total_reg2 > 0){
										for($i2=0; $i2 < $total_reg2; $i2++){
											foreach ($res2[$i2] as $key => $value){}
												echo '<option value="'.$res2[$i2]['id'].'">'.$res2[$i2]['nome'].'</option>';
										}
									}

								}else{
									echo '<option value="'.$id_usuario.'">'.$nome_usuario.'</option>';
									}

									?>
								


								</select>   
							</div> 	
						</div>

						<div class="col-md-3">						
							<div class="form-group"> 
								<label>Procedimento</label> 
								<select class="form-control sel2" id="servico" name="servico" style="width:100%;" required> 									

								</select>    
							</div>						
						</div>

					</div>
					<div class="row">						

						<div class="col-md-3" id="nasc">						
							<div class="form-group"> 
								<label>Data </label> 
								<input type="date" class="form-control" name="data" id="data-modal" onchange="mudarData()" style="width:95%;"> 
							</div>						
						</div>

					<div class="col-md-7">						
						<div class="form-group"> 
							<label>OBS <small>(Máx 100 Caracteres)</small></label> 
							<input maxlength="100" type="text" class="form-control" name="obs" id="obs" style="width:95%;">
						</div>						
					</div>

					<div class="col-md-2">						
						<div class="form-group"> 
							<label>Retorno</label>
								<select class="form-control" id="retorno" name="retorno" >
									<option value="Não">Não</option>
									<option value="Sim">Sim</option>
								</select>								
						</div>						
					</div>



					</div>


					<hr>
					<div class="row">

						<div class="col-md-12" id="nasc">						
							<div class="form-group"> 								
								<div id="listar-horarios">
									<small>Selecione um Profissional ou um Procedimento</small>
								</div>
							</div>						
						</div>					

					</div>
					<hr>



				



					<br>
					<input type="hidden" name="id" id="id">
					<input type="hidden" name="id_funcionario" id="id_funcionario" value="<?php echo $id_func ?>"> 
					<small><div id="mensagem" align="center" class="mt-3"></div></small>					
			</div>

			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				<button type="submit" class="btn btn-primary">Adicionar</button>
			  </div>
			</form>
			</div>
		</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		
		$('.sel2').select2({
			dropdownParent: $('#ModalAdd')
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		var atend = "<?=$atendimento_usuario?>";

		if(atend == 'Sim'){
			$('#funcionario_modal').val("<?=$id_usuario?>").change();
		}		

		mudarFuncionarioModal()
		
	});
</script>

<script type="text/javascript">
	
	function mudarFuncionarioModal(){	
		var func = $('#funcionario_modal').val();	
		
		listarHorarios();
		listarServicos(func);
	}
</script>

<script type="text/javascript">
	
	function mudarData(){
		var data = $('#data-modal').val();			
		$('#data_agenda').val(data).change();

		listarHorarios();

	}
</script>



<script type="text/javascript">
	function listarHorarios(){

		var funcionario = $('#funcionario_modal').val();		
		var data = $('#data_agenda').val();	
		
		$.ajax({
			url: 'paginas/' + pag + "/listar-horarios.php",
			method: 'POST',
			data: {funcionario, data},
			dataType: "text",

			success:function(result){
				$("#listar-horarios").html(result);
			}
		});
	}
</script>

<script>

	$("#form-servico").submit(function () {
		event.preventDefault();
		
		var formData = new FormData(this);

		var convenio = $('#convenio').val();
		var pgto = $('#pgto').val();

		if(pgto == "Convênio" && convenio == ""){
			alert("Selecione um Convênio ou uma forma de pagamento");
			return;
		}

		$.ajax({
			url: 'paginas/' + pag +  "/inserir-servico.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem-servico').text('');
				$('#mensagem-servico').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {                    
					$('#btn-fechar-servico').click();
					listar();
				} else {
					$('#mensagem-servico').addClass('text-danger')
					$('#mensagem-servico').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});

</script>



<script type="text/javascript">
	function listarServicos(func){	
		var serv = $("#servico").val();
		
		$.ajax({
			url: 'paginas/' + pag +  "/listar-servicos.php",
			method: 'POST',
			data: {func},
			dataType: "text",

			success:function(result){
				$("#servico").html(result);
			}
		});
	}


	function calcularValor(){
		var convenio = $("#convenio").val();
		var id_agd = $("#id_agd").val();

		$.ajax({
			url: 'paginas/' + pag +  "/calcular.php",
			method: 'POST',
			data: {id_agd, convenio},
			dataType: "text",

			success:function(result){				
				$("#valor_serv_agd").val(result);
			}
		});
	}
</script>