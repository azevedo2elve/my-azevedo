# 🚀 My Azevedo - Blog & Portfólio (Backend & Infraestrutura)

Bem-vindo ao repositório do projeto **My Azevedo**! 

Este projeto é um **laboratório de estudos de fundamentos de backend, arquitetura de software e infraestrutura**, desenvolvido com foco em entender o funcionamento "por baixo dos panos" de cada tecnologia utilizada.

O desenvolvimento é guiado por mentoria técnica e adota rigor sênior para boas práticas, isolamento de ambiente, segurança e padrões de documentação (*Docs as Code*).

---

## 🛠️ Tech Stack & Arquitetura

- **Backend:** PHP 8.3 com Laravel
- **Frontend Reativo:** Livewire (sem acoplamento ou separação de SPA)
- **Banco de Dados Relacional:** PostgreSQL 16
- **Cache & In-Memory:** Redis
- **Web Server / Proxy Reverso:** Nginx (Alpine Linux)
- **Infraestrutura / Containerização:** Docker & Docker Compose (construído 100% do zero)

---

## 🏗️ Arquitetura da Infraestrutura (Docker)

Todo o ambiente de desenvolvimento roda isolado em containers Docker conectados por uma rede `bridge` customizada (`blog-network`), sem poluir o sistema operacional da máquina host.

```text
[Host Browser] 
      │ (Porta 80 HTTP)
      ▼
[Container: Nginx] (nginx:alpine)
      │ (FastCGI :9000)
      ├──► [Container: PHP 8.3-FPM]
      │           │
      │           ├──► [Container: PostgreSQL 16 :5432]
      │           └──► [Container: Redis :6379]
```

---

## 📚 Documentação Técnica (`/docs`)

A documentação do repositório foi construída de forma modular para registrar a evolução da engenharia do projeto:

- 🏗️ **[Decisões de Arquitetura (ADRs)](docs/architecture/decisions.md):** Registros de decisões técnicas, trade-offs e justificativas (ex: uso do Nginx Alpine, Dockerfile customizado com multi-stage build para Composer).
- 🐳 **[Guia de Infraestrutura & Docker](docs/infrastructure/docker-setup.md):** Detalhes da rede, serviços, mapeamento de volumes, comandos e troubleshooting.
- 📐 **[Boas Práticas e Diretrizes](docs/standards/guidelines.md):** Padrões de isolamento, princípios de segurança e menor privilégio.
- 🎓 **[Metodologia de Mentoria (CONTEXT.md)](CONTEXT.md):** Diretrizes, objetivos de aprendizagem e método socrático adotado.

---

## ⚡ Como Rodar o Ambiente Local

### Pré-requisitos
- Docker Engine e Docker Compose instalados.

### Passo a Passo

1. **Clonar o repositório:**
   ```bash
   git clone https://github.com/seu-usuario/my-azevedo.git
   cd my-azevedo
   ```

2. **Subir os containers com build:**
   ```bash
   docker compose up -d --build
   ```

3. **Acessar a aplicação:**
   Abra o seu navegador em: `http://localhost`

---

## 🎯 Status do Projeto (Sprint 1 - Infraestrutura)

- [x] **Nginx:** Configurado como proxy reverso, isolamento de `public/` e Front Controller.
- [x] **PHP 8.3-FPM:** Containerização customizada via Dockerfile com extensões `pdo_pgsql`, `bcmath`, `gd`, `zip`, `redis` (PECL) e Composer.
- [x] **PostgreSQL 16:** Instalação, variáveis via `.env` e volume de persistência.
- [x] **Redis 7:** Camada de cache e sessões in-memory.
- [x] **Laravel:** Instalação e inicialização completa da aplicação no container.

---

### 📝 Licença

Este projeto é de código aberto para fins de aprendizado e portfólio.
