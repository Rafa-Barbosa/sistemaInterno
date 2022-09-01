<?php
Class Produto {
	private $pdo;

	public function __construct($dbname, $host, $user, $senha){
		try {
			$this->pdo = new PDO("mysql:dbname=".$dbname."; host=".$host, $user, $senha);
		} catch (PDOException $e){
			echo "Erro com o banco de dados: ".$e->getMessage();
			exit();
		} catch (Exception $e){
			echo "Erro generico: ".$e->getMessage();
			exit();
		}
	}


	// FUNÇÕES PARA INCLUIR OS DADOS NO BANCO

	public function incluirFornecedor($nome, $razao_social, $fone, $email, $rua, $numero_rua, $estado, $cep) {
		$cmd = $this->pdo->prepare("SELECT id_fornecedor from fornecedores WHERE nome_fantasia = :nome");
		$cmd->bindvalue(":nome", $nome);
		$cmd->execute();
		if($cmd->rowCount() > 0){//nome já existe no banco
			return false;
		} else {
			$cmd = $this->pdo->prepare("INSERT INTO fornecedores(nome_fantasia, razao_social, fone, email, rua, numero_rua, estado, cep) VALUES (:nome, :razao_social, :fone, :email, :rua, :numero_rua, :estado, :cep)");
			$cmd->bindvalue(":nome", $nome);
			$cmd->bindvalue(":razao_social", $razao_social);
			$cmd->bindvalue(":fone", $fone);
			$cmd->bindvalue(":email", $email);
			$cmd->bindvalue(":rua", $rua);
			$cmd->bindvalue(":numero_rua", $numero_rua);
			$cmd->bindvalue(":estado", $estado);
			$cmd->bindvalue(":cep", $cep);
			$cmd->execute();
			return true;
		}
	}

	public function incluirProduto($produto, $marca, $modelo, $cor, $ano, $preco, $quantidade, $fabricacao, $id_fornecedor) {
		$cmd = $this->pdo->prepare("SELECT id_produto FROM produtos WHERE produto = :produto");
		$cmd->bindvalue(":produto", $produto);
		$cmd->execute();
		if($cmd->rowCount() > 0) {
			return false;
		} else {
			$cmd = $this->pdo->prepare("INSERT INTO produtos(produto, marca, modelo_moto, cor, ano, preco, quantidade, data_fabricacao, id_fornecedor) values(:produto, :marca, :modelo, :cor, :ano, :preco, :quantidade, :fabricacao, :id_fornecedor)");
			$cmd->bindvalue(":produto", $produto);
			$cmd->bindvalue(":marca", $marca);
			$cmd->bindvalue(":modelo", $modelo);
			$cmd->bindvalue(":cor", $cor);
			$cmd->bindvalue(":ano", $ano);
			$cmd->bindvalue(":preco", $preco);
			$cmd->bindvalue(":quantidade", $quantidade);
			$cmd->bindvalue(":fabricacao", $fabricacao);
			$cmd->bindvalue(":id_fornecedor", $id_fornecedor);
			$cmd->execute();
			return true;
		}
	}


	// FUNÇÕES QUE RETORNA OS DADOS DA TABELA

	public function buscarDadosFornecedores() {
		$res = array();
		$cmd = $this->pdo->query("SELECT * FROM fornecedores");
		$res = $cmd->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function buscarDadosProdutos() {
		$res = array();
		$cmd = $this->pdo->query("SELECT * FROM produtos");
		$res = $cmd->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}


	// FUNÇÕES QUE RETORNAM VALORES ESPECÍFICOS

	public function consultarDadosFornecedor($id){
		$res = array();
		$cmd = $this->pdo->prepare("SELECT * FROM fornecedores WHERE id_fornecedor = :id");
		$cmd->bindvalue(":id", $id);
		$cmd->execute();
		$res = $cmd->fetch(PDO::FETCH_ASSOC);
		return $res;
	}

	public function consultarDadosProduto($id) {
		$res = array();
		$cmd = $this->pdo->prepare("SELECT * FROM produtos WHERE id_produto = :id");
		$cmd->bindvalue(":id", $id);
		$cmd->execute();
		$res = $cmd->fetch(PDO::FETCH_ASSOC);
		return $res;
	}


	// FUNÇÕES PARA ATUALIZAR VALORES

	public function atualizarFornecedor($id, $nome, $razao_social, $fone, $email, $rua, $numero_rua, $estado, $cep) {
		$cmd = $this->pdo->prepare("UPDATE fornecedores SET nome_fantasia = :nome, razao_social = :razao_social, fone = :fone, email = :email, rua = :rua, numero_rua = :numero_rua, estado = :estado, cep = :cep WHERE id_fornecedor = :id");
		$cmd->bindvalue(":nome", $nome);
		$cmd->bindvalue(":razao_social", $razao_social);
		$cmd->bindvalue(":fone", $fone);
		$cmd->bindvalue(":email", $email);
		$cmd->bindvalue(":rua", $rua);
		$cmd->bindvalue(":numero_rua", $numero_rua);
		$cmd->bindvalue(":estado", $estado);
		$cmd->bindvalue(":cep", $cep);
		$cmd->bindvalue(":id", $id);
		$cmd->execute();
	}

	public function atualizarProduto($id, $produto, $marca, $modelo, $cor, $ano, $preco, $quantidade, $fabricacao, $id_fornecedor) {
		$cmd = $this->pdo->prepare("UPDATE produtos SET produto = :produto, marca = :marca, modelo_moto = :modelo, cor = :cor, ano = :ano, preco = :preco, quantidade = :quantidade, data_fabricacao = :fabricacao, id_fornecedor = :id_fornecedor WHERE id_produto = :id");
		$cmd->bindvalue(":produto", $produto);
		$cmd->bindvalue(":marca", $marca);
		$cmd->bindvalue(":modelo", $modelo);
		$cmd->bindvalue(":cor", $cor);
		$cmd->bindvalue(":ano", $ano);
		$cmd->bindvalue(":preco", $preco);
		$cmd->bindvalue(":quantidade", $quantidade);
		$cmd->bindvalue(":fabricacao", $fabricacao);
		$cmd->bindvalue(":id_fornecedor", $id_fornecedor);
		$cmd->bindvalue(":id", $id);
		$cmd->execute();
	}


	// FUNÇÕES PARA EXCLUSÕES

	public function excluirFornecedor($id) {
		$cmd = $this->pdo->prepare("DELETE FROM fornecedores WHERE id_fornecedor = :id");
		$cmd->bindvalue(":id", $id);
		$cmd->execute();
	}

	public function excluirProduto($id) {
		$cmd = $this->pdo->prepare("DELETE FROM produtos WHERE id_produto = :id");
		$cmd->bindvalue(":id", $id);
		$cmd->execute();
	}


	// FUNÇÕES PARA PESQUISA

	public function pesquisarFornecedorNome($nome) {
		$res = array();
		$cmd = $this->pdo->prepare("SELECT * FROM fornecedores WHERE nome_fantasia LIKE :nome");
		$cmd->bindvalue(":nome", $nome."%");
		$cmd->execute();
		$res = $cmd->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
	public function pesquisarFornecedorId($id) {
		$res = array();
		$cmd = $this->pdo->prepare("SELECT * FROM fornecedores WHERE id_fornecedor = :id");
		$cmd->bindvalue(":id", $id);
		$cmd->execute();
		$res = $cmd->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function pesquisarProduto($produto) {
		$res = array();
		$cmd = $this->pdo->prepare("SELECT * FROM produtos WHERE produto LIKE :produto");
		$cmd->bindvalue(":produto", $produto."%");
		$cmd->execute();
		$res = $cmd->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
	public function pesquisarProdutoId($id_fornecedor) {
		$res = array();
		$cmd = $this->pdo->prepare("SELECT * FROM produtos WHERE id_fornecedor = :id_fornecedor");
		$cmd->bindvalue(":id_fornecedor", $id_fornecedor);
		$cmd->execute();
		$res = $cmd->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

}

?>