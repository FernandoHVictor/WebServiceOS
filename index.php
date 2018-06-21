<?php

require 'Slim/Slim.php';
require 'conectar.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');

$app->get('/', function () {
    echo "Ola";
});

$app->get('/dados', 'getDadosPrincipais');
$app->get('/menu', 'getMenu');
//$app->put('/contatos', 'adicionaContatos');
//$app->delete('/contatos/:id', 'deletaContatos');
//$app->get('/operadoras', 'listaOperadoras');
//$app->post('/parceiroDetalhe','parceiroDetalhes');

$app->run();

//function listaContatos() {
//    $stmt = getConn()->query("SELECT c.*,o.operadora FROM contatos c "
//            . "join operadora o on o.id = c.operadora");
//    $categorias = $stmt->fetchAll(PDO::FETCH_OBJ);
//    echo json_encode($categorias);
//}
function getDadosPrincipais() {
    $stmt = getConn()->query("SELECT propriedade,valor from info");
    $info = $stmt->fetchAll(PDO::FETCH_OBJ);
    $retorno = [];
    foreach ($info as $row) {
        $retorno[$row->propriedade] = $row->valor;
    }
    echo json_encode($retorno);
}

function getMenu() {
    $stmt = getConn()->query("SELECT id,categoria from categorias_menu order by ordem");
    $categorias = $stmt->fetchAll(PDO::FETCH_OBJ);
    $retorno = [];
    foreach ($categorias as $categoria) {
        $stmt = getConn()->prepare("SELECT * from menu where categoria = :id");
        $stmt->bindValue(':id', $categoria->id);
        $stmt->execute();
        $menusCategoria = $stmt->fetchAll(PDO::FETCH_OBJ);
        $retorno[$categoria->categoria] = $menusCategoria;
    }
     echo json_encode($retorno);
}

//
//function listaOperadoras() {
//    $stmt = getConn()->query("SELECT * FROM operadora");
//    $categorias = $stmt->fetchAll(PDO::FETCH_OBJ);
//    echo json_encode($categorias);
//}
//
//function adicionaContatos() {
//    $dados = getDataInput();
//    $stmt = getConn()->prepare("INSERT INTO contatos (nome, telefone, operadora) values (:nome, :telefone, :operadora)");
//    $stmt->bindParam(":nome", $dados->nome);
//    $stmt->bindParam(":telefone", $dados->telefone);
//    $stmt->bindParam(":operadora", $dados->operadora->id);
//    escreve($stmt->execute());
//}
//
//function deletaContatos($id) {
//    $stmt = getConn()->prepare("DELETE FROM contatos WHERE id = :id");
//    $stmt->bindParam(":id", $id);
//    escreve($stmt->execute());
//}

function getDataInput() {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    return $request;
}

function escreve($string) {
    echo json_encode($string);
}
