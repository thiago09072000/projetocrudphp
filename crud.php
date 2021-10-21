<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $telefone = (isset($_POST["telefone"]) && $_POST["telefone"] != null) ? $_POST["telefone"] : NULL;
} else if (!isset($id)) {
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = NULL;
    $telefone = NULL;
}

try {
    $conexao = new PDO("mysql:host=localhost;dbname=crudphp", "root", "123456");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("set names utf8");
} catch (PDOException $erro) {
    echo "Erro na conexão:".$erro->getMessage();
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
    try {
        if ($id != "") {
            $stmt = $conexao->prepare("UPDATE agenda SET nome=?, telefone=? WHERE id = ?");
            $stmt->bindParam(3, $id);
        } else {
            $stmt = $conexao->prepare("INSERT INTO agenda (nome, telefone) VALUES (?, ?)");
        }
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $telefone);
 
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $nome = null;
                $telefone = null;
            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM agenda WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $nome = $rs->nome;
            $telefone = $rs->telefone;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM agenda WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Registo foi excluído com êxito";
            $id = null;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Agenda Telefônica</title>
        </head>
        <body>
            <form action="?act=save" method="POST" name="form1" >
                <h1>Agenda Telefônica</h1>
                <hr>
                <input type="hidden" name="id" <?php

                if (isset($id) && $id != null || $id != "") {
                    echo "value=\"{$id}\"";
                }
                ?> />
                Nome:
               <input type="text" name="nome" <?php

               if (isset($nome) && $nome != null || $nome != "") {
                   echo "value=\"{$nome}\"";
               }
               ?> />

                Telefone:
               <input type="text" name="telefone" <?php

               if (isset($telefone) && $telefone != null || $telefone != "") {
                   echo "value=\"{$telefone}\"";
               }
               ?> />
               <input type="submit" value="salvar" />
               <input type="reset" value="Novo" />
               <hr>
            </form>
            <table border="1" width="100%">
                <tr>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
                <?php

                try {
                    $stmt = $conexao->prepare("SELECT * FROM agenda");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            echo "<td>".$rs->nome."</td><td>".$rs->telefone."</td><td><center>
                                        <a href=\"?act=upd&id=".$rs->id."\">[Alterar]</a>"
                                       ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                       ."<a href=\"?act=del&id=".$rs->id."\">[Excluir]</a></center></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Erro: Não foi possível recuperar os dados do banco de dados";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
            </table>
        </body>
    </html>