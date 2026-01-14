<?php
/**
 * CONEXIÓN PDO PARA SQLITE - FastFood
 * Gestiona la conexión a la base de datos SQLite
 */

class DatabaseConnection {
    private static $instance = null;
    private $pdo;
    private $db_path;

    private function __construct() {
        // Ruta del archivo de base de datos
        $this->db_path = __DIR__ . '/../db/fastfood.db';
        
        try {
            // Crear conexión PDO a SQLite
            $this->pdo = new PDO('sqlite:' . $this->db_path);
            
            // Configurar errores
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Habilitar claves foráneas en SQLite
            $this->pdo->exec('PRAGMA foreign_keys = ON');
            
            // Crear tablas si no existen
            $this->initializaDatabase();
            
        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene la instancia única de la conexión (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtiene la conexión PDO
     */
    public function getConnection() {
        return $this->pdo;
    }

    /**
     * Inicializa la base de datos ejecutando el schema
     */
    private function initializaDatabase() {
        $schema_file = __DIR__ . '/../db/schema.sql';
        
        if (file_exists($schema_file)) {
            $schema = file_get_contents($schema_file);
            $this->pdo->exec($schema);
        }
    }

    /**
     * Inserta un registro
     */
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        
        if ($stmt->execute(array_values($data))) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    /**
     * Actualiza registros
     */
    public function update($table, $data, $where) {
        $set = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));
        $whereCondition = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($where)));
        
        $sql = "UPDATE $table SET $set WHERE $whereCondition";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute(array_merge(array_values($data), array_values($where)));
    }

    /**
     * Obtiene registros
     */
    public function select($table, $where = null, $order = null) {
        $sql = "SELECT * FROM $table";
        
        if ($where) {
            $conditions = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($where)));
            $sql .= " WHERE $conditions";
        }
        
        if ($order) {
            $sql .= " ORDER BY $order";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $params = $where ? array_values($where) : [];
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }

    /**
     * Obtiene un registro
     */
    public function selectOne($table, $where) {
        $conditions = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($where)));
        $sql = "SELECT * FROM $table WHERE $conditions LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($where));
        
        return $stmt->fetch();
    }

    /**
     * Elimina registros
     */
    public function delete($table, $where) {
        $conditions = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($where)));
        $sql = "DELETE FROM $table WHERE $conditions";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_values($where));
    }

    /**
     * Ejecuta una consulta personalizada
     */
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Obtiene la ruta de la BD
     */
    public function getDatabasePath() {
        return $this->db_path;
    }
}

// Obtener conexión
$db = DatabaseConnection::getInstance()->getConnection();
?>
