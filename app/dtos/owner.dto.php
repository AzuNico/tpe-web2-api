<?php
class OwnerDTO
{
    public $id;
    public $fullName;
    public $contactEmail;

    public $phoneNumber;

    function toDataBase()
    {
        return [
            'ID' => $this->id,
            'NOMBRE' => $this->fullName,
            'MAIL' => $this->contactEmail,
            'TELEFONO' => $this->phoneNumber
        ];
    }
}
