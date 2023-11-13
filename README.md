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

## Ordenamiento

Por defecto, las consultas de tipo `GET` se ordenan por `id` de manera `ascendente`.

Para ordenar los resultados de busqueda del metodo `GET` debera utilizar los siguientes query params:

#### Sort

### `GET /{recurso}?sort={nombreAtributo}`

Ejemplo:

```http
GET /owners?sort=fullName
```

#### Order

Establece la dirección del ordenamiento.
Utilice `D` para ordenar de manera `descendente` de cualquier otra forma lo ordenara por su valor por defecto (`ascendente`)

### `GET /{recurso}?order=D`

Ejemplo:

```http
GET /owners?order=D
```

## Bonus Track

Puede mejorar su ordenamiento si realiza una combinación de ambos parametros `sort` y `order`

Ejemplo:

```http
GET /owners?sort=fullName&order=D
```

# Ejemplos de Solicitudes y Respuestas

Las respuestas de los servicios cumplen con la siguiente estructura:

```json
{
    "data": "Información solicitada",
    "status": 200,
    "message": "OK"
}
```

## Login

### `POST /login`

Solicitud:

```http
POST /login
Content-Type: application/json

{
    "user": "juanperez@gmail.com",
    "password": "1234"
}
```

Respuesta:

```json
{
  "data": {
    "token": "header.payload.signature"
  },
  "status": 200,
  "message": "Logueado correctamente"
}
```

### `POST /register`

El atributo `user` debe ser un formato de mail válido.

Solicitud:

```http
POST /register
Content-Type: application/json

{
    "user": "juanperez@gmail.com",
    "password": "1234"
}
```

Respuesta:

```json
{
  "status": 201,
  "message": "Se ha registrado correctamente"
}
```

## Dueños

Solicitud:

### `GET /owners`

Respuesta:

```json
{
  "data": [
    {
      "id": 1,
      "fullName": "Juan Perez",
      "contactEmail": "juanperez@gmail.com",
      "phoneNumber": "42423423",
      "pets": []
    },
    {
      "id": 2,
      "fullName": "Ana Gomez",
      "contactEmail": "anagomez@gmail.com",
      "phoneNumber": "42423423",
      "pets": []
    }
  ],
  "status": 200,
  "message": "OK"
}
```

Solicitud:

### `GET /owners/1`

Respuesta:

```json
{
  "data": {
    "id": 1,
    "fullName": "Juan Perez",
    "contactEmail": "juanperez@gmail.com",
    "phoneNumber": "42423423",
    "pets": []
  },
  "status": 200,
  "message": "OK"
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
    "status":201,
    "message": "Created successfully"
}
```

### `PUT /owners`

Solo podra editar los datos del dueño, no del atributo `pets`.

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

Si el `owner` posee mascotas asociadas a él el servicio arrojara error 400 Bad Request.

Respuesta:

```json
{
    "status":200,
    "message": "Deleted successfully"
}
```

## Mascotas

Solicitud:

### `GET /pets`

Respuesta:

```json
{
  "data": [
    {
      "id": 1,
      "name": "Lana",
      "age": 2,
      "weight": 3,
      "type": "Gato",
      "ownerId": 2
    },
    {
      "id": 2,
      "name": "Luna",
      "age": 5,
      "weight": 7,
      "type": "Perro",
      "ownerId": 3
    }
  ],
  "status": 200,
  "message": "OK"
}

```

Solicitud:

### `GET /pets/1`

Respuesta:

```json
{
  "data": {
    "id": 1,
    "name": "Lana",
    "age": 2,
    "weight": 3,
    "type": "Gato",
    "ownerId": 2
  },
  "status": 200,
  "message": "OK"
}

```

### `POST /pets`

Solicitud:

```http
POST /pets
Content-Type: application/json

{
    "name": "Lana",
    "age": 2,
    "weight": 3,
    "type": "Gato",
    "ownerId": 2
}
```

Respuesta:

```json
{
    "status":201,
    "message": "Created successfully"
}
```

### `PUT /pets`

Solicitud:

```http
PUT /pets
Content-Type: application/json

{
    "id": 1,
    "name": "Lana",
    "age": 2,
    "weight": 3,
    "type": "Gato",
    "ownerId": 2
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

### `DELETE /pets/1`

Respuesta:

```json
{
    "status":200,
    "message": "Deleted successfully"
}
```

## Errores

En caso de error, la API devolverá un código de estado HTTP y un objeto JSON con más información sobre el error.

## Ejemplos

Respuesta:

```json
{
    "status": 500,
    "message": "Internal Server Error"
}
```

Respuesta:

```json
{
    "status": 404,
    "message": "Not found"
}
```

## Autores

Este proyecto fue creado por:

- Nicolás Azuaga azuaganicolas@gmail.com
- Luca Ochoa Marcos ochoa.luca@gmail.com