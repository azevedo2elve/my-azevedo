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
│   └── php/                # (Em desenvolvimento: Dockerfile customizado)
├── docs/                   # Documentação do projeto
├── src/                    # Código da aplicação (montado em /var/www)
│   └── index.html          # Arquivo inicial de validação
├── docker-compose.yml      # Definição e orquestração dos serviços locais
└── CONTEXT.md              # Diretrizes de mentoria e objetivos de aprendizagem
```

---

## 3. Serviços Configurados

### 3.1 Nginx Web Server (`nginx-my-azevedo`)
- **Imagem Base:** `nginx:alpine`
- **Portas:** `80:80`
- **Volumes:**
  - `./docker/nginx/default.conf` ➔ `/etc/nginx/conf.d/default.conf` (não sobrescreve o `/etc/nginx/nginx.conf` principal).
  - `./src` ➔ `/var/www` (ponto de montagem raiz para servir estáticos e arquivos PHP).
- **Rede:** `blog-network` (driver `bridge`).

---

## 4. Guia de Operação e Troubleshooting

### Comandos Comuns
```bash
# Subir serviços em background
docker compose up -d

# Visualizar status dos containers
docker compose ps

# Visualizar logs em tempo real
docker compose logs -f nginx

# Derrubar containers
docker compose down
```

### Problemas Conhecidos e Soluções
* **Conflito na porta 80 com Apache2 local (WSL2/Linux):**
  * *Sintoma:* `http://localhost` retorna a página padrão do Apache2 do host em vez do container.
  * *Causa:* O Apache2 nativo no host estava rodando e capturava as requisições antes do Docker.
  * *Solução:* Parar e desabilitar o Apache nativo:
    ```bash
    sudo service apache2 stop
    sudo update-rc.d apache2 disable
    sudo ss -tulpn | grep :80 # Deve retornar vazio
    ```

