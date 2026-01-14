<?php
/**
 * MODELO DE USUARIOS
 */

require_once __DIR__ . '/Model.php';

class Usuario extends Model {
    protected $table = 'usuarios';

    /**
     * Obtiene usuario por correo
     */
    public function findByEmail($email) {
        return $this->findOne('correo = ?', [$email]);
    }

    /**
     * Obtiene usuarios con rol específico
     */
    public function getByRole($role) {
        return $this->where('rol = ?', [$role]);
    }

    /**
     * Valida credenciales de login
     */
    public function authenticate($email, $password) {
        $usuario = $this->findByEmail($email);
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        
        return false;
    }

    /**
     * Registra un nuevo usuario
     */
    public function register($nombre, $email, $password) {
        // Verificar si el correo ya existe
        if ($this->findByEmail($email)) {
            return ['success' => false, 'message' => 'El correo ya está registrado'];
        }

        $data = [
            'nombre' => $nombre,
            'correo' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'rol' => 'cliente'
        ];

        $id = $this->create($data);
        
        if ($id) {
            return ['success' => true, 'id' => $id];
        }

        return ['success' => false, 'message' => 'Error en el registro'];
    }

    /**
     * Actualiza perfil del usuario
     */
    public function updateProfile($id, $nombre) {
        return $this->update($id, ['nombre' => $nombre]);
    }

    /**
     * Cambia contraseña
     */
    public function changePassword($id, $oldPassword, $newPassword) {
        $usuario = $this->getById($id);

        if (!$usuario || !password_verify($oldPassword, $usuario['password'])) {
            return ['success' => false, 'message' => 'Contraseña actual incorrecta'];
        }

        $success = $this->update($id, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);

        return [
            'success' => $success,
            'message' => $success ? 'Contraseña actualizada' : 'Error al cambiar contraseña'
        ];
    }
}
?>
