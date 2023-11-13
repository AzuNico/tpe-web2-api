<?php
require_once('app/models/model.php');
require_once('app/helpers/adapter.api.helper.php');
class OwnerModel extends Model
{


    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'duenio';
        $this->allowedFields = ['ID', 'NOMBRE', 'MAIL', 'TELEFONO'];
        $this->dbFieldsMap = [
            'id' => 'ID',
            'fullName' => 'NOMBRE',
            'contactEmail' => 'MAIL',
            'phoneNumber' => 'TELEFONO',
        ];
    }

    public function getOwners($order = 'ASC')
    {
        $query = $this->db->prepare('SELECT * FROM `duenio` ORDER BY `ID` ' . $order);
        $query->execute();
        $query = $query->fetchAll(PDO::FETCH_OBJ);
        $owners = mapDataList($query, $this->dbFieldsMap);
        return $owners;
    }

    public function getOwnerByID($id)
    {
        $query = $this->db->prepare('SELECT * FROM `duenio` WHERE `ID` = ?');
        $query->execute([$id]);
        $query = $query->fetch(PDO::FETCH_OBJ);
        $owner = mapObject($query, $this->dbFieldsMap);
        return $owner;
    }

    public function getFilterBy($filter){
        echo 'a';
        $query = $this->db->prepare('SELECT * FROM `duenio` WHERE id LIKE ? OR nombre LIKE ? OR mail LIKE ? OR telefono LIKE ?');
        $query->execute([$filter,$filter,$filter,$filter]);
        $query = $query->fetchAll(PDO::FETCH_OBJ);
        $owners = mapDataList($query, $this->dbFieldsMap);
        return $owners;
    }

    //Esta funcion hace un alta de un dueño
    public function insertOwner($name, $email, $tel)
    {
        $query = $this->db->prepare('INSERT INTO `duenio`(`NOMBRE`, `MAIL`,`TELEFONO` ) VALUES (?,?,?)');
        $query->execute([$name, $email, $tel]);
        return $this->db->lastInsertId();
    }

    //Esta funcion edita un dueño
    public function editOwner($id, $name, $email, $tel)
    {
        $query = $this->db->prepare('UPDATE `duenio` SET `NOMBRE`=?,`MAIL`=?,`TELEFONO`=? WHERE `ID`=?');
        $query->execute([$name, $email, $tel, $id]);
    }

    //Esta funcion elimina un dueño
    public function deleteOwner($id)
    {
        $query = $this->db->prepare('DELETE FROM `duenio` WHERE `ID`=?');
        $query->execute([$id]);
    }
}
