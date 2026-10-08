# Registro de Decisões de Arquitetura (ADRs)

Este documento registra as principais decisões de design técnico, seus contextos e os trade-offs envolvidos.

---

## ADR 001: Separação de Infraestrutura e Código da Aplicação

* **Status:** Aprovado / Implementado
* **Data:** 2026-10-06

### Contexto
Precisamos estruturar o repositório antes de instalar o Laravel para garantir que os arquivos de infraestrutura (Dockerfile, configurações do Nginx, scripts de inicialização) não fiquem misturados na raiz com os arquivos da aplicação.

### Decisão
Criamos a pasta `docker/` na raiz para guardar subpastas de serviços (`docker/nginx`, `docker/php`) e a pasta `src/` para abrigar a aplicação web montada no container em `/var/www`.

### Consequências e Trade-offs
* **Vantagens:** Raiz limpa; fácil manutenção; separação clara de responsabilidades (DevOps vs Aplicação).
* **Desvantagens:** Requer atenção no mapeamento de caminhos relativos no `docker-compose.yml`.

---

## ADR 002: Nginx como Servidor Web e Proxy Reverso com Alpine Linux

* **Status:** Aprovado / Implementado
* **Data:** 2026-10-06

### Contexto
Necessidade de expor o projeto na porta 80, gerenciar arquivos estáticos com alta performance e repassar scripts dinâmicos para o PHP-FPM via FastCGI.

### Decisão
Utilizar a imagem oficial `nginx:alpine` e injetar a configuração do site através de um bind mount em `/etc/nginx/conf.d/default.conf`.

### Consequências e Trade-offs
* **Vantagens:** Consumo mínimo de memória e CPU (~5-10MB RAM); suporte nativo a FastCGI de alto rendimento.
* **Desvantagens:** O Nginx não processa scripts PHP sozinho, exigindo um container separado para o PHP-FPM.

---

## ADR 003: Imagem Customizada PHP-FPM (Alpine) com Multi-Stage Composer

* **Status:** Aprovado / Implementado
* **Data:** 2026-10-07

### Contexto
O Laravel exige extensões PHP específicas que não acompanham a imagem oficial padrão (`php:alpine`), como `pdo_pgsql` para suporte ao PostgreSQL e `gd` para manipulação de mídias. Além disso, precisamos do Composer disponível para instalação de dependências.

### Decisão
Criar um `Dockerfile` customizado em `docker/php/Dockerfile` baseado em `php:8.3-fpm-alpine`, instalando as extensões via `docker-php-ext-install` e copiando o binário do Composer via Multi-stage build (`COPY --from=composer:latest`).

### Consequências e Trade-offs
* **Vantagens:** Ambiente de desenvolvimento 100% idêntico entre colaboradores; extensões necessárias pré-compiladas; presença do Composer sem poluir a máquina host.
* **Desvantagens:** O primeiro build (`docker compose up --build`) demora um pouco mais devido à compilação das extensões do PHP no Alpine.

---

## ADR 004: PostgreSQL 16 com Volume Nomeado para Persistência e Segurança de Credenciais via `.env`

* **Status:** Aprovado / Implementado
* **Data:** 2026-10-08

### Contexto
Precisamos de um banco de dados relacional robusto para o blog/portfólio. Os dados não podem ser perdidos quando os containers forem encerrados (`docker compose down`) e as credenciais de acesso não devem ser expostas diretamente no controle de versão (`docker-compose.yml`).

### Decisão
1. Adotar a imagem oficial `postgres:16-alpine` fixando a versão estável.
2. Utilizar um volume nomeado (`postgres_data`) com driver `local` mapeado em `/var/lib/postgresql/data` para máxima performance de I/O no WSL2 e isolamento contra corrupção de arquivos.
3. Injetar variáveis de ambiente a partir do arquivo local `.env` (`POSTGRES_DB`, `POSTGRES_USER`, `POSTGRES_PASSWORD`).

### Consequências e Trade-offs
* **Vantagens:** Alta performance no WSL2/Linux; persistência de dados garantida entre reinicializações do Docker; conformidade com boas práticas de segurança OWASP (secrets fora do Git).
* **Desvantagens:** Alterações posteriores nas credenciais do `.env` exigem o reset manual do volume nomeado (`docker compose down -v`), pois o script de entrada do Postgres só processa credenciais em volumes novos/vazios.

---

## ADR 005: Redis 7 para Cache In-Memory e Instalação de Extensão via PECL com Otimização de Camadas Docker

* **Status:** Aprovado / Implementado
* **Data:** 2026-10-08

### Contexto
Para acelerar o tempo de resposta do blog e gerenciar sessões/queues sem onerar o banco PostgreSQL, precisamos de uma solução in-memory. Além disso, a instalação de extensões de terceiros via PECL no Alpine exige ferramentas de compilação C (`$PHPIZE_DEPS`).

### Decisão
1. Adicionar o container `redis:7-alpine` na porta `6379`.
2. Instalar a extensão nativa do `redis` no PHP via `pecl install redis && docker-php-ext-enable redis`.
3. Unificar a instalação de pacotes `$PHPIZE_DEPS`, extensões nativas e PECL em uma única instrução `RUN` encadeada com `&&` no `Dockerfile`.

### Consequências e Trade-offs
* **Vantagens:** Otimização drasticamente do tamanho da imagem Docker gerando menos camadas (*layers*); leitura de dados em microsegundos via memória RAM; suporte nativo no Laravel.
* **Desvantagens:** Como não mapeamos volume no Redis nesta etapa, dados de cache na memória somem se o container for destruído (comportamento desejável para cache em dev).

