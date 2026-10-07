# Padrões e Boas Práticas

Orientações de engenharia de software seguidas no desenvolvimento do projeto **My Azevedo**.

---

## 1. Princípios de Infraestrutura (Docker & DevOps)

1. **Princípio do Menor Privilégio e Isolamento:**
   - Cada serviço roda em seu próprio container com um único processo principal (princípio de responsabilidade única para containers).
   - Nginx roda apenas o processo de servidor web / proxy reverso; PHP roda apenas o processo FastCGI.
2. **Imagens Mínimas (`alpine`):**
   - Preferência por distribuições Alpine Linux para diminuir a superfície de ataque, tamanho da imagem e tempo de inicialização.
3. **Não Poluição da Máquina Hospedeira:**
   - Softwares de runtime (PHP, Composer, PostgreSQL, Redis, Node) devem rodar estritamente dentro de containers, preservando a máquina host limpa e reproduzível em qualquer ambiente.
4. **Validação Incremental:**
   - Nunca subir todos os serviços de uma vez sem validar a conectividade e os binds individualmente.

---

## 2. Boas Práticas de Configuração do Nginx

1. **Configuração Modular (`conf.d/`):**
   - Nunca sobrescrever `/etc/nginx/nginx.conf` diretamente para vhosts.
   - Montar arquivos específicos de vhost em `/etc/nginx/conf.d/*.conf`.
2. **Segurança de Cabeçalhos e Ocultação de Versão (Backlog):**
   - Configurar `server_tokens off;` nas etapas finais de produção.
   - Implementar proteção contra Clickjacking (`X-Frame-Options`) e Sniffing (`X-Content-Type-Options`).

---

## 3. Padrões de Documentação

- A documentação deve ser tratada como código de primeira classe (*Docs as Code*).
- Toda funcionalidade ou etapa de infraestrutura completada deve refletir uma atualização nos arquivos dentro de `/docs/`.

