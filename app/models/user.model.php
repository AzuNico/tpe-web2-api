<?php

class UsersModel extends Model {
   
    public function getByUser($user)
    {
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE USER = ?');
        $query->execute([$user]);

        return $query->fetch(PDO::FETCH_OBJ);
    }

   //Esta funcion registra un usuario en la tabla usuarios, las passwords se hashean con BCRYPT
    public function registerUser($user, $password)
    {
        $query = $this->db->prepare('INSERT INTO usuarios (USER, PASSWORD) VALUES (?,?)');
        $query->execute([$user, password_hash($password, PASSWORD_BCRYPT)]);
    }

    //Esta funcion elimina un usuario de la tabla usuarios
    public function deleteUser($id)
    {
        $query = $this->db->prepare('DELETE FROM usuarios WHERE ID=?');
        $query->execute([$id]);
    }

    //Esta funcion edita un usuario de la tabla usuarios
    public function editUser($id, $user, $password)
    {
        $query = $this->db->prepare('UPDATE usuarios SET USER=?, PASSWORD=? WHERE ID=?');
        $query->execute([$user, password_hash($password, PASSWORD_BCRYPT), $id]);
    }

    //Obtener usuario por id
    public function getUserById($id)
    {
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE ID = ?');
        $query->execute([$id]);

        return $query->fetch(PDO::FETCH_OBJ);
    }

    //Obtener todos los usuarios
    public function getAllUsers()
    {
        $query = $this->db->prepare('SELECT * FROM usuarios');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}
