﻿
<?php
  //Controla o Debug no projeto
  ini_set('display_errors', 'On');
  
  include "sessao.php";
  include "config.php";
  include "funcs.php";
?>

<html>
	<header>
		<title>Envio de SMS Gratuíto</title>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		
	</header>
	<body>
		<?php
		if ($_SESSION["login"] == true)
		{
		
		?>
	    
		<div ng-app="Envia_SMS" ng-controller="cntrl" >
			<div class="container-fluid bg-1 text-rigth">
				<form>
					<?  Pesquisar Itens ?>
					<div class="jumbotron">
							<h1>Sistema de Envio de SMS </h1>							
							<p>Para maiores informações marcelomaurinmartins@gmail.com</p>
					</div>
					<div class="row">
						<div class="col-sm-1">Telefone:</div>
						<div class="col-sm-4"><input class="form-control" placeholder="Telefone (opcional)" type="text" ng-model="ptelefone" name="ptelefone"></div>					
						<div class="col-sm-1"> <input type="button" class="btn btn-primary"  value="Pesquisar" ng-click="displayJob(ptelefone)" > </div> 
						<div class="col-sm-1">  </div> 
						<div class="col-sm-1"> <input type="button" class="btn btn-primary"  value="Novo Item" ng-click="newJob(ptelefone)" > </div> 
					
					</div>
					
					
					<? Retorno de mensagem de erro ?>
					<div class="info">
						<div class="control-label">Alerta:</div>
						<div class="info">{{msg}}</div>
					</div>
				</form>
			</div>
			<? layout da tabela de resposta ?>
			<div class="container-fluid bg-1 text-rigth">
				
				<? **Cadastrar itens** ?>
				<div id="cadastro" ng-style="disableInsert" >
					<div class="row">
						<div class="col-sm-12"> <h3>Operação Insert registro </h3></div>	
					</div>
					<div class="row">
						<div class="col-sm-1 control-label"> Telefone: </div>
						<div class="col-sm-4"> <input class="form-control" placeholder="Telefone que será enviado" type="text" ng-model="telefone" name="telefone"> </div>
					</div>
					<div class="row">
						<div class="col-sm-1 control-label"> Mensagem: </div>
						<div class="col-sm-4"> <input  class="form-control" placeholder="Mensagem a enviar" type="text" ng-model="mensagem" name="mensagem"> </div>	
					</div>
					<div class="row">
						<div class="col-sm-4"></div>
						<div class="col-sm-1 control-label">  </div> 
						<div> <input type="button" class="btn btn-primary" value="submit" ng-click="insertJob()" > </div> 					
					</div>
				</div>
			</div>
			
			<div class="container-fluid bg-1 text-rigth">
				
				<? *** Update *** ?>
				<div id="edicao" ng-style="disableUpdate" class="container-fluid bg-1 text-rigth">
					<div class="row">
						<div class="col-sm-12"> <h3>Operacao de Edicao</h3></div>
					</div>
					<div class="row">
						<div class="col-sm-1 control-label"> Id:</div><div> {{edidjob}}</div>
					</div>
					<div class="row">
						<div class="col-sm-1 control-label"> Telefone</div>
						<div class="col-sm-4"> <input class="form-control"  type="text" ng-model="edtelefone" name="edtelefone"></div>
					</div>
					<div class="row">
						<div class="col-sm-1 control-label"> Mensagem: </div>
						<div class="col-sm-4"> <input class="form-control"  type="text" ng-model="edmensagem" name="edmensagem"></div>						
					</div>
					<div class="row">
						<div class="col-sm-4"></div>
						<div class="col-sm-1"> <button class="btn btn-primary" ng-click="updateJob(edidjob,edtelefone, edmensagem)">Atualizar</button></div>
					</div>
				</div>
			</div>
			
			<div class="container-fluid bg-1 text-rigth">
				<div class="row">
				<hr>
				</div>
			</div>
				
			<? ** Tela de Resultado **?>
			<div class="container-fluid bg-1 text-rigth">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Id</th>
							<th>Telefone</th>
							<th>Mensagem</th>
							<th>Status</th>						
						<tr>
					</thead>
					<tbody>
						<tr ng-repeat="sms in data">
							<td>{{sms.idjob}}</td>
							<td>{{sms.telefone}}</td>
							<td>{{sms.mensagem}}</td>
							<td>{{sms.status}}</td>
							<td><button class="btn btn-primary" ng-click="deleteJob(sms.idjob);">Delete</button></td>
							<td><button class="btn btn-primary" ng-click="HabilitaEdicao(sms);">Edit</button></td>
						</tr>
					</tbody>
				</table>
				
				
			</div>
			
			
			
			<? *** Controler *** ?>
			<script>
				var app = angular.module('Envia_SMS',[]);
				app.controller('cntrl', function($scope,$http)
				{
				    $scope.disableUpdate = {'display': 'none'}; //Atribui Edicao invisivel
					$scope.disableInsert = {'display': 'none'}; //Atribui Edicao invisivel
					
					//Mostra  os Jobs				
					$scope.insertJob=function()
					{
						$http.post("/casa/ws/iSMS.php",{'telefone':$scope.telefone, 'mensagem':$scope.mensagem, 'status': $scope.status})
						.success(function()
						{
							$scope.msg = "SMS foi programado com sucesso";
							$scope.displayJob();
						})
					}

					$scope.displayJob=function(pTelefone)
					{
					    $scope.disableUpdate = {'display': 'none'}; //Atribui Edicao invisivel
						$scope.disableInsert = {'display': 'none'}; //Atribui Edicao invisivel
						if (typeof pTelefone == "undefined") 
						{
							pTelefone = "";
						}	

						var params = {"telefone": pTelefone };
						var config = {params: params};
						
						
						$http.get("/casa/ws/sSMS.php",config)
						.success(function(data)
						{
							$scope.data = data;
							$scope.msg = "Tela Atualizada!";
						})
						.error(function()
						{
							$scope.msg = "Pesquisa retornou vazia";
							$scope.data = null;
						}) 
					}
					
					$scope.deleteJob=function(idjob)
					{
						$http.post("/casa/ws/dSMS.php",{'id':idjob})
						.success(function()
						{					
							$scope.displayJob();
							$scope.msg = "Registro excluido!";
						})
					}
					
					//Mostra  os Jobs				
					$scope.newJob=function()
					{
						$scope.disableInsert = {'display': 'block'}; 
						$scope.disableUpdate = {'display': 'none'}; 
						$scope.edidjob = "";
						$scope.edtelefone = "";
						$scope.edmensagem = "";
					}					
					
					$scope.HabilitaEdicao=function(sms)
					{
						$scope.disableUpdate = {'display': 'block'}; 
						$scope.edidjob = sms.idjob;
						$scope.edtelefone = sms.telefone;
						$scope.edmensagem = sms.mensagem;						
					}
					
					
					$scope.updateJob=function(edidjob, edtelefone, edmensagem)
					{
						$http.post("/casa/ws/uSMS.php",{'idjob':edidjob,'telefone':edtelefone, 'mensagem':edmensagem})
						.success(function()
						{					
							$scope.displayJob();
							$scope.msg = "Registro excluido!";
							
							$scope.disableUpdate = {'display': 'none'}; 
							$scope.DisplayJob();
						})
					}

				});
			</script>
			<?php
				
					}
					else
					{
					
			?>
			<a href="http://maurinsoft.com.br/casa/">Relog na pagina</a>
			<?php
				
					}
				
			?>

		</div>
	</body>
</html>