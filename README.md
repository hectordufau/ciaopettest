# Pet API - Gerenciamento de Pets

API RESTful Laravel para gerenciamento de registros de pets com autenticação JWT.

## Recursos

- ✅ Autenticação JWT (Registro, Login, Logout, Refresh Token)
- ✅ CRUD completo de Pets
- ✅ Busca de pets por nome, espécie, raça ou microchip
- ✅ Paginação
- ✅ Validação de dados
- ✅ Testes automatizados
- ✅ Containerização Docker

## Requisitos

- Docker
- Docker Compose
- PHP 8.3+ (para desenvolvimento local sem Docker)

## Instalação

### Usando Docker (Recomendado)

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/pet-api.git
cd pet-api
```

2. Configure as variáveis de ambiente:
```bash
cp .env.example .env
```

3. Inicie os containers Docker:
```bash
docker-compose up -d --build
```

4. Acesse o container da aplicação:
```bash
docker-compose exec app bash
```

5. Instale as dependências e gere a chave:
```bash
composer install
php artisan key:generate
php artisan jwt:secret
```

6. Execute as migrations:
```bash
php artisan migrate
```

7. Execute os testes:
```bash
php artisan test
```

### Sem Docker

1. Instale as dependências:
```bash
composer install
```

2. Configure o arquivo `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pet_api
DB_USERNAME=root
DB_PASSWORD=your_password

JWT_SECRET=your-jwt-secret
```

3. Execute:
```bash
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan serve
```

## Endpoints da API

### Autenticação

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| POST | `/api/auth/register` | Registrar novo usuário |
| POST | `/api/auth/login` | Login |
| POST | `/api/auth/logout` | Logout (requer auth) |
| POST | `/api/auth/refresh` | Refresh token (requer auth) |
| GET | `/api/auth/me` | Dados do usuário atual (requer auth) |

### Pets

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/api/pets` | Listar pets (paginado) |
| POST | `/api/pets` | Criar pet |
| GET | `/api/pets/{id}` | Ver pet específico |
| PUT | `/api/pets/{id}` | Atualizar pet |
| DELETE | `/api/pets/{id}` | Excluir pet |
| GET | `/api/pets/search?q=termo` | Buscar pets |

## Exemplos de Uso

### Registro de Usuário
```bash
curl -X POST http://localhost:8080/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva",
    "email": "joao@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login
```bash
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "joao@example.com",
    "password": "password123"
  }'
```

### Criar Pet
```bash
curl -X POST http://localhost:8080/api/pets \
  -H "Authorization: Bearer SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Buddy",
    "species": "Cachorro",
    "breed": "Golden Retriever",
    "gender": "Macho",
    "birth_date": "2022-05-10",
    "weight": 30.5
  }'
```

## Variáveis de Ambiente

| Variável | Descrição | Padrão |
|----------|------------|--------|
| `APP_NAME` | Nome da aplicação | PetAPI |
| `APP_ENV` | Ambiente | local |
| `APP_DEBUG` | Modo debug | true |
| `APP_URL` | URL da aplicação | http://localhost:8080 |
| `DB_CONNECTION` | Driver do banco | mysql |
| `DB_HOST` | Host do banco | db |
| `DB_DATABASE` | Nome do banco | pet_api |
| `DB_USERNAME` | Usuário do banco | pet_user |
| `DB_PASSWORD` | Senha do banco | pet_password |
| `JWT_SECRET` | Chave JWT | - |
| `JWT_TTL` | Tempo de vida do token (min) | 60 |

## Executando Testes

```bash
# Todos os testes
php artisan test

# Testes específicos
php artisan test --filter=AuthTest
php artisan test --filter=PetTest

# Com cobertura
php artisan test --coverage
```

## Estrutura do Projeto

```
pet-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/  # Controllers da API
│   │   └── Resources/        # API Resources
│   ├── Models/               # Models Eloquent
│   └── Services/             # Camada de serviços
├── config/                   # Configurações
├── database/
│   ├── factories/           # Factories
│   └── migrations/          # Migrations
├── docker/                  # Arquivos Docker
├── routes/                  # Rotas
├── tests/                   # Testes
│   ├── Feature/            # Testes de feature
│   └── Unit/              # Testes unitários
├── docker-compose.yml
├── Dockerfile
└── README.md
```

## License

MIT License.
