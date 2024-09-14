## 🚀 FilamentPHP

Este projeto utiliza o **[FilamentPHP](https://filamentphp.com/)**, um framework de administração para Laravel que facilita a criação de painéis administrativos modernos e altamente customizáveis.

### Principais Funcionalidades

-   **CRUDs Rápidos**: Gerencie facilmente modelos com operações de criação, leitura, atualização e exclusão.
-   **Formulários Personalizáveis**: Crie formulários com validações integradas e campos dinâmicos.
-   **Widgets e Dashboards**: Construa dashboards personalizados com gráficos e estatísticas em tempo real.
-   **Autenticação**: Controle de acesso com suporte a papéis e permissões via Laravel.

### Por que FilamentPHP?

-   **Fácil de usar**: Integração simplificada com Laravel.
-   **Flexível**: Altamente customizável para atender às necessidades do projeto.
-   **Livewire**: Integração nativa para criar interfaces dinâmicas sem JavaScript manual.

Para mais detalhes, consulte a [documentação oficial](https://filamentphp.com/docs).

## Requisitos

Para rodar este projeto, você precisará ter os seguintes softwares instalados:

-   **Docker**: Para a configuração dos contêineres de desenvolvimento.
-   **Composer**: Gerenciador de dependências do PHP.
-   **PHP 8.1+**: Versão do PHP necessária para rodar o projeto.
-   **Npm**: Gerenciador de pacotes JavaScript para build de front-end.

## Como rodar este projeto

Siga os passos abaixo para configurar e rodar o projeto:

```bash
# 1. Iniciar o Docker ou Docker Desktop.

# 2. Configurar o ambiente - Abra o terminal na pasta raiz do projeto e execute os seguintes comandos:

# Copiar o arquivo de exemplo de configuração
cp .env.example .env

# Instalar as dependências do PHP
composer install

# Gerar a chave da aplicação
php artisan key:generate

# Instalar o Laravel Sail
php artisan sail:install

# Construir os contêineres Docker
sail build

# Iniciar os contêineres
sail up -d

# Executar as migrações e popular o banco de dados
sail artisan migrate --seed

# 3. Configurar o Shield para gerenciar as permissões de acesso
sail artisan shield:install
# Do you wish to continue? (yes/no) - Responda com 'yes'
# Run `shield:install --fresh` instead? (yes/no) - Responda com 'yes'
# Please provide the `UserID` to be set as `super_admin` - Responda com 1

# 4. Acessar o projeto no navegador através da seguinte URL:
# Usuário admin e senha 12345678
# http://localhost/admin

```
