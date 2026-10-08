# Documentação do Projeto: My Azevedo

Bem-vindo à documentação oficial de arquitetura, infraestrutura e boas práticas do projeto **My Azevedo** (Blog & Portfólio).

---

## 🗂️ Estrutura da Documentação

A pasta `docs/` está organizada por domínios de conhecimento para facilitar a navegação, onboarding e consulta de decisões técnicas tomadas ao longo do desenvolvimento:

- 🏗️ **[Arquitetura](architecture/decisions.md):** Architectural Decision Records (ADRs), decisões técnicas e seus trade-offs.
- 🐳 **[Infraestrutura & Docker](infrastructure/docker-setup.md):** Configurações de containers, redes, volumes e guia de troubleshooting de ambiente.
- 📐 **[Padrões de Código & Boas Práticas](standards/guidelines.md):** Diretrizes de escrita de código, convenções de commits, regras de linting e segurança.

---

## 🚀 Status Atual da Sprint

- **Sprint 1: Infraestrutura Docker do Zero**
  - [x] Etapa 1: Setup do Nginx, rede bridge customizada, isolamento de diretórios e validação de bind mounts estáticos.
  - [x] Etapa 2: Dockerfile do PHP-FPM, extensões necessárias e comunicação FastCGI via porta 9000.
  - [x] Etapa 3: Banco de dados PostgreSQL com persistência de volumes e integração via PDO.
  - [x] Etapa 4: Cache Redis, extensão via PECL e validação da malha de rede entre containers.
  - [x] Etapa 5: Inicialização e instalação limpa do Laravel 11/12/13 dentro dos containers com permissões e rotas Nginx.
  
🎉 **SPRINT 1 CONCLUÍDA COM SUCESSO!** Toda a infraestrutura Docker (Nginx, PHP 8.3-FPM, PostgreSQL 16, Redis 7 e Laravel) está 100% operacional.

---

- **Sprint 2: Modelagem do Banco de Dados PostgreSQL & Arquitetura de Migrations**
  - [x] Etapa 1: Validação do ambiente e teste de execução de `php artisan migrate` no container `app`.
    * 🌿 *Branch:* `feature/database-setup-validation`
  - [x] Etapa 2: Desenho do Modelo Entidade-Relacionamento (DER) para as entidades do Blog (`users`, `posts`, `categories`, `tags`).
    * 🌿 *Branch:* `feature/blog-der-modeling`
  - [x] Etapa 3: Criação das Migrations no Laravel aplicando chaves estrangeiras, índices e restrições de integridade no PostgreSQL.
    * 🌿 *Branch:* `feature/blog-schema-migrations`
  - [ ] Etapa 4: Configuração de Seeders e Factories para população de dados de testes reais.
    * 🌿 *Branch sugerida:* `feature/blog-factories-seeders`
  - [ ] Etapa 5: Validação da camada de Cache com Redis integrando com a leitura de postagens via Eloquent ORM.
    * 🌿 *Branch sugerida:* `feature/redis-eloquent-cache`

