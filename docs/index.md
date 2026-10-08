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
  - [ ] Etapa 5: Inicialização e instalação limpa do Laravel dentro dos containers.

