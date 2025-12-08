FROM php:8.4-rc-fpm

# 1. Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    libssl-dev \
    gnupg \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    unixodbc-dev \
    build-essential \
    nano \
    && rm -rf /var/lib/apt/lists/*

# 2. Install Microsoft SQL Server Drivers (Debian 12)
RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc \
        | gpg --dearmor \
        -o /usr/share/keyrings/microsoft-prod.gpg \
    && echo "deb [signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" \
        > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 \
    && rm -rf /var/lib/apt/lists/*

# Add SQL tools to PATH
ENV PATH="$PATH:/opt/mssql-tools18/bin"

# 3. Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql intl mbstring zip

# 4. Install SQLSRV + PDO_SQLSRV via PECL
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# 5. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Create non-root user with UID 1000 to avoid whoami errors
RUN useradd -u 1000 -m app

USER app

WORKDIR /var/www/html
