<?php
class PetDTO
{
    public $id;
    public $name;
    public $type;
    public $breed;
    public $age;

    public $weight;

    public $ownerId;

    public function toDataBase()
    {
        return [
            'ID' => $this->id,
            'NOMBRE' => $this->name,
            'TIPO' => $this->type,
            'RAZA' => $this->breed,
            'EDAD' => $this->age,
            'PESO' => $this->weight,
            'ID_DUENIO' => $this->ownerId
        ];
    }
}
