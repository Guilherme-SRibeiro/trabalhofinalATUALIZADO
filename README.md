# 📰 Portal Tech

Portal Tech é um sistema de notícias desenvolvido em **PHP**, utilizando **Programação Orientada a Objetos (POO)** e **MySQL**. O projeto permite que usuários realizem cadastro, login e interajam com as notícias por meio de comentários e curtidas, enquanto administradores podem gerenciar as publicações.

---

## 📋 Funcionalidades

### Usuários
- Cadastro de usuários
- Login e Logout
- Senhas criptografadas com `password_hash()`
- Sessão de autenticação

### Notícias
- Visualização de notícias
- Cadastro de notícias
- Edição de notícias
- Exclusão de notícias
- Upload de imagem para cada notícia

### Interações
- Curtir notícias
- Comentar notícias
- Ranking das notícias mais curtidas

### Dashboard
- Área administrativa
- Cadastro de novas notícias
- Gerenciamento das publicações

---

## 🛠 Tecnologias Utilizadas

- PHP
- HTML
- CSS
- MySQL
- Programação Orientada a Objetos (POO)

---

## 📁 Estrutura do Projeto

```
PortalTech/
│
├── assets/
│   └── styles/
│       └── style.css
│
├── classes/
│   ├── Comentarios.php
│   ├── Database.php
│   ├── Likes.php
│   ├── Noticia.php
│   └── Usuario.php
│
├── config/
│   ├── conexao.php
│   ├── funcoes.php
│   └── verifica_login.php
│
├── dashboard/
│   ├── dashboard.php
│   ├── nova_noticia.php
│   ├── editar_noticiar.php
│   └── excluir_noticia.php
│
├── database/
│   └── PortalTech.sql
│
├── cadastro.php
├── index.php
├── login.php
├── logout.php
├── noticia.php
└── README.md
```

---

## ⚙️ Como executar o projeto

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/PortalTech.git
```

### 2. Coloque o projeto no servidor local

Exemplos:

- XAMPP → `htdocs`
- WAMP → `www`
- Laragon → `www`

### 3. Crie o banco de dados

No phpMyAdmin:

- Crie um banco chamado:

```
PortalTech
```

- Importe o arquivo:

```
database/PortalTech.sql
```

### 4. Configure a conexão

Edite o arquivo:

```
config/conexao.php
```

Caso seja necessário alterar:

```php
$host = "localhost";
$dbname = "PortalTech";
$user = "root";
$password = "";
```

### 5. Execute

Abra o navegador:

```
http://localhost/PortalTech
```

---

## 🔒 Segurança

O projeto utiliza:

- Password Hash (`password_hash`)
- Password Verify (`password_verify`)
- Sessões para autenticação
- Organização em classes
- Conexão utilizando PDO

---

## 📚 Conceitos Aplicados

- Programação Orientada a Objetos
- Encapsulamento
- Classes e Objetos
- CRUD
- Sessões em PHP
- Upload de arquivos
- Relacionamento entre tabelas
- Consultas SQL
- Validação de formulários

---

## 👨‍💻 Autor

**Henrique Michel Rodrigues e Guilherme Silveira Ribeiro**

Projeto desenvolvido como trabalho da disciplina de Desenvolvimento Web utilizando PHP e MySQL.

---

## 📄 Licença

Este projeto possui finalidade exclusivamente acadêmica.
