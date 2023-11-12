<?php
require_once('app/dtos/owner.dto.php');

function mapOwner($owner)
{
    $ownerDTO = new OwnerDTO();
    $ownerDTO->id = $owner->ID;
    $ownerDTO->fullName = $owner->NOMBRE;
    $ownerDTO->contactEmail = $owner->MAIL;
    $ownerDTO->phoneNumber = $owner->TELEFONO;
    return $ownerDTO;
}

function mapOwners($owners)
{

    if (!is_array($owners)) {
        return mapOwner($owners);
    }

    $ownersDTO = [];
    foreach ($owners as $owner) {
        $ownerDTO = mapOwner($owner);
        array_push($ownersDTO, $ownerDTO);
    }
    return $ownersDTO;
}

// Mapea un ownerDTO a un owner de la base de datos
function mapOwnerFromRequestToDataBase($request)
{
    $ownerDTO = mapOwner($request);
    return $ownerDTO->toDataBase();
}

// Función que transforma una key del request en un campo de la base de datos del owner
function mapRequestField($field)
{
    $map = [
        'id' => 'ID',
        'fullName' => 'NOMBRE',
        'contactEmail' => 'MAIL'
    ];
    if (array_key_exists($field, $map)) {
        return $map[$field];
    }
    return 'ID';
}



