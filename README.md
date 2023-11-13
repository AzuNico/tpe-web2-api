# tpe-web2-api
Trabajo Práctico Especial 3 - API REST realizada en PHP

# Documentación de la API

## Endpoints

### Dueños

- `GET /owners`: Recupera una lista de todos los Dueños.
- `GET /owners/:ID`: Recupera un propietario específico por ID.
- `POST /owners`: Crea un nuevo propietario.
- `PUT /owners`: Actualiza un propietario.
- `DELETE /owners/:ID`: Elimina un propietario específico por ID.

### Mascotas

- `GET /pets`: Recupera una lista de todas las mascotas.
- `GET /pets/:ID`: Recupera una mascota específica por ID.
- `PUT /pets`: Actualiza una mascota.

### Registro e inicio de sesión de usuario

- `POST /register`: Registra un nuevo usuario.
- `POST /login`: Inicia sesión de un usuario.

## Uso

Para usar esta API, envía una solicitud HTTP al endpoint deseado con el método apropiado. Para los endpoints que requieren un ID, reemplaza `:ID` con el ID del recurso.

Para las solicitudes `POST` y `PUT`, incluye los datos del recurso en el cuerpo de la solicitud en formato JSON.

Esta API utiliza JWT para autenticar al usuario, sólo los usuarios registrados pueden crear recursos `POST`, editarlos `PUT` o eliminarlos `DELETE`.

# Ejemplos de Solicitudes y Respuestas

Las respuestas de los servicios cumplen con la siguiente estructura:

```json
{
    "data": "Información solicitada",
    "status": 200,
    "message": "OK"
}
```

## AVISO
Los ejemplos que vienen a continuación, asumen que la información solicitada se encuentra dentro del atributo "data".

## Dueños

Solicitud:

### `GET /owners`

Respuesta:
```json
[
    {
    "id": 1,
    "fullName": "Juan Perez",
    "contactEmail": "juanperez@gmail.com",
    "phoneNumber": "42423423"
},
    {
    "id": 2,
    "fullName": "Ana Gomez",
    "contactEmail": "anagomez@gmail.com",
    "phoneNumber": "42423423"
}
]
```

Solicitud:

### `GET /owners/1`

Respuesta:
```json
{
    "id": 1,
    "fullName": "Juan Perez",
    "contactEmail": "juanperez@gmail.com",
    "phoneNumber": "42423423"
}
```

### `POST /owners`

Solicitud:
```http
POST /owners
Content-Type: application/json

{
    "fullName": "Juan Perez",
    "contactEmail": "juanperez@gmail.com",
    "phoneNumber": "42423423"
}
```

Respuesta:

```json
{
    "status":200,
    "message": "Created successfully"
}
```

### `PUT /owners`

Solicitud:
```http
PUT /owners
Content-Type: application/json

{
    "id": 1,
    "fullName": "Juan Perez",
    "contactEmail": "juanperez@gmail.com",
    "phoneNumber": "42423423"
}
```

Respuesta:

```json
{
    "status":200,
    "message": "Updated successfully"
}
```

Solicitud:

### `DELETE /owners/1`

Respuesta:
```json
{
    "status":200,
    "message": "Deleted successfully"
}
```

## Errores

En caso de error, la API devolverá un código de estado HTTP y un objeto JSON con más información sobre el error.