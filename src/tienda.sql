CREATE EXTENSION unaccent;  -- Ignorar acentos. 

DROP TABLE IF EXISTS articulos CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;
DROP TABLE IF EXISTS facturas CASCADE;
DROP TABLE IF EXISTS articulos_facturas CASCADE;
DROP TABLE IF EXISTS cupones CASCADE;
DROP TABLE IF EXISTS comentarios CASCADE;
DROP TABLE IF EXISTS ofertas CASCADE;

-- Ofertas especiales.
CREATE TABLE ofertas (
    id      bigserial   PRIMARY KEY,
    oferta  varchar(50) NOT NULL
);

CREATE TABLE articulos (
    id          bigserial     PRIMARY KEY,
    codigo      varchar(13)   NOT NULL UNIQUE,
    descripcion varchar(255)  NOT NULL,
    precio      numeric(7, 2) NOT NULL,
    stock       int           NOT NULL,
    descuento   numeric(3)    DEFAULT 0   CHECK (descuento >= 0 AND descuento <= 100),
    oferta_id   bigint  REFERENCES ofertas (id) ON DELETE CASCADE
);

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

--Cupones de descuento.
CREATE TABLE cupones (
    id              bigserial       PRIMARY KEY,
    cupon           varchar(50)     NOT NULL,
    descuento       numeric(7, 2)   NOT NULL,
    fecha_caducidad timestamp       NOT NULL
    
);

CREATE TABLE facturas (
    id         bigserial  PRIMARY KEY,
    created_at timestamp  NOT NULL DEFAULT localtimestamp(0),
    usuario_id bigint NOT NULL REFERENCES usuarios (id) ON DELETE CASCADE,
    cupon_id   bigint REFERENCES cupones (id) ON DELETE CASCADE
);

CREATE TABLE articulos_facturas (
    articulo_id bigint NOT NULL REFERENCES articulos (id) ON DELETE CASCADE,
    factura_id  bigint NOT NULL REFERENCES facturas (id) ON DELETE CASCADE,
    cantidad    int    NOT NULL,
    PRIMARY KEY (articulo_id, factura_id)
);

--Comentarios en los artículos.
CREATE TABLE comentarios (
    created_at  timestamp   NOT NULL DEFAULT localtimestamp(0),
    articulo_id bigint      NOT NULL REFERENCES  articulos   (id) ON DELETE CASCADE,
    usuario_id  bigint      NOT NULL REFERENCES  usuarios    (id) ON DELETE CASCADE,
    comentario  varchar(255),
    PRIMARY KEY (created_at, articulo_id, usuario_id)
);

-- Carga inicial de datos de prueba:
INSERT INTO ofertas (oferta)
    VALUES  ('2x1'),
            ('50%'),
            ('2ª Unidad a mitad de precio');

INSERT INTO articulos (codigo, descripcion, precio, stock, descuento, oferta_id)
    VALUES ('18273892389', 'Yogur piña', 200.50, 40, 50, null),
           ('83745828273', 'Tigretón', 50.10, 2, 0, 2),
           ('51736128495', 'Disco duro SSD 500 GB', 150.30, 3, 0, 3),
           ('83746828273', 'Bollicao', 80.10, 3, 0, 3),
           ('51786128435', 'Bolígrafo', 10.30, 0, 0, null),
           ('83745228673', 'Ordenador', 550.10, 80, 40, null),
           ('51786198495', 'Alfombrilla', 5.30, 3, 0, 1);

INSERT INTO usuarios (usuario, password, validado)
    VALUES ('admin', crypt('admin', gen_salt('bf', 10)), true),
           ('pepe', crypt('pepe', gen_salt('bf', 10)), false);

INSERT INTO cupones (cupon, descuento, fecha_caducidad)
    VALUES ('DESCUENTO25', 25,  '2024-07-01'),
           ('DESCUENTO50', 50,  '2023-07-01'),
           ('DESCUENTO75', 75,  '2022-07-01');

