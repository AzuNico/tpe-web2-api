<?php
require_once('app/dtos/owner.dto.php');

function mapObject($object, $map)
{
    $objectDTO = [];
    foreach ($map as $key => $value) {
        $objectDTO[$key] = $object[$value];
    }
    return $objectDTO;
}

//  Función que enmascara los campos de la base de datos con los nombres de los atributos que se usan para el cliente.
function mapDataList($dataList, $map)
{
    $dataToClient = [];
    foreach ($dataList as $object) {
        $newObject = new stdClass();
        foreach ($map as $clientAttribute => $dbField) {
            if (property_exists($object, $dbField)) {
                $newObject->$clientAttribute = $object->$dbField;
            }
        }
        $dataToClient[] = $newObject;
    }
    return $dataToClient;
}


// Mapea el atributo del cliente con el campo de la base de datos.
function mapAttributeToDatabaseField($attribute, $dbFields)
{
    if (array_key_exists($attribute, $dbFields)) {
        return $dbFields[$attribute];
    }
    return 'ID';
}
