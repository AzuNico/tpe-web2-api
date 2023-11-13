<?php
require_once('app/models/model.php');
require_once('app/helpers/adapter.api.helper.php');
class PetModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'mascotas';
        $this->allowedFields = ['ID', 'NOMBRE', 'EDAD', 'PESO', 'TIPO', 'ID_DUENIO'];
        $this->dbFieldsMap = [
            'id' => 'ID',
            'name' => 'NOMBRE',
            'age' => 'EDAD',
            'weight' => 'PESO',
            'type' => 'TIPO',
            'ownerId' => 'ID_DUENIO'
        ];
    }

    public function getPets($order = 'ASC')
    {
        $query = $this->db->prepare('SELECT * FROM `mascotas`' . ' ORDER BY `ID` ' . $order);
        $query->execute();
        $query = $query->fetchAll(PDO::FETCH_OBJ);
        $pets = mapDataList($query, $this->dbFieldsMap);
        return $pets;
    }

    public function getPetByID($id)
    {
        $query = $this->db->prepare('SELECT * FROM `mascotas` WHERE `ID` = ?');
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getPetsByOwner($idOwner)
    {
        $query = $this->db->prepare('SELECT * FROM `mascotas` WHERE ID_DUENIO = ?');
        $query->execute([$idOwner]);
        $query = $query->fetchAll(PDO::FETCH_OBJ);
        $pets = mapDataList($query, $this->dbFieldsMap);
        return $pets;
    }

    //Esta funcion hace un alta de una mascota la tabla mascotas tiene columnas ID, NOMBRE, EDAD ,PESO ,TIPO,ID_DUENIO
    public function insertPet($name, $age, $weight, $type, $idowner)
    {
        $query = $this->db->prepare('INSERT INTO `mascotas`(`NOMBRE`, `EDAD`, `PESO`, `TIPO`, `ID_DUENIO`) VALUES (?,?,?,?,?)');
        $query->execute([$name, $age, $weight, $type, $idowner]);
        // return $this->db->lastInsertId();
    }

    //Esta funcion edita una mascota
    public function editPet($id, $name, $age, $weight, $type, $idowner)
    {
        $query = $this->db->prepare('UPDATE `mascotas` SET `NOMBRE`=?,`EDAD`=?,`PESO`=?,`TIPO`=?,`ID_DUENIO`=? WHERE `ID`=?');
        $query->execute([$name, $age, $weight, $type, $idowner, $id]);
    }

    //Esta funcion elimina una mascota
    public function deletePet($id)
    {
        $query = $this->db->prepare('DELETE FROM `mascotas` WHERE `ID`=?');
        $query->execute([$id]);
    }
}
