<?php
	//CONECTANDO COM A CLASSE PRODUTO
	require_once 'classe.php';
	$p = new Produto("patrono_motos", "localhost", "root", "");

	// Enviando informações
	if(isset($_POST['produto'])){
		if(isset($_GET['up_id_produto']) && !empty($_GET['up_id_produto'])) {
			$id_produto = $_GET['up_id_produto'];
			$produto = $_POST['produto'];
			$marca = $_POST['marca'];
			$modelo = $_POST['modelo'];
			$cor = $_POST['cor'];
			$ano = $_POST['ano'];
			$preco = $_POST['preco'];
			$quantidade = $_POST['quantidade'];
			$fabricacao = $_POST['fabricacao'];
			$id_fornecedor = $_POST['id_fornecedor'];
			if(!empty($produto) && !empty($marca) && !empty($modelo) && !empty($cor) && !empty($ano) && !empty($preco) && !empty($quantidade) && !empty($fabricacao) && !empty($id_fornecedor)) {
				$p->atualizarProduto($id_produto, $produto, $marca, $modelo, $cor, $ano, $preco, $quantidade, $fabricacao, $id_fornecedor);
				header("location: produtos.php");
			}
		} else {
			$produto = $_POST['produto'];
			$marca = $_POST['marca'];
			$modelo = $_POST['modelo'];
			$cor = $_POST['cor'];
			$ano = $_POST['ano'];
			$preco = $_POST['preco'];
			$quantidade = $_POST['quantidade'];
			$fabricacao = $_POST['fabricacao'];
			$id_fornecedor = $_POST['id_fornecedor'];

			if(!empty($produto) && !empty($marca) && !empty($modelo) && !empty($cor) && !empty($ano) && !empty($preco) && !empty($quantidade) && !empty($fabricacao) && !empty($id_fornecedor)) {

				if(!$p->incluirProduto($produto, $marca, $modelo, $cor, $ano, $preco, $quantidade, $fabricacao, $id_fornecedor)) {
					echo "O produto já está cadastrado, apenas altere a quantidade.";
				}
				
			} else {
				echo "Preencha todos os campos!";
			}
		}
	}

	// Consulta para substituir os input
	if(isset($_GET['up_id_produto'])) {
		$up_id_produto = $_GET['up_id_produto'];
		$res = $p->consultarDadosproduto($up_id_produto);
	}

	// Para excluir
	if(isset($_GET['exc_id'])) {
		$exc_id = $_GET['exc_id'];
		$p->excluirProduto($exc_id);
		header("location: produtos.php");
	}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Cadastro de produtos</title>
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
</head>
<body>
	<header>
		<div>
			<h1>Patrono Motos</h1>
		</div>

		<div class="atalho_btns">
			<div class="atalho">
				<p>Produtos</p>
			</div>
			<a href="http://localhost/projetos/sistemaInterno/fornecedores.php" class="atalho">
				<p>Fornecedores</p>
			</a>
		</div>
	</header>

	<section class="pesquisa">
		<form method="POST">
			<h2>Campo de pesquisa:</h2>
			<p>Escolha um dos campos para realizar a pesquisa</p>

			<label for="pesquisa_produto">Produto: </label>
			<input type="text" name="pesquisa_produto" id="pesquisa_produto">
			<label for="pesquisa_id_fornecedor">ID fornecedor: </label>
			<input type="number" name="pesquisa_id_fornecedor" id="pesquisa_id_fornecedor">

			<br>

			<button type="submit">Pesquisar</button>
		</form>

		<table>
			<tr id="titulo">
				<td>PRODUTO</td>
				<td>MARCA</td>
				<td>MODELO</td>
				<td>COR</td>
				<td>ANO</td>
				<td>PREÇO</td>
				<td>QUANTIDADE</td>
				<td>FABRICAÇÃO</td>
				<td colspan="2">FORNECEDOR</td>
			</tr>
			<?php
				if(isset($_POST['pesquisa_produto']) || isset($_POST['pesquisa_id_fornecedor'])) {
					$produto = $_POST['pesquisa_produto'];
					$fornecedor_id = $_POST['pesquisa_id_fornecedor'];

					if(!empty($produto)) {
						$produtos = $p->pesquisarProduto($produto);
					} else {
						$produtos = $p->pesquisarProdutoId($fornecedor_id);
					}
					if(count($produtos) > 0) {
						for ($i=0; $i < count($produtos); $i++) { 
							echo "<tr>";
							foreach($produtos[$i] as $k => $v) {
								if($k != 'id_produto') {
									echo "<td>". $v ."</td>";
								}
							}
							?>
								<td class="botoes">
									<a href="produtos.php?up_id_produto=<?php echo $produtos[$i]['id_produto'] ?>">Editar</a>
							<a href="produtos.php?exc_id=<?php echo $produtos[$i]['id_produto'] ?>">Excluir</a>
								</td>
							</tr>
							<?php
						}
					} else {
						echo '<tr><td colspan="9">Não foi encontrado nenhuma informação com os valores passados. Verifique e tente novamente.</td></tr>';
					}
				}
			?>
		</table>
	</section>


	<section id="cadastro">
		<form method="POST">
			<h2>Produto:</h2>

			<label for="produto">Produto:</label>
			<input type="text" name="produto" id="produto"
			value="<?php if(isset($res)){echo $res['produto'];} ?>">

			<label for="marca">Marca:</label>
			<input type="text" name="marca" id="marca"
			value="<?php if(isset($res)){echo $res['marca'];} ?>">

			<label for="modelo">Modelo moto:</label>
			<input type="text" name="modelo" id="modelo"
			value="<?php if(isset($res)){echo $res['modelo_moto'];} ?>">

			<label for="cor">Cor:</label>
			<input type="text" name="cor" id="cor"
			value="<?php if(isset($res)){echo $res['cor'];} ?>">

			<label for="ano">Ano:</label>
			<input type="number" name="ano" id="ano"
			value="<?php if(isset($res)){echo $res['ano'];} ?>">

			<label for="preco">Preço:</label>
			<input type="number" name="preco" id="preco"
			value="<?php if(isset($res)){echo $res['preco'];} ?>">

			<label for="quantidade">Quantidade:</label>
			<input type="number" name="quantidade" id="quantidade"
			value="<?php if(isset($res)){echo $res['quantidade'];} ?>">

			<label for="fabricacao">Data fabricação:</label>
			<input type="date" name="fabricacao" id="fabricacao"
			value="<?php if(isset($res)){echo $res['data_fabricacao'];} ?>">

			<label for="id_fornecedor">ID fornecedor:</label>
			<input type="number" name="id_fornecedor" id="id_fornecedor"
			value="<?php if(isset($res)){echo $res['id_fornecedor'];} ?>">

			<br>

			<button type="submit"><?php if(isset($res)){echo 'Atualizar';} else{echo 'enviar';} ?></button>
			<button type="reset">Limpar</button>
		</form>

	</section>

	<section class="apresentacao">
		<table>
			<tr id="titulo">
				<td>PRODUTO</td>
				<td>MARCA</td>
				<td>MODELO</td>
				<td>COR</td>
				<td>ANO</td>
				<td>PREÇO</td>
				<td>QUANTIDADE</td>
				<td>FABRICAÇÃO</td>
				<td colspan="2">FORNECEDOR</td>
			</tr>
			<?php
				$produtos = $p->buscarDadosProdutos();
				if(count($produtos) > 0) {
					for($i = 0; $i < count($produtos); $i++) {
						echo "<tr>";
						foreach($produtos[$i] as $k => $v) {
							if($k != "id_produto"){
								echo "<td>".$v."</td>";
							}
						}
			?>
						<td class="botoes">
							<a href="produtos.php?up_id_produto=<?php echo $produtos[$i]['id_produto'] ?>">Editar</a>
							<a href="produtos.php?exc_id=<?php echo $produtos[$i]['id_produto'] ?>">Excluir</a>
						</td>
			<?php
						echo "</tr>";
					}
				} else {
					echo '<tr><td colspan="2">Ainda não há fornecedores cadastrados.</td></tr>';
				}
			?>
		</table>
	</section>
</body>

</html>