<?php

namespace Connections;

use PDO;
use PDOException;

class MysqlPdo
{
    protected $host;
    protected $port;
    protected $db;
    protected $user;
    protected $password;
    protected $pdo;

    public function __construct()
    {
        // Cargar datos de conexión desde variables de entorno
        $this->host     = $_ENV['DB_HOST'];
        $this->port     = $_ENV['DB_PORT'] ?? 3306; // puerto por defecto 3306
        $this->db       = $_ENV['DB_NAME'];
        $this->user     = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];

        // Intentar conectar
        $this->connect();
    }

    protected function connect()
    {
        // Construir la cadena de conexión para PDO
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset=utf8mb4";

        try {
            // Crear instancia PDO
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            // Configurar para lanzar excepciones y traer resultados como arreglo asociativo
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Mostrar error simple y detener la ejecución
            die("No se pudo conectar a la base de datos: " . $e->getMessage());
        }
    }

    /**
     * Insert: insertar registros en base de datos
     *
     * @param string $table nombre de la tabla
     * @param array $data columnas de la tabla
     * @return void
     */
    public function insert(string $table, array $data)
    {
        // Crear las columnas y los placeholders (:columna)
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($k) => ":$k", array_keys($data)));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        $stmt = $this->pdo->prepare($sql);

        // Asociar valores con los placeholders
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        // Ejecutar y devolver true/false según éxito
        return $stmt->execute();
    }

    // Método para actualizar datos en una tabla con condición
    public function update(string $table, array $data, string $where, array $whereParams = [])
    {
        // Crear la parte SET de la consulta: col1 = :set_col1, col2 = :set_col2, etc.
        $setClause = implode(", ", array_map(fn($k) => "$k = :set_$k", array_keys($data)));

        $sql = "UPDATE $table SET $setClause WHERE $where";

        $stmt = $this->pdo->prepare($sql);

        // Asociar valores de actualización
        foreach ($data as $key => $value) {
            $stmt->bindValue(":set_$key", $value);
        }

        // Asociar parámetros del WHERE (pueden venir con ':' o sin, pero mejor que sí)
        foreach ($whereParams as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt->execute();
    }

    // Método para borrar filas según condición
    public function delete(string $table, string $where, array $whereParams = [])
    {
        $sql = "DELETE FROM $table WHERE $where";

        $stmt = $this->pdo->prepare($sql);

        foreach ($whereParams as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt->execute();
    }

    // Método para obtener todos los registros de una tabla
    public function selectAll(string $table)
    {
        $sql = "SELECT * FROM $table";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    // Método para obtener registros con condición WHERE
    public function selectWhere(string $table, string $where, array $whereParams = [])
    {
        $sql = "SELECT * FROM $table WHERE $where";

        $stmt = $this->pdo->prepare($sql);

        foreach ($whereParams as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function callStoredProcedure(string $procedureName, array $params = [])
    {
        // Armar llamada tipo: CALL nombre_procedimiento(:param1, :param2, ...)
        $placeholders = implode(", ", array_map(fn($k) => ":$k", array_keys($params)));
        $sql = "CALL $procedureName($placeholders)";

        $stmt = $this->pdo->prepare($sql);

        // Asociar valores
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
