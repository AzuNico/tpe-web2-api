<?php

function mapObject($object, $map)
{
    $newObject = new stdClass();
    foreach ($map as $clientAttribute => $dbField) {
        if (property_exists($object, $dbField)) {
            $newObject->$clientAttribute = $object->$dbField;
        }
    }
    return $newObject;
}

//  Función que enmascara los campos de la base de datos con los nombres de los atributos que se usan para el cliente.
function mapDataList($dataList, $map)
{
    $dataToClient = [];
    foreach ($dataList as $object) {
        $dataToClient[] = mapObject($object, $map);
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
