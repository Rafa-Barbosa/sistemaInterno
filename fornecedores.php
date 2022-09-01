<?php
	//CONECTANDO COM A CLASSE PRODUTO
	require_once 'classe.php';
	$p = new Produto("patrono_motos", "localhost", "root", "");

	// Enviando informações
	if(isset($_POST['nome'])) {
		if(isset($_GET['up_id_fornecedores']) && !empty($_GET['up_id_fornecedores'])) {
			$id_fornecedor = $_GET['up_id_fornecedores'];
			$nome = $_POST['nome'];
			$razao_social = $_POST['razao_social'];
			$fone = $_POST['fone'];
			$email = $_POST['email'];
			$rua = $_POST['rua'];
			$numero_rua = $_POST['numero_rua'];
			$estado = $_POST['estado'];
			$cep = $_POST['cep'];
			if(!empty($nome) && !empty($razao_social) && !empty($fone) && !empty($email) && !empty($rua) && !empty($numero_rua) && !empty($estado) && !empty($cep)) {
				$p->atualizarFornecedor($id_fornecedor, $nome, $razao_social, $fone, $email, $rua, $numero_rua, $estado, $cep);
				header("location: fornecedores.php");
			}
		}else {
			$nome = $_POST['nome'];
			$razao_social = $_POST['razao_social'];
			$fone = $_POST['fone'];
			$email = $_POST['email'];
			$rua = $_POST['rua'];
			$numero_rua = $_POST['numero_rua'];
			$estado = $_POST['estado'];
			$cep = $_POST['cep'];
			if(!empty($nome) && !empty($razao_social) && !empty($fone) && !empty($email) && !empty($rua) && !empty($numero_rua) && !empty($estado) && !empty($cep)) {
				if(!$p->incluirFornecedor($nome, $razao_social, $fone, $email, $rua, $numero_rua, $estado, $cep)) {
					echo "O fornecedor já está cadastrado!";
				}
			} else {
				echo "Preencha todos os campos!";
			}
		}
	}

	// Consulta para substituir os input
	if(isset($_GET['up_id_fornecedores'])) {
		$up_id_fornecedores = $_GET['up_id_fornecedores'];
		$res = $p->consultarDadosFornecedor($up_id_fornecedores);
	}

	// Exclusões
	if(isset($_GET['exc_id'])) {
		$exc_id = $_GET['exc_id'];
		$p->excluirFornecedor($exc_id);
		header("location: fornecedores.php");
	}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Cadastro de fornecedores</title>
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
</head>
<body>
	<header>
		<div>
			<h1>Patrono Motos</h1>
		</div>

		<div class="atalho_btns">
			<a href="http://localhost/projetos/sistemaInterno/produtos.php" class="atalho">
				<p>Produtos</p>
			</a>
			<div class="atalho">
				<p>Fornecedores</p>
			</div>
		</div>
	</header>

	<section class="pesquisa">
		<form method="POST">
			<h2>Campo de pesquisa:</h2>
			<p>Escolha um dos campos para realizar a pesquisa</p>

			<label for="pesquisa_nome_fornecedor">Nome fornecedor: </label>
			<input type="text" name="pesquisa_nome_fornecedor" id="pesquisa_nome_fornecedor">
			<label for="pesquisa_id_fornecedor">ID fornecedor: </label>
			<input type="number" name="pesquisa_id_fornecedor" id="pesquisa_id_fornecedor">

			<br>

			<button type="submit">Pesquisar</button>
		</form>

		<table>
			<tr id="titulo">
				<td>ID</td>
				<td>NOME FANTASIA</td>
				<td>RAZÃO SOCIAL</td>
				<td>TELEFONE</td>
				<td>E-MAIL</td>
				<td>RUA</td>
				<td>NÚMERO</td>
				<td>ESTADO</td>
				<td colspan="2">CEP</td>
			</tr>
			<?php
				if(isset($_POST['pesquisa_nome_fornecedor']) || isset($_POST['pesquisa_id_fornecedor'])) {
					$fornecedor_nome = $_POST['pesquisa_nome_fornecedor'];
					$fornecedor_id = $_POST['pesquisa_id_fornecedor'];

					if(!empty($fornecedor_nome)) {
						$fornecedores = $p->pesquisarFornecedorNome($fornecedor_nome);
					} else {
						$fornecedores = $p->pesquisarFornecedorId($fornecedor_id);
					}
					if(count($fornecedores) > 0) {
						for ($i=0; $i < count($fornecedores); $i++) { 
							echo "<tr>";
							foreach($fornecedores[$i] as $k => $v) {
								echo "<td>". $v ."</td>";
							}
							?>
								<td class="botoes">
									<a href="fornecedores.php?up_id_fornecedores=<?php echo $fornecedores[$i]['id_fornecedor'] ?>">Editar</a>
									<a href="fornecedores.php?exc_id=<?php echo $fornecedores[$i]['id_fornecedor'] ?>">Excluir</a>
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
			<h2>Fornecedor:</h2>

			<label for="nome">Nome fantasia:</label>
			<input type="text" name="nome" id="nome"
			value="<?php if(isset($res)){echo $res['nome_fantasia'];} ?>">

			<label for="razao_social">Razão social:</label>
			<input type="text" name="razao_social" id="razao_social"
			value="<?php if(isset($res)){echo $res['razao_social'];} ?>">

			<label for="fone">Telefone:</label>
			<input type="text" name="fone" id="fone"
			value="<?php if(isset($res)){echo $res['fone'];} ?>">

			<label for="email">E-mail:</label>
			<input type="email" name="email" id="email"
			value="<?php if(isset($res)){echo $res['email'];} ?>">

			<label for="rua">Rua:</label>
			<input type="text" name="rua" id="rua"
			value="<?php if(isset($res)){echo $res['rua'];} ?>">

			<label for="numero_rua">Número:</label>
			<input type="number" name="numero_rua" id="numero_rua"
			value="<?php if(isset($res)){echo $res['numero_rua'];} ?>">

			<label for="estado">Estado:</label>
			<input type="text" name="estado" id="estado"
			value="<?php if(isset($res)){echo $res['estado'];} ?>">

			<label for="cep">CEP:</label>
			<input type="text" name="cep" id="cep"
			value="<?php if(isset($res)){echo $res['cep'];} ?>">

			<br>

			<button type="submit"><?php if(isset($res)){echo 'Atualizar';} else{echo 'enviar';} ?></button>
			<button type="reset">Limpar</button>
		</form>
	</section>

	<section class="apresentacao">
		<table>
			<tr id="titulo">
				<td>ID</td>
				<td>NOME FANTASIA</td>
				<td>RAZÃO SOCIAL</td>
				<td>TELEFONE</td>
				<td>E-MAIL</td>
				<td>RUA</td>
				<td>NÚMERO</td>
				<td>ESTADO</td>
				<td colspan="2">CEP</td>
			</tr>
			<?php
				$fornecedores = $p->buscarDadosFornecedores();
				if(count($fornecedores) > 0) {
					for($i = 0; $i < count($fornecedores); $i++) {
						echo "<tr>";
						foreach($fornecedores[$i] as $k => $v) {
							echo "<td>".$v."</td>";
						}
			?>
						<td class="botoes">
							<a href="fornecedores.php?up_id_fornecedores=<?php echo $fornecedores[$i]['id_fornecedor'] ?>">Editar</a>
							<a href="fornecedores.php?exc_id=<?php echo $fornecedores[$i]['id_fornecedor'] ?>">Excluir</a>
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