CREATE DATABASE portalTech;

USE portalTech;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    sexo CHAR(1) NOT NULL,
    fone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    noticia TEXT NOT NULL,
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    autor INT NOT NULL,
    imagem VARCHAR(255) DEFAULT NULL,

    CONSTRAINT fk_noticias_usuario
        FOREIGN KEY (autor)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

ALTER TABLE usuarios DROP COLUMN fone;
ALTER TABLE usuarios ADD COLUMN perfil VARCHAR(255);
ALTER TABLE usuarios DROP COLUMN sexo;
ALTER TABLE usuarios ADD COLUMN sexo ENUM('masculino','feminino','outro','prefiro_nao_dizer') DEFAULT NULL;

CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    noticia_id INT NOT NULL,
    usuario_id INT NOT NULL,
    comentario TEXT NOT NULL,
    data_comentario DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_comentario_noticia
        FOREIGN KEY (noticia_id)
        REFERENCES noticias(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_comentario_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE likes_noticia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    noticia_id INT NOT NULL,
    usuario_id INT NOT NULL,
    data_like DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_like_noticia
        FOREIGN KEY (noticia_id)
        REFERENCES noticias(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_like_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    -- impede que um usuário curta a mesma notícia mais de uma vez
    CONSTRAINT uk_like UNIQUE (noticia_id, usuario_id)
);
