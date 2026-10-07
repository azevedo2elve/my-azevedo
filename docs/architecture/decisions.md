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

