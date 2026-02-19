# API Reference - Pet API

## Autenticação

Todos os endpoints de pets requerem autenticação via JWT. Inclua o token no header:
```
Authorization: Bearer <seu_token>
```

## Endpoints

---

## Autenticação

### Registrar Usuário

Registra um novo usuário no sistema.

**Endpoint:** `POST /api/auth/register`

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
  "name": "João Silva",
  "email": "joao@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Parâmetros:**

| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| name | string | Sim | Nome do usuário (máx: 255) |
| email | string | Sim | Email válido único |
| password | string | Sim | Mínimo 8 caracteres |
| password_confirmation | string | Sim | Confirmação da senha |

**Resposta (201):**
```json
{
  "success": true,
  "message": "Usuário registrado com sucesso.",
  "data": {
    "user": {
      "id": 1,
      "name": "João Silva",
      "email": "joao@example.com",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

---

### Login

Autentica o usuário e retorna um token JWT.

**Endpoint:** `POST /api/auth/login`

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
  "email": "joao@example.com",
  "password": "password123"
}
```

**Parâmetros:**

| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| email | string | Sim | Email do usuário |
| password | string | Sim | Senha do usuário |

**Resposta (200):**
```json
{
  "success": true,
  "message": "Login realizado com sucesso.",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

**Erro (422):**
```json
{
  "success": false,
  "message": "As credenciais fornecidas estão incorretas.",
  "errors": {
    "email": ["As credenciais fornecidas estão incorretas."]
  }
}
```

---

### Logout

Invalida o token JWT atual.

**Endpoint:** `POST /api/auth/logout`

**Headers:**
```
Authorization: Bearer <token>
```

**Resposta (200):**
```json
{
  "success": true,
  "message": "Logout realizado com sucesso."
}
```

---

### Refresh Token

Renova o token JWT.

**Endpoint:** `POST /api/auth/refresh`

**Headers:**
```
Authorization: Bearer <token>
```

**Resposta (200):**
```json
{
  "success": true,
  "message": "Token atualizado com sucesso.",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

---

### Dados do Usuário Atual

Retorna os dados do usuário autenticado.

**Endpoint:** `GET /api/auth/me`

**Headers:**
```
Authorization: Bearer <token>
```

**Resposta (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@example.com",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

---

## Pets

### Listar Pets

Retorna lista paginada de pets do usuário autenticado.

**Endpoint:** `GET /api/pets`

**Headers:**
```
Authorization: Bearer <token>
```

**Query Parameters:**

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|--------|-----------|
| page | int | 1 | Número da página |
| per_page | int | 15 | Itens por página |

**Resposta (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Buddy",
      "species": "Cachorro",
      "breed": "Golden Retriever",
      "gender": "Macho",
      "birth_date": "2022-05-10",
      "age": "2 anos",
      "weight": "30.50 kg",
      "microchip_number": null,
      "notes": null,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 1,
    "from": 1,
    "to": 1
  }
}
```

---

### Criar Pet

Cria um novo pet.

**Endpoint:** `POST /api/pets`

**Headers:**
```
Authorization: Bearer <token>
Content-Type: application/json
```

**Body:**
```json
{
  "name": "Buddy",
  "species": "Cachorro",
  "breed": "Golden Retriever",
  "gender": "Macho",
  "birth_date": "2022-05-10",
  "weight": 30.5,
  "microchip_number": "123456789012345",
  "notes": "Pet muito dócil"
}
```

**Parâmetros:**

| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| name | string | Sim | Nome do pet (máx: 255) |
| species | string | Sim | Espécie (Cachorro, Gato, Pássaro, Coelho, etc.) |
| breed | string | Não | Raça (máx: 100) |
| gender | string | Não | Macho, Fêmea ou Desconhecido |
| birth_date | date | Não | Data de nascimento (não pode ser futura) |
| weight | decimal | Não | Peso em kg (máx: 999.99) |
| microchip_number | string | Não | Número do microchip (único, máx: 50) |
| notes | text | Não | Observações |

**Resposta (201):**
```json
{
  "success": true,
  "message": "Pet criado com sucesso.",
  "data": {
    "id": 1,
    "name": "Buddy",
    "species": "Cachorro",
    "breed": "Golden Retriever",
    "gender": "Macho",
    "birth_date": "2022-05-10",
    "age": "2 anos",
    "weight": "30.50 kg",
    "microchip_number": "123456789012345",
    "notes": "Pet muito dócil",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

**Erro de Validação (422):**
```json
{
  "success": false,
  "message": "Erro de validação.",
  "errors": {
    "name": ["O campo nome é obrigatório."],
    "species": ["O campo espécie é obrigatório."]
  }
}
```

---

### Ver Pet Específico

Retorna os dados de um pet específico.

**Endpoint:** `GET /api/pets/{id}`

**Headers:**
```
Authorization: Bearer <token>
```

**Resposta (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Buddy",
    "species": "Cachorro",
    "breed": "Golden Retriever",
    "gender": "Macho",
    "birth_date": "2022-05-10",
    "age": "2 anos",
    "weight": "30.50 kg",
    "microchip_number": null,
    "notes": null,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

**Erro (404):** Pet não encontrado ou pertence a outro usuário.

---

### Atualizar Pet

Atualiza os dados de um pet existente.

**Endpoint:** `PUT /api/pets/{id}`

**Headers:**
```
Authorization: Bearer <token>
Content-Type: application/json
```

**Body (exemplo - campos opcionais):**
```json
{
  "name": "Max",
  "weight": 32.0
}
```

**Parâmetros:** mesmos de criação, mas todos opcionais.

**Resposta (200):**
```json
{
  "success": true,
  "message": "Pet atualizado com sucesso.",
  "data": {
    "id": 1,
    "name": "Max",
    "species": "Cachorro",
    ...
  }
}
```

---

### Excluir Pet

Exclui um pet.

**Endpoint:** `DELETE /api/pets/{id}`

**Headers:**
```
Authorization: Bearer <token>
```

**Resposta (200):**
```json
{
  "success": true,
  "message": "Pet excluído com sucesso."
}
```

---

### Buscar Pets

Busca pets por termo (nome, espécie, raça ou microchip).

**Endpoint:** `GET /api/pets/search`

**Headers:**
```
Authorization: Bearer <token>
```

**Query Parameters:**

| Parâmetro | Tipo | Descrição |
|-----------|------|-----------|
| q | string | Termo de busca |
| per_page | int | Itens por página |

**Exemplo:**
```
GET /api/pets/search?q=Buddy&per_page=10
```

**Resposta (200):**
```json
{
  "success": true,
  "data": [...],
  "meta": {...}
}
```

---

## Códigos de Status HTTP

| Código | Descrição |
|--------|-----------|
| 200 | Sucesso |
| 201 | Criado com sucesso |
| 401 | Não autenticado (token inválido ou expirado) |
| 403 | Acesso negado |
| 404 | Recurso não encontrado |
| 422 | Erro de validação |
| 500 | Erro interno do servidor |

---

## Erros Comuns

### Token não fornecido
```json
{
  "message": "Unauthenticated."
}
```

### Token expirado
```json
{
  "message": "Token has expired"
}
```

### Validação falhou
```json
{
  "success": false,
  "message": "Erro de validação.",
  "errors": {
    "field": ["mensagem de erro"]
  }
}
```
