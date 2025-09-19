## Requisitos

- Docker
- Docker Compose

## Passo a passo

1. Suba os containers (o primeiro build instala as dependências do PHP e do
   front-end, gera o `.env` e cria a chave da aplicação automaticamente):

   ```bash
   docker compose up --build
   ```

   Isso iniciará:

   - **app**: servidor Laravel disponível em <http://localhost:8000>
   - **frontend**: servidor Vite (hot reload) em <http://localhost:5173>
   - **mysql**: banco de dados MySQL exposto em `localhost:3307`

2. Após os containers estarem rodando, execute as migrations (e quaisquer
   outros comandos Artisan que precisar) de dentro do container do Laravel:

   ```bash
   docker compose exec app php artisan migrate
   ```

3. Problema encontrado:
o vite nao esta gerando no dockerfile, precisa configurar para dar npm install e npm run build

Para desligar:
   ```bash
   docker compose down
   ```

### Dicas adicionais

- As credenciais do banco de dados padrão são:
  - host: `mysql`
  - porta interna: `3306` (use `3307` para conectar a partir da máquina host)
  - banco: `laravel`
  - usuário: `laravel`
  - senha: `secret`
- Se atualizar as dependências do NPM e precisar reinstalá-las, remova o arquivo
  `node_modules/.npm-installed` dentro do container `frontend` ou recrie o
  volume com `docker compose down -v` antes de subir novamente.
