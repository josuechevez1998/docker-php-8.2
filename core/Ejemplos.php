<?php

$mysql = new \Connections\MysqlPdo();

// Insertar un usuario
$mysql->insert('users', [
    'name' => 'Josué',
    'email' => 'josue@mail.com'
]);

// Actualizar el email del usuario con id=5
$mysql->update('users', ['email' => 'nuevo@mail.com'], 'id = :id', [':id' => 5]);

// Eliminar usuario con id=5
$mysql->delete('users', 'id = :id', [':id' => 5]);

// Obtener todos los usuarios
$users = $mysql->selectAll('users');

// Obtener usuarios con email específico
$user = $mysql->selectWhere('users', 'email = :email', [':email' => 'josue@mail.com']);

// Ejecutar un stored procedure sin parámetros
$resultadoSP1 = $mysql->callStoredProcedure('obtener_todos_los_usuarios');

// Ejecutar un stored procedure con parámetros
$resultadoSP2 = $mysql->callStoredProcedure('buscar_usuario_por_email', [
    'correo' => 'josue@mail.com'
]);

// Imprimir resultados
echo "<pre>";
print_r($resultadoSP1);
print_r($resultadoSP2);
echo "</pre>";
