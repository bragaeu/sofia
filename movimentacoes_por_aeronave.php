﻿<?php 
session_start();

include ("Funcoes/funcao_selecionar.php");

@$datainicial = $_REQUEST['datainicial'];
@$datafinal = $_REQUEST['datafinal'];
@$aeronave = $_REQUEST['aeronave'];

$offset = 100;
@$x = $_GET['x'];

if (!$x){
	$x = 0;
}else{
	$x = $_GET['x'];
}

$consulta_total = selecionar("tabela_historicoretirada","data, numeroferramenta, cracha", "WHERE (data BETWEEN '$datainicial' AND ADDDATE('$datafinal',1)) AND aeronave = '$aeronave'","ORDER BY DATE(data) DESC, numeroferramenta ASC, cracha ASC");
$consulta = selecionar("tabela_historicoretirada","data, numeroferramenta, cracha", "WHERE (data BETWEEN '$datainicial' AND ADDDATE('$datafinal',1)) AND aeronave = '$aeronave'","ORDER BY DATE(data) DESC, numeroferramenta ASC, cracha ASC", "LIMIT $offset OFFSET $x");

?>

<!doctype html>
<html lang="pt-BR">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="css/sofia.css" rel="stylesheet" type="text/css" media="screen">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.css">
        <script src="Js/movimentacoes_por_aeronave.js"></script>
		<title>Movimentações por Aeronave</title>
	</head>
    
	<body><br>

    <div class="container">
    <div class="row">
    
    <?php if (@$_SESSION['logado']){ ?>
	<?php include ("nav.php"); ?>    
    
    	<header class="span8 offset4">
    		<h1>Movimentações por Aeronave</h1><br>
    	</header>
    
    	<article class="span8 offset4">
    	
        	<section>
        		<br><h4><b><?php echo $aeronave;?></b></h4><br>         
        	</section>
    
    		<?php if ($consulta == true){ ?>
    		<table width="584" border="1" class="table table-bordered table-hover">
    			<tbody>
    		
            		<tr class="cabecalho">
    					<td><b>Crachá</b></td>
                		<td><b>Nome de Guerra</b></td>
                		<td><b>N° Ferramenta</b></td>
                		<td><b>Ferramenta</b></td>
                		<td><b>Data</b></td>
    				</tr>
            
            	<?php
					for ($i=0;$i<count($consulta);$i++){
					$consultacracha = $consulta[$i]['cracha'];
					$consultanumeroferramenta = $consulta[$i]['numeroferramenta']; 					
					$consultanomeguerra = selecionar("tabela_mecanicos","nomeguerra","WHERE cracha = '$consultacracha'");
					$consultaferramenta = selecionar("tabela_ferramentas", "ferramenta", "WHERE numeroferramenta = '$consultanumeroferramenta'");
				?>
                
            		<tr>
            			<td><?php echo $consulta[$i]['cracha']; ?></td>
                		<td><?php echo $consultanomeguerra[0]['nomeguerra']; ?></td>
 		     	        <td><?php echo $consulta[$i]['numeroferramenta']; ?></td>
        		        <td><?php echo $consultaferramenta[0]['ferramenta'] ?></td>
                		<td><?php echo date('d/m/Y', strtotime($consulta[$i]['data'])); ?></td>
		            </tr>
            	<?php }?>
                
        		</tbody>
        	</table>
        
        	<section class="pagination">
            	<ul>
           		<?php 
				for ($y=0;$offset*$y<count($consulta_total);$y++){
				if ($y*$offset == $x){
				?>
                <li class="active"><a href="movimentacoes_por_aeronave.php?x=<?php echo ($y)*$offset ?>"> <?php echo $y+1 ?></a></li>
                <?php }else{ ?>
			    <li><a href="movimentacoes_por_aeronave.php?x=<?php echo ($y)*$offset ?>&aeronave=<?php echo $aeronave ?>&datainicial=<?php echo $datainicial?>&datafinal=<?php echo $datafinal?>"> <?php echo $y+1 ?></a></li>		
               	<?php }} ?>
                </ul>
            </section>
            
            	<?php 
				}else{ ?>
			<section> 
				<p>Nenhuma ferramenta foi utilizada nesta aeronave neste período!</p>
			</section>
            	<?php }?>
                 
    	</article>
        
        <?php }else{ ?>    
    <article>
    	<section>
        	<p><b>Acesso restrito! Faça login no sistema!</b></p><br>
            <a class="btn btn-info pagination-centered" href="login.php">Fazer login</a>
        </section>    
    </article>
    <?php } ?>
        
    </div>
    </div>
	</body>
    
</html>