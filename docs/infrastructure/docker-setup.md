# Infraestrutura & Docker

Guia técnico dos containers, redes, volumes e comandos operacionais do projeto.

---

## 1. Visão Geral da Rede e Serviços

Todos os serviços operam conectados a uma rede interna do tipo `bridge` denominada `blog-network`.

```text
[Host Browser] 
      │ (Porta 80 HTTP)
      ▼
[Container: Nginx] (nginx:alpine)
      │ (FastCGI :9000 - em breve)
      ├──► [Container: PHP-FPM]
      │           │
      │           ├──► [Container: PostgreSQL :5432]
      │           └──► [Container: Redis :6379]
```

---

## 2. Estrutura de Diretórios da Infraestrutura

Para evitar acoplamento entre arquivos de infraestrutura e código da aplicação, adotamos a separação:

```text
my-azevedo/
├── docker/                 # Arquivos dedicados de infraestrutura
│   ├── nginx/
│   │   └── default.conf    # VirtualHost configurado no /etc/nginx/conf.d/
│   └── php/
│       └── Dockerfile      # Imagem customizada PHP 8.3-FPM + extensões (pdo_pgsql, gd, bcmath, zip)
├── docs/                   # Documentação do projeto
├── src/                    # Código da aplicação (montado em /var/www)
│   ├── index.html          # Arquivo inicial de validação estática
│   └── index.php           # Arquivo inicial de validação do PHP-FPM (phpinfo)
├── docker-compose.yml      # Definição e orquestração dos serviços locais
└── CONTEXT.md              # Diretrizes de mentoria e objetivos de aprendizagem
```

---

## 3. Serviços Configurados

### 3.1 Nginx Web Server (`nginx-my-azevedo`)
- **Imagem Base:** `nginx:alpine`
- **Portas:** `80:80`
- **Volumes:**
  - `./docker/nginx/default.conf` ➔ `/etc/nginx/conf.d/default.conf`
  - `./src` ➔ `/var/www`
- **Comunicação FastCGI:** Passa requisições `\.php$` para o serviço `app:9000`.
- **Rede:** `blog-network`.

### 3.2 PHP 8.3-FPM (`php-my-azevedo` / serviço `app`)
- **Build:** `./docker/php/Dockerfile` (Base: `php:8.3-fpm-alpine`).
- **Extensões do PHP:** `pdo`, `pdo_pgsql`, `pgsql`, `bcmath`, `gd`, `zip`, `redis` (compilada via PECL).
- **Ferramentas:** Composer embutido via multi-stage build e pacotes de compilação `$PHPIZE_DEPS`.
- **Injeção de Ambiente:** Injeta variáveis do `.env` local (`env_file: - .env`).
- **Volumes:** `./src` ➔ `/var/www`.
- **Rede:** `blog-network`.

### 3.3 PostgreSQL 16 (`db-my-azevedo` / serviço `db`)
- **Imagem Base:** `postgres:16-alpine`
- **Portas:** `5432:5432` (exposto para clientes como DBeaver / TablePlus).
- **Variáveis de Ambiente:** Lidas do `.env` (`POSTGRES_DB`, `POSTGRES_USER`, `POSTGRES_PASSWORD`).
- **Persistência de Dados:** Volume nomeado `postgres_data` mapeado em `/var/lib/postgresql/data` (Driver `local`).
- **Rede:** `blog-network`.

### 3.4 Redis 7 (`redis-my-azevedo` / serviço `redis`)
- **Imagem Base:** `redis:7-alpine`
- **Portas:** `6379:6379`
- **Uso no Projeto:** Cache em memória RAM para postagens e gerenciamento de sessões/filas.
- **Rede:** `blog-network`.

---

## 4. Guia de Operação e Troubleshooting

### Comandos Comuns
```bash
# Subir serviços em background
docker compose up -d

# Visualizar status dos containers
docker compose ps

# Visualizar logs em tempo real
docker compose logs -f [nome_do_app]

# Derrubar containers e apagar volumes (reset total do banco)
docker compose down -v
```

### Problemas Conhecidos e Soluções
* **Conflito na porta 80 com Apache2 local (WSL2/Linux):**
  * *Sintoma:* `http://localhost` retorna a página padrão do Apache2 do host em vez do container.
  * *Causa:* O Apache2 nativo no host estava rodando e capturava as requisições antes do Docker.
  * *Solução:* Parar e desabilitar o Apache nativo (`sudo service apache2 stop`).
* **Erro de Autenticação/Senha no PostgreSQL (`Password authentication failed`):**
  * *Sintoma:* O container do Postgres falha na autenticação mesmo com a senha correta no `.env`.
  * *Causa:* O script de entrypoint do PostgreSQL no Docker só inicializa credenciais na primeira execução com volume 100% vazio. Se um volume antigo existir, o Postgres ignora as novas variáveis do `.env`.
  * *Solução:* Resetar o volume nomeado rodando `docker compose down -v` e subir novamente com `docker compose up -d`.
* **Erro de Permissão no Laravel (`tempnam(): file created in system's temporary directory`):**
  * *Sintoma:* O Symfony/Laravel lança uma exceção de permissão negada ao tentar criar arquivos temporários de cache ou log em `storage/` ou `bootstrap/cache/`.
  * *Causa:* O processo do PHP-FPM dentro do container roda sob o usuário do sistema web (`www-data` ou `nobody`) e não tem privilégios de escrita nos diretórios criados no host.
  * *Solução Local (Dev):* `chmod -R 777 src/storage src/bootstrap/cache`
  * *Diferença para Produção (Segurança OWASP):* Em ambiente local de dev com bind mounts, o `chmod 777` libera leitura/escrita para qualquer usuário resolver rápido. **Em Produção**, o `chmod 777` é uma falha de segurança grave! Deve-se usar o `chown -R www-data:www-data storage bootstrap/cache` (alterando o dono do arquivo) combinado com permissões restritas como `775` ou `755`, garantindo que apenas o usuário do webserver/PHP consiga alterar esses diretórios.

