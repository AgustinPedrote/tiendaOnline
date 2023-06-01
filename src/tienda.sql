DROP TABLE IF EXISTS articulos CASCADE;

CREATE TABLE articulos (
    id          bigserial     PRIMARY KEY,
    codigo      varchar(13)   NOT NULL UNIQUE,
    descripcion varchar(255)  NOT NULL,
    precio      numeric(7, 2) NOT NULL,
    stock       int           NOT NULL,
    descuento   numeric(3)    DEFAULT 0   CHECK (descuento >= 0 AND descuento <= 100) --Descuento artículos.
);

DROP TABLE IF EXISTS usuarios CASCADE;

-- Datos para el perfil de usuario.
CREATE TABLE usuarios (
     id          bigserial    PRIMARY KEY,
    usuario     varchar(255) NOT NULL UNIQUE,
    nombre      varchar(255),
    apellidos   varchar(255),
    email       varchar(255),
    telefono    varchar(9),
    password    varchar(255) NOT NULL,
    validado    bool         NOT NULL
);

DROP TABLE IF EXISTS facturas CASCADE;

CREATE TABLE facturas (
    id         bigserial  PRIMARY KEY,
    created_at timestamp  NOT NULL DEFAULT localtimestamp(0),
    usuario_id bigint NOT NULL REFERENCES usuarios (id)
);

DROP TABLE IF EXISTS articulos_facturas CASCADE;

CREATE TABLE articulos_facturas (
    articulo_id bigint NOT NULL REFERENCES articulos (id),
    factura_id  bigint NOT NULL REFERENCES facturas (id),
    cantidad    int    NOT NULL,
    PRIMARY KEY (articulo_id, factura_id)
);

--Comentarios en los artículos.
DROP TABLE IF EXISTS comentarios CASCADE;
CREATE TABLE comentarios (
    created_at  timestamp   NOT NULL DEFAULT localtimestamp(0),
    articulo_id bigint      NOT NULL REFERENCES  articulos   (id),
    usuario_id  bigint      NOT NULL REFERENCES  usuarios    (id),
    comentario  varchar(255),
    PRIMARY KEY (created_at, articulo_id, usuario_id)
);

-- Carga inicial de datos de prueba:

INSERT INTO articulos (codigo, descripcion, precio, stock, descuento)
    VALUES ('18273892389', 'Yogur piña', 200.50, 40, 50),
           ('83745828273', 'Tigretón', 50.10, 2, 0),
           ('51736128495', 'Disco duro SSD 500 GB', 150.30, 0, 0),
           ('83746828273', 'Bollicao', 80.10, 3, 25),
           ('51786128435', 'Bolígrafo', 10.30, 5, 0),
           ('83745228673', 'Ordenador', 550.10, 80, 0),
           ('51786198495', 'Alfombrilla', 5.30, 1, 0);

INSERT INTO usuarios (usuario, password, validado)
    VALUES ('admin', crypt('admin', gen_salt('bf', 10)), true),
           ('pepe', crypt('pepe', gen_salt('bf', 10)), false);
