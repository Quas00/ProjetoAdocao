    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #F08080;
        }
        form {
            background-color: #F08080;
            padding: 20px;
            border-radius: 5px;
            color: white;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        button, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button[type="submit"] {
            background-color: white;
            color: #F08080;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background-color: #F08080;
            color: white;
        }
    </style>
    <div class="container">
        <h1>Agendamento de Doações</h1>
        <form method="post" action="">

            <label for="address">Endereço:</label>
            <input type="text" id="address" name="address" required>

            <label for="date">Data da Doação:</label>
            <input type="date" id="date" name="date" required>

            <label for="description">O que é a doação:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <input type="hidden" name="id_user" id="id_user" value="<?php echo $id_user ?>">

            <button type="submit" name="botao">Agendar doação</button>
            <?php               
                if (isset($_POST['botao'])) {
                    $endereco = $_POST['address'] ?? null;
                    $data = $_POST['date'] ?? null;
                    $descricao = $_POST['description'] ?? null;
                    $id_usuario = $_POST['id_user'] ?? null;
                
                    if (empty($data)) {
                        echo "O campo de data não pode estar vazio.";
                        return; // Impede a execução da inserção
                    }
                
                    var_dump($endereco, $data, $descricao, $id_usuario); // Debug
                
                    $agendamento = "INSERT INTO tb_agendoacao (endereco_agendoacao, data_agendoacao, desc_agendoacao, id_user) VALUES (:endereco, :data, :descricao, :id_user)";
                    
                    try {
                        $result = $conect->prepare($agendamento);
                        $result->bindParam(':endereco', $endereco, PDO::PARAM_STR);
                        $result->bindParam(':data', $data, PDO::PARAM_STR);
                        $result->bindParam(':descricao', $descricao, PDO::PARAM_STR);
                        $result->bindParam(':id_user', $id_usuario, PDO::PARAM_INT);
                
                        $result->execute();
                        
                        // Resto do código...
                    } catch (PDOException $e) {
                        echo "<strong>ERRO DE PDO= </strong>" . $e->getMessage();
                    }
                }
              ?>
        </form>
    </div>
