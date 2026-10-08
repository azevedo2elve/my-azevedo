<?php

try {
    $redis = new Redis();
    $redis->connect('redis', 6379);

    $redis->set("teste_chave", "Redis funcionando com sucesso no Docker! 🚀");
    $mensagem = $redis->get("teste_chave");

    echo "<h1>" . $mensagem . "</h1>";
} catch (Exception $e) {
    echo "<h1>Erro ao conectar ao Redis:</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}