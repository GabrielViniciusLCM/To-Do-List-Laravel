# Laravel To-Do Application

## Descrição

Esta aplicação To-Do foi desenvolvida com foco na **intuitividade** e **facilidade de uso** para o usuário final. O processo de cadastro é simples, com poucas credenciais, e o usuário é redirecionado imediatamente para a lista de tarefas após se registrar.

---

## Funcionalidades Principais

- **Botão flutuante para adicionar tarefas:** Um botão localizado no canto inferior direito permite a criação rápida de novas tarefas, seguindo uma prática comum em aplicações modernas.
- **Alteração rápida do status da tarefa:** O usuário pode marcar uma tarefa como concluída diretamente na listagem, sem precisar abrir um formulário de edição, reduzindo cliques e otimizando a experiência.
- **Destaque visual para tarefas concluídas:** Títulos e datas das tarefas concluídas são riscados, facilitando a identificação rápida das pendentes.
- **Ordenação inteligente:** Tarefas pendentes aparecem primeiro, ordenadas da mais antiga para a mais nova, ajudando o usuário a focar no que realmente importa.
- **Confirmação para exclusão:** Antes de excluir uma tarefa, o sistema solicita confirmação para evitar exclusões acidentais.
- **Soft Deletes e filtro para tarefas deletadas:** Exclusões são feitas via soft delete, permitindo que o usuário restaure tarefas excluídas ao aplicar um filtro específico. Tarefas deletadas não aparecem na listagem principal por padrão.
- **Criação e filtragem por prioridade:** Agora é possível criar tarefas com prioridade (alta, média, baixa) e filtrá-las conforme a prioridade desejada para melhor organização e foco.

---

## Decisões de Design

- O botão de adicionar tarefa segue o padrão comum em apps para uma experiência familiar e intuitiva.
- A alteração rápida do status na listagem economiza tempo do usuário.
- O destaque visual das tarefas concluídas ajuda a separar visualmente o que está pendente.
- A ordenação das tarefas foi pensada para priorizar as pendentes mais antigas.
- Exclusão com confirmação e soft delete protege contra perda de dados acidental.
- A prioridade das tarefas foi incorporada para permitir organização e filtragem eficientes.

---

## Melhorias Futuras Planejadas

- **Tipos de usuários:** Criar perfis distintos, como admin e usuário comum. O admin poderá visualizar e gerenciar tarefas de outros usuários.
- **Filtros dinâmicos:** Implementar filtros que atualizam a lista automaticamente, sem necessidade de recarregar ou clicar em botão.
- **Tipos personalizados de tarefa:** Permitir que o usuário crie categorias próprias (ex.: esportes, escola) para organizar suas tarefas.
- **Cadastro em lote:** Possibilitar a criação de múltiplas tarefas de uma só vez.
- **Tarefas para datas futuras:** Suporte para agendamento de tarefas em datas específicas.
- **Testes automatizados:** Implementar testes para garantir a estabilidade e qualidade do código.
- **Registro de conclusão:** Manter histórico de quando a tarefa foi concluída, com registro de mudanças de status.

---

## Instalação e Configuração para rodar localmente

Siga os passos abaixo para configurar e executar a aplicação localmente:

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/GabrielViniciusLCM/To-Do-List-Laravel.git
   cd To-Do-List-Laravel

2. **Instale as dependências do PHP via Composer:**

   ```bash
   composer install
   ```

3. **Configure o arquivo de ambiente:**

   * Copie o arquivo `.env.example` para `.env`:

     ```bash
     cp .env.example .env
     ```
   * Edite o arquivo `.env` para configurar a conexão com seu banco de dados:

     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=nome_do_banco
     DB_USERNAME=seu_usuario
     DB_PASSWORD=sua_senha
     ```

4. **Gere a chave da aplicação:**

   ```bash
   php artisan key:generate
   ```

5. **Crie o banco de dados no seu SGBD preferido (MySQL, MariaDB, etc.)**

   * Use sua ferramenta preferida (MySQL CLI, phpMyAdmin, etc.) para criar um banco vazio com o nome configurado no `.env`.

6. **Execute as migrations para criar as tabelas:**

   ```bash
   php artisan migrate
   ```

7. **(Opcional) Execute os seeders, caso existam dados iniciais:**

   ```bash
   php artisan db:seed
   ```

8. **Baixe arquivos do vite**

```bash
npm install
npm run build
```

9. **Inicie o servidor local:**

   ```bash
   php artisan serve
   ```

   * A aplicação estará disponível em [http://localhost:8000](http://localhost:8000).

---

### Comandos Artisan importantes

* `php artisan migrate` — cria as tabelas do banco.
* `php artisan migrate:rollback` — desfaz a última migração.
* `php artisan db:seed` — insere dados iniciais, se existirem seeders.
* `php artisan serve` — inicia o servidor local.
* `php artisan key:generate` — gera a chave da aplicação Laravel.

---

## Uso

* Cadastre-se e seja redirecionado para a lista de tarefas.
* Use o botão inferior direito para adicionar novas tarefas.
* Marque tarefas como concluídas diretamente na lista.
* Filtre e restaure tarefas deletadas via filtro específico.
* Crie e filtre tarefas por prioridade (alta, média, baixa).
* Delete tarefas com segurança, confirmando antes.

---

## Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues e pull requests para sugerir melhorias ou corrigir bugs.

---

## Licença

[MIT License](LICENSE)

---

Qualquer dúvida, estou à disposição!

