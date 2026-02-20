# Pet API - Gerenciamento de Pets

API RESTful Laravel para gerenciamento de registros de pets com autenticação JWT.

## Recursos

- ✅ Autenticação JWT (Registro, Login, Logout, Refresh Token)
- ✅ CRUD completo de Pets
- ✅ Busca de pets por nome, espécie, raça ou microchip
- ✅ Paginação
- ✅ Validação de dados
- ✅ Testes automatizados
- ✅ Containerização Docker com setup automático

## Requisitos

- Docker
- Docker Compose v2

## Instalação

### Usando Docker (Recomendado)

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/pet-api.git
cd pet-api
```

2. Configure o arquivo `.env` (já está configurado para Docker):
```bash
# O arquivo .env já contém as configurações padrão para Docker
# MySQL: pet_api / pet_user / pet_password
```

3. Inicie os containers Docker:
```bash
docker compose up -d --build
```

4. Aguarde a inicialização automática:
   - O container `app` executa automaticamente:
     - `composer install`
     - `php artisan key:generate`
     - `php artisan jwt:secret`
     - `php artisan migrate`

5. Verifique os logs:
```bash
docker compose logs -f app
```

### Acessando a aplicação

A API estará disponível em: **http://localhost:8080**

### Comandos Docker úteis

```bash
# Iniciar containers
docker compose up -d

# Parar containers
docker compose down

# Ver logs
docker compose logs -f

# Acessar container da aplicação
docker compose exec app bash

# Executar comandos artisan
docker compose exec app php artisan <comando>

# Executar testes
docker compose exec app php artisan test
```

### Sem Docker

1. Configure o arquivo `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pet_api
DB_USERNAME=root
DB_PASSWORD=your_password

JWT_SECRET=your-jwt-secret
```

2. Instale as dependências e configure:
```bash
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan serve
```

## Endpoints da API

[API Reference](api-reference.md) 

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
| `JWT_SECRET` | Chave JWT | (gerado automaticamente) |
| `JWT_TTL` | Tempo de vida do token (min) | 60 |

## Executando Testes

```bash
# Com Docker
docker compose exec app php artisan test

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
│   ├── entrypoint.sh       # Script de inicialização
│   ├── nginx.conf          # Configuração Nginx
│   ├── php.ini             # Configuração PHP
│   └── supervisor.conf     # Configuração Supervisor
├── routes/                  # Rotas
├── tests/                   # Testes
│   ├── Feature/            # Testes de feature
│   └── Unit/              # Testes unitários
├── docker-compose.yml
├── Dockerfile
├── .env
├── README.md
└── api-reference.md
```

## Configuração Docker

### Services

- **app**: PHP-FPM 8.3 com Laravel
- **webserver**: Nginx Alpine
- **db**: MySQL 8.0

### Portas

- **8080**: API Nginx
- **3306**: MySQL

### Volumes

- **pet-api-db**: Dados persistentes do MySQL

## License

MIT License.
