<?php
class Model
{
    protected $db;

    function __construct()
    {
        $this->db = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB . ';charset=utf8', MYSQL_USER, MYSQL_PASS);
        $this->deploy();
    }

    function deploy()
    {
        // Chequear si hay tablas
        $query = $this->db->query('SHOW TABLES');
        $tables = $query->fetchAll(); // Nos devuelve todas las tablas de la db
        if (count($tables) == 0) {
            // Si no hay crearlas
            $sql = <<<END
                --
                    -- Estructura de tabla para la tabla `duenio`
                    --

                    CREATE TABLE `duenio` (
                    `ID` int(11) NOT NULL,
                    `NOMBRE` varchar(50) NOT NULL,
                    `MAIL` varchar(100) NOT NULL,
                    `TELEFONO` varchar(100) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

                    --
                    -- Volcado de datos para la tabla `duenio`
                    --

                    INSERT INTO `duenio` (`ID`, `NOMBRE`, `MAIL`, `TELEFONO`) VALUES
                    (1, 'Luca', 'ochoa.luca@gmail.com', '2494536560'),
                    (2, 'Pancho', 'pancho@gmail.com', '249412345');

                    -- --------------------------------------------------------

                    --
                    -- Estructura de tabla para la tabla `mascotas`
                    --

                    CREATE TABLE `mascotas` (
                    `ID` int(11) NOT NULL,
                    `NOMBRE` varchar(50) NOT NULL,
                    `EDAD` int(11) NOT NULL,
                    `PESO` double NOT NULL,
                    `TIPO` varchar(50) NOT NULL,
                    `ID_DUENIO` int(11) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

                    --
                    -- Volcado de datos para la tabla `mascotas`
                    --

                    INSERT INTO `mascotas` (`ID`, `NOMBRE`, `EDAD`, `PESO`, `TIPO`, `ID_DUENIO`) VALUES
                    (1, 'choco', 14, 10, 'perro', 1),
                    (2, 'kurt', 3, 15, 'perro', 1),
                    (3, 'cala', 3, 15, 'perro', 2);

                    -- --------------------------------------------------------

                    --
                    -- Estructura de tabla para la tabla `usuarios`
                    --

                    CREATE TABLE `usuarios` (
                    `ID` int(11) NOT NULL,
                    `USER` varchar(50) NOT NULL,
                    `PASSWORD` varchar(255) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

                    --
                    -- Volcado de datos para la tabla `usuarios`
                    --

                    INSERT INTO `usuarios` (`ID`, `USER`, `PASSWORD`) VALUES
                    (1, 'webadmin', '$2y$10$z3QlvohpRdWh2bggGdsDH.Ke/6JgbbWZzZdCmcnZFVjPV/1OtuhsO');

                    --
                    -- Índices para tablas volcadas
                    --

                    --
                    -- Indices de la tabla `duenio`
                    --
                    ALTER TABLE `duenio`
                    ADD PRIMARY KEY (`ID`);

                    --
                    -- Indices de la tabla `mascotas`
                    --
                    ALTER TABLE `mascotas`
                    ADD PRIMARY KEY (`ID`);

                    --
                    -- Indices de la tabla `usuarios`
                    --
                    ALTER TABLE `usuarios`
                    ADD PRIMARY KEY (`ID`);

                    --
                    -- AUTO_INCREMENT de las tablas volcadas
                    --

                    --
                    -- AUTO_INCREMENT de la tabla `duenio`
                    --
                    ALTER TABLE `duenio`
                    MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

                    --
                    -- AUTO_INCREMENT de la tabla `mascotas`
                    --
                    ALTER TABLE `mascotas`
                    MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

                    --
                    -- AUTO_INCREMENT de la tabla `usuarios`
                    --
                    ALTER TABLE `usuarios`
                    MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
                    COMMIT;
                END;
            $this->db->query($sql);
        }
    }
}
