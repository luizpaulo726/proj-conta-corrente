FROM php:8.2-apache

# Instale as extensões necessárias do PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Instale o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Copie os arquivos do projeto para o diretório padrão do Apache
COPY . .

# Copie a configuração personalizada do Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Ative o módulo de reescrita do Apache
RUN a2enmod rewrite

# Instale dependências adicionais e execute o script de permissões
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Ajuste de permissões
RUN chmod -R 777 /var/www/html/storage \

# Exponha a porta 80
EXPOSE 80

# Inicie o servidor Apache
CMD ["apache2-foreground"]
