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

