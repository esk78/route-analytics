# Route Analytics

Laravel-застосунок для аналізу щоденних маршрутів інспекторів по чекпоінтах.

Система зберігає команди, інспекторів, планові точки маршруту, фактично пройдені точки, відхилення від маршруту, швидкість між точками, статистику виконання маршрутів, карту маршруту та звіти по командах і інспекторах.

## Стек технологій

- PHP
- Laravel
- MySQL / MariaDB
- Blade
- Tailwind CSS
- Vite
- Leaflet.js
- OpenStreetMap
- Laravel Scheduler
- Docker / Laravel Sail
- Git

## Можливості

- Авторизація через Laravel Breeze
- Команди
- Контролери, прив’язані до команд
- Генерація 1 000 000 чекпоінтів
- Щоденні маршрути контролерів
- Планові точки маршруту
- Фактично пройдені точки маршруту
- Додаткові точки поза денним планом
- Відсоток виконання маршруту
- Середня швидкість маршруту
- Швидкість між точками
- Сторінка деталей маршруту
- Візуалізація маршруту на мапі через Leaflet
- Фільтрація маршрутів за командою, контролером і датами
- Звіти по командах та контролерах
- Artisan-команда для симуляції маршрутів
- Scheduler для автоматичної щоденної симуляції

## Вимоги

### Варіант 1: Docker / Laravel Sail

- Docker
- Docker Compose

### Варіант 2: Локальний запуск

- PHP 8.2+
- Composer
- Node.js та npm
- MySQL або MariaDB

## Встановлення через Docker / Laravel Sail

Клонувати репозиторій:

```bash
git clone https://github.com/esk78/route-analytics.git
cd route-analytics
```

Встановити PHP-залежності:

```bash
composer install
```

Скопіювати файл середовища:

```bash
cp .env.example .env
```

Встановити Laravel Sail:

```bash
composer require laravel/sail --dev
php artisan sail:install
```

Під час вибору сервісів обрати:

```bash
mysql
```

У файлі .env перевірити налаштування бази даних:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=route_analytics
DB_USERNAME=sail
DB_PASSWORD=password
```

Якщо Docker не дозволяє використовувати порт 80, додати або змінити:

```env
APP_PORT=8080
```

Запустити контейнери:

```bash
./vendor/bin/sail up -d
```

Згенерувати ключ застосунку:

```bash
./vendor/bin/sail artisan key:generate
```

Встановити frontend-залежності:

```bash
./vendor/bin/sail npm install
```

Запустити міграції та seeders:

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

Запустити симуляцію маршрутів:

```bash
./vendor/bin/sail artisan routes:simulate
```

Запустити Vite:
```bash
./vendor/bin/sail npm run dev
```

Відкрити застосунок:

```
http://localhost:8080
```
або
```
http://localhost
```
Логін:
```
User: test@example.com
Pass: 11111111