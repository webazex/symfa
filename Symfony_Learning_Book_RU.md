# Symfony Learning Book

Практический курс Symfony для PHP-разработчика с опытом Yii2  
Редакция: 0.1  
Базовая версия: Symfony 8.1.6  
Язык объяснений: русский  
Язык документации, кода и терминов: английский

---

## 1. Зачем существует эта книга

Эта книга — не пересказ документации и не набор кода для копирования. Это маршрут обучения, в котором официальная английская документация Symfony превращена в последовательность небольших практических задач.

Цель курса: не просто научиться получать работающую страницу или CRUD, а понимать:

- как HTTP-запрос проходит через Symfony;
- где заканчивается framework и начинается код приложения;
- как Symfony находит контроллеры и сервисы;
- что именно делает Dependency Injection Container;
- чем Doctrine отличается от Active Record;
- как вручную построить JSON API;
- что позже автоматизирует API Platform;
- как проверять решение тестами и диагностическими командами.

Основной учебный проект называется **Catalog Lab**. К концу курса это будет небольшой каталог товаров с HTML-интерфейсом, JSON API, базой данных, авторизацией, тестами, очередью и кешированием.

## 2. Почему Symfony 8.1

Курс закреплён за конкретной веткой документации: `8.1`. Ссылки вида `/current/` сознательно не используются, потому что их содержимое меняется при выходе новой версии Symfony. Сейчас `/current/` указывает на 8.1, но после следующего релиза начнёт указывать на другую ветку.

Symfony 8.1:

- является актуальной stable-веткой на момент начала курса;
- требует PHP 8.4 или новее;
- совпадает с уже установленной в учебном проекте версией 8.1.6;
- позволяет изучать актуальные PHP attributes, DI, Doctrine и API Platform без искусственного downgrade.

Symfony 8.1 не является LTS и имеет более короткий срок поддержки, чем 7.4 LTS. Для учебного проекта это приемлемо: задача курса — изучить актуальный framework. Для долгоживущего production-проекта выбор версии рассматривается отдельно и может закончиться выбором LTS.

Официальные источники:

- [Symfony releases](https://symfony.com/releases)
- [Symfony 8.1 Setup](https://symfony.com/doc/8.1/setup.html)
- [Symfony: The Fast Track](https://symfony.com/doc/8.1/the-fast-track/en/index.html)
- [Symfony 8.1 Documentation](https://symfony.com/doc/8.1/index.html)
- [API Platform Documentation](https://api-platform.com/docs/)

## 3. Правила обучения

1. Ученик пишет код самостоятельно.
2. Ментор объясняет модель, формулирует задачу, проверяет решение и указывает на ошибки.
3. Готовое решение выдаётся только после самостоятельной попытки или когда проблема не относится к теме урока.
4. Ошибка не исправляется случайным перебором. Сначала читаются сообщение, класс исключения, файл и строка.
5. Каждый урок завершается проверяемым результатом.
6. Нельзя переходить дальше, если ученик может повторить действия, но не может объяснить их смысл.
7. Генераторы MakerBundle разрешены, но сгенерированный код обязательно разбирается.
8. Все новые зависимости проверяются через `composer show`, а изменения рецептов — через Git diff.
9. Framework-магия всегда раскладывается на вход, конфигурацию, вызванный компонент и результат.

### Формат сдачи урока

Для проверки обычно нужны:

- краткое объяснение своими словами;
- относящийся к задаче код или Git diff;
- вывод указанных диагностических команд;
- точный текст ошибки, если задача не работает;
- собственная гипотеза о причине ошибки.

Фраза «не работает» без фактов не считается отчётом об ошибке.

## 4. Учебный проект: Catalog Lab

Проект развивается постепенно. Не нужно создавать все сущности и слои заранее.

Итоговые возможности:

- публичный HTML-каталог;
- карточка товара;
- категории товаров;
- административные операции;
- ручной JSON API;
- валидация входных данных;
- пользователи и разграничение доступа;
- логирование значимых действий;
- асинхронная обработка сообщения;
- кеширование списка;
- unit, integration и application tests;
- отдельная read-only публикация через API Platform.

Предварительная модель товара:

- identifier;
- name;
- description;
- price;
- status;
- category;
- createdAt;
- updatedAt.

Точный PHP- и DB-тип цены будет выбран в уроке Doctrine после сравнения `integer minor units`, `DECIMAL` и `float`. До этого решения поле создавать не нужно.

## 5. Карта между Yii2 и Symfony

Это ориентиры, а не гарантированное соответствие один к одному.

| Задача | Привычная точка в Yii2 | Основная точка в Symfony |
| --- | --- | --- |
| Вход HTTP-запроса | `index.php`, Application | `public/index.php`, Kernel |
| Маршрут | URL Manager | Routing component |
| Обработчик | Controller action | Controller method/callable |
| Запрос и ответ | `yii\web\Request/Response` | HttpFoundation `Request/Response` |
| Зависимости | DI container, service locator, config components | Service Container, autowiring |
| Модель БД | Обычно Active Record | Doctrine ORM, Data Mapper |
| Валидация | Rules в Model/Form Model | Validator constraints, DTO/entity |
| JSON | Response formatter | `JsonResponse` и Serializer |
| Консоль | Console Controller/Action | Console Command |
| Очереди | `yii2-queue` | Messenger |
| События | Events/behaviors | EventDispatcher и framework events |
| Авторизация | RBAC, access control | Security, voters, access control |

Главное различие, которое нельзя замазывать аналогиями: Doctrine entity не является Symfony-аналогом Yii ActiveRecord. Entity описывает состояние и отношения предметной области, а загрузкой и сохранением занимается отдельный EntityManager.

## 6. Маршрут курса

Статусы:

- `[ ]` — не начато;
- `[-]` — в работе;
- `[x]` — пройдено и проверено.

### Этап A. Основа framework

- [ ] Урок 0. Версия и рабочее окружение
- [ ] Урок 1. Структура проекта и точка входа
- [ ] Урок 2. HTTP: Request → Controller → Response
- [ ] Урок 3. Routing без магии
- [ ] Урок 4. Контроллеры и разные типы Response
- [ ] Урок 5. Twig и граница представления
- [ ] Урок 6. Composer, Flex, recipes и configuration
- [ ] Урок 7. Service Container, autowiring и application services

### Этап B. Данные и ввод

- [ ] Урок 8. Doctrine: Data Mapper, entity и migration
- [ ] Урок 9. Repository, QueryBuilder и отношения
- [ ] Урок 10. Validator и входные модели
- [ ] Урок 11. Forms и обработка HTML-ввода

### Этап C. API

- [ ] Урок 12. Ручной JSON API и HTTP semantics
- [ ] Урок 13. Serializer, DTO и контролируемый контракт
- [ ] Урок 14. Ошибки API и единый формат ответа
- [ ] Урок 15. API Platform без слепой магии

### Этап D. Надёжность и эксплуатация

- [ ] Урок 16. Security: authentication и authorization
- [ ] Урок 17. Тестирование
- [ ] Урок 18. Logging, events и console commands
- [ ] Урок 19. Messenger и асинхронная работа
- [ ] Урок 20. Cache и измерение результата
- [ ] Урок 21. Итоговый архитектурный разбор
- [ ] Урок 22. Версионная дисциплина и обновления

---

# Этап A. Основа framework

## Урок 0. Версия и рабочее окружение

### Цель

Проверить существующий проект на Symfony 8.1.6 и доказать командами, какая версия PHP, Symfony и пакетов фактически выполняется. Переустановка или downgrade не требуются.

### Прочитать

Основной материал:

- [Installing & Setting up Symfony 8.1](https://symfony.com/doc/8.1/setup.html)

Дополнительно:

- [Fast Track: Checking your Work Environment](https://symfony.com/doc/8.1/the-fast-track/en/1-tools.html)
- [Symfony releases](https://symfony.com/releases)

Не нужно читать дополнительные разделы вперёд.

### Модель, которую нужно понять

Проект Symfony — это не скачанный «framework-каталог». Это Composer-проект, в котором:

- `composer.json` задаёт допустимые версии прямых зависимостей;
- `composer.lock` фиксирует точные установленные версии всего дерева;
- `vendor/` содержит установленные пакеты;
- Symfony Flex обрабатывает recipes при установке некоторых пакетов;
- `symfony.lock` фиксирует применённые recipes;
- версия PHP в терминале, web server и PHP-FPM может различаться.

### Задача 0.1. Зафиксировать окружение

Выполнить и сохранить вывод:

```bash
php -v
php --ini
composer --version
symfony -V
symfony check:requirements
```

Если команда `symfony` отсутствует, это не повод устанавливать что-либо вслепую. Сначала зафиксировать факт и сообщить его.

### Задача 0.2. Проверить существующий учебный проект

Использовать уже созданный проект на Symfony 8.1.6. Не переустанавливать framework, не выполнять downgrade и не удалять экспериментальный код до отдельного решения.

В корне проекта выполнить:

```bash
pwd
git status
composer show symfony/framework-bundle
composer show symfony/flex
composer show api-platform/core
```

Последняя команда может сообщить, что API Platform не установлен. Это допустимый диагностический результат.

### Задача 0.3. Проверить реальное состояние проекта

Внутри проекта выполнить:

```bash
php bin/console about
composer show symfony/framework-bundle
composer show symfony/flex
git status
```

Если Git не был инициализирован автоматически, инициализировать репозиторий и сделать первый осмысленный commit после просмотра файлов.

### Вопросы для самопроверки

1. Чем `composer.json` отличается от `composer.lock`?
2. Для чего нужен каталог `vendor/` и почему его обычно не коммитят?
3. Что делает Flex поверх обычного Composer?
4. Зачем существует `symfony.lock`?
5. Как доказать, что проект действительно использует Symfony 8.1.6, а не верить имени документации?
6. Почему вывод `php -v` в терминале ещё не гарантирует ту же версию PHP под Nginx/PHP-FPM?

### Критерий готовности

Урок пройден, если:

- приложение запускается;
- стартовая страница отвечает без HTTP 500;
- `php bin/console about` выполняется;
- `symfony/framework-bundle` имеет версию 8.1.6;
- ученик может объяснить роли Composer и Flex;
- исходное состояние сохранено в Git.

### Что прислать ментору

- вывод пяти команд из задачи 0.1;
- вывод `php bin/console about`;
- строку версии из `composer show symfony/framework-bundle`;
- ответы на вопросы 1–5 своими словами;
- описание способа запуска приложения: Symfony CLI, встроенный PHP server или Nginx/PHP-FPM.

## Урок 1. Структура проекта и точка входа

### Цель

Перестать воспринимать каталоги Symfony как ритуальную структуру и понять назначение каждой основной директории.

### Прочитать

- раздел Project Structure в [Create your First Page](https://symfony.com/doc/8.1/page_creation.html)
- [Symfony and HTTP Fundamentals](https://symfony.com/doc/8.1/introduction/http_fundamentals.html)

### Практика

1. Найти web entry point.
2. Найти класс Kernel приложения.
3. Найти конфигурацию routes.
4. Найти конфигурацию services.
5. Найти каталоги cache и logs.
6. Найти переменные окружения и определить, какие файлы разрешено коммитить.
7. Составить собственную таблицу `путь → ответственность` для `bin/`, `config/`, `public/`, `src/`, `templates/`, `tests/`, `translations/`, `var/`, `vendor/`.

### Ограничение

На этом уроке не создавать controller через MakerBundle. Сначала нужно увидеть пустой проект и понять маршрут выполнения.

### Проверка

```bash
php bin/console about
php bin/console debug:config framework
php bin/console debug:router
```

### Контрольные вопросы

1. Почему web server должен смотреть в `public/`, а не в корень проекта?
2. Какой файл принимает HTTP-запрос первым?
3. Что допустимо хранить в `src/`, а что туда класть не следует?
4. Чем `var/cache` отличается от `vendor`?
5. Почему `.env` не следует считать надёжным хранилищем production-секретов?

### Результат

Кода почти нет. Результатом урока является точная карта проекта и понимание входа запроса.

## Урок 2. HTTP: Request → Controller → Response

### Цель

Понять минимальный сценарий страницы без `AbstractController`, Twig, Doctrine и API Platform.

### Прочитать

- [Create your First Page](https://symfony.com/doc/8.1/page_creation.html)
- [HttpFoundation Component](https://symfony.com/doc/8.1/components/http_foundation.html)

### Практика

1. Самостоятельно создать controller class без наследования от `AbstractController`.
2. Создать маршрут `GET /health`.
3. Вернуть обычный `Response` с текстом `OK`.
4. Установить правильный `Content-Type`.
5. Проверить статус, headers и body через браузерные DevTools или `curl -i`.
6. Объяснить, откуда Symfony узнал, какой метод вызвать.

### Усложнение

Добавить endpoint `GET /request-info`, который получает объект `Request` и возвращает безопасную часть информации о текущем запросе.

### Критерий готовности

Ученик способен без подсказки описать цепочку:

```text
web server → public/index.php → Kernel → Router → controller → Response
```

## Урок 3. Routing без магии

### Источник

- [Routing](https://symfony.com/doc/8.1/routing.html)

### Темы

- route path и route name;
- placeholders;
- requirements;
- defaults;
- HTTP methods;
- route priority;
- attribute routes;
- URL generation;
- диагностика совпадения маршрута.

### Практика

Создать несколько маршрутов каталога так, чтобы:

- список и карточка не конфликтовали;
- идентификатор принимал только допустимый формат;
- неправильный HTTP method не обрабатывался;
- URL карточки генерировался по имени route, а не собирался строкой.

### Проверка

```bash
php bin/console debug:router
php bin/console router:match /products/42
```

## Урок 4. Контроллеры и разные типы Response

### Источник

- [Controller](https://symfony.com/doc/8.1/controller.html)

### Практика

Для одного набора тестовых данных вернуть:

- HTML response;
- JSON response;
- redirect;
- 404 response;
- download response или небольшой streamed response.

Затем определить, какие методы появились только после наследования от `AbstractController` и какие сервисы они скрывают.

### Главный вопрос

Является ли `AbstractController` обязательной частью controller? Ответ должен быть подтверждён работающим примером.

## Урок 5. Twig и граница представления

### Источники

- [Templates with Twig](https://symfony.com/doc/8.1/templates.html)
- раздел Rendering a Template в [Create your First Page](https://symfony.com/doc/8.1/page_creation.html)

### Практика

- общий layout;
- список товаров;
- карточка товара;
- partial/component для цены;
- URL через `path()`;
- корректное escaping;
- пустое состояние списка.

### Архитектурное ограничение

Twig отображает подготовленные данные. Запросы к базе и изменение бизнес-состояния в template запрещены.

## Урок 6. Composer, Flex, recipes и configuration

### Источники

- [Installing Packages](https://symfony.com/doc/8.1/setup.html#installing-packages)
- [Configuring Symfony](https://symfony.com/doc/8.1/configuration.html)
- [Symfony Flex](https://symfony.com/doc/8.1/setup.html#installing-packages)

### Практика

1. Установить небольшой пакет через Flex alias.
2. До commit изучить `git diff`.
3. Найти изменения в `composer.json`, `composer.lock`, `symfony.lock` и `config/`.
4. Разделить обычную configuration value и secret.
5. Получить параметр через контейнер, не читая `$_ENV` из application service.
6. Удалить пакет и сравнить обратные изменения recipe.

## Урок 7. Service Container, autowiring и application services

### Источник

- [Service Container](https://symfony.com/doc/8.1/service_container.html)

### Практика

1. Создать небольшой сервис форматирования цены или формирования карточки товара.
2. Передать зависимость через constructor injection.
3. Проверить регистрацию через `debug:container`.
4. Создать interface и две реализации.
5. Явно выбрать реализацию для interface.
6. Найти случай, когда autowiring не может сделать однозначный выбор.

### Проверка

```bash
php bin/console debug:container
php bin/console debug:autowiring
```

### Порог понимания

Ученик должен объяснить разницу между:

- service;
- service definition;
- service id;
- alias;
- autowiring;
- autoconfiguration;
- public/private service.

---

# Этап B. Данные и ввод

## Урок 8. Doctrine: Data Mapper, entity и migration

### Источник

- [Databases and Doctrine ORM](https://symfony.com/doc/8.1/doctrine.html)
- [Doctrine DBAL Types](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/types.html)

### Практика

- настроить отдельную development database;
- создать `Product`;
- понять каждый mapping attribute;
- создать migration;
- прочитать SQL migration до выполнения;
- выполнить migration;
- создать и загрузить запись через EntityManager;
- сравнить entity с Yii ActiveRecord.

### Отдельное исследование: деньги

До создания поля `price` письменно сравнить:

- `float`;
- `DECIMAL(p, s)` + PHP `string`;
- integer в минимальных денежных единицах;
- Money value object.

Для Catalog Lab выбрать вариант и защитить решение аргументами: точность, арифметика, API contract, валюта, диапазон и удобство запросов.

## Урок 9. Repository, QueryBuilder и отношения

### Источник

- раздел Querying for Objects в [Doctrine](https://symfony.com/doc/8.1/doctrine.html)
- [Doctrine Associations](https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/association-mapping.html)

### Практика

- создать `Category`;
- настроить отношение Product → Category;
- определить owning side;
- написать именованный repository method;
- добавить фильтрацию по status и category;
- увидеть проблему N+1 на профайлере;
- исправить запрос осознанно.

## Урок 10. Validator и входные модели

### Источник

- [Validation](https://symfony.com/doc/8.1/validation.html)

### Практика

- ограничения для name, price и status;
- class-level или custom constraint для составного правила;
- validation groups;
- вывод ошибок без потери структуры;
- сравнение constraint на entity и отдельном input DTO.

## Урок 11. Forms и обработка HTML-ввода

### Источник

- [Forms](https://symfony.com/doc/8.1/forms.html)

### Практика

- форма создания товара;
- GET/POST lifecycle;
- `handleRequest()`;
- distinction между submitted и valid;
- CSRF;
- повторное отображение ошибок;
- PRG pattern после успешного сохранения.

---

# Этап C. API

## Урок 12. Ручной JSON API и HTTP semantics

### Источники

- [Controller: JSON responses](https://symfony.com/doc/8.1/controller.html#returning-json-response)
- [HttpFoundation](https://symfony.com/doc/8.1/components/http_foundation.html)

### Практика

Реализовать без API Platform:

- `GET /api/products`;
- `GET /api/products/{id}`;
- `POST /api/products`;
- корректные `Content-Type`, status codes и `Location`;
- 400, 404, 409 и 422 только там, где их семантика обоснована;
- проверку endpoint через HTTP client.

## Урок 13. Serializer, DTO и контролируемый контракт

### Источник

- [Serializer](https://symfony.com/doc/8.1/serializer.html)

### Практика

- input DTO для создания продукта;
- output DTO для ответа;
- deserialize → validate → application service → serialize;
- normalization groups как альтернативный механизм;
- защита от случайной публикации внутреннего поля entity;
- проверка типов и неизвестных полей входного JSON.

### Архитектурный вопрос

Почему возврат entity напрямую может быть удобным сегодня и опасным для контракта завтра?

## Урок 14. Ошибки API и единый формат ответа

### Источники

- [Error Handling](https://symfony.com/doc/8.1/controller/error_pages.html)
- [EventDispatcher](https://symfony.com/doc/8.1/components/event_dispatcher.html)

### Практика

- единый error payload;
- validation errors;
- domain conflict;
- not found;
- unexpected server error;
- request/correlation id;
- отсутствие stack trace в production response.

## Урок 15. API Platform без слепой магии

### Источники

- [API Platform Getting Started](https://api-platform.com/docs/core/getting-started/)
- [Fast Track: Exposing an API with API Platform](https://symfony.com/doc/8.1/the-fast-track/en/26-api.html)

### Входное требование

До урока ученик умеет вручную реализовать route, controller, validation, serialization и persistence для одного endpoint.

### Практика

1. Установить API Platform через Flex.
2. По `composer show api-platform/core` определить фактическую major version.
3. Использовать namespace, соответствующий установленной версии.
4. Опубликовать read-only Product resource.
5. Явно ограничить operations.
6. Управлять полями ответа.
7. Сравнить с ручным endpoint.
8. Составить таблицу: что сгенерировано, что настроено и где остаётся бизнес-логика.

### Порог понимания

`#[ApiResource]` — metadata, а не «запуск API». Нужно уметь объяснить, кто читает metadata и какие подсистемы участвуют в выполнении операции.

---

# Этап D. Надёжность и эксплуатация

## Урок 16. Security: authentication и authorization

### Источник

- [Security](https://symfony.com/doc/8.1/security.html)

### Практика

- User и password hashing;
- firewall;
- login;
- role-based restriction;
- voter для правила изменения Product;
- 401 против 403;
- CSRF для HTML и особенности stateless API.

## Урок 17. Тестирование

### Источник

- [Testing](https://symfony.com/doc/8.1/testing.html)

### Практика

- unit test чистого сервиса;
- integration test сервиса из container;
- application test HTML endpoint;
- application test JSON endpoint;
- отдельная test database;
- проверка негативных сценариев;
- понимание, где mock помогает, а где скрывает реальную интеграцию.

## Урок 18. Logging, events и console commands

### Источники

- [Logging](https://symfony.com/doc/8.1/logging.html)
- [Events and Event Listeners](https://symfony.com/doc/8.1/event_dispatcher.html)
- [Console Commands](https://symfony.com/doc/8.1/console.html)

### Практика

- structured log при изменении цены;
- domain/application event после изменения;
- listener с одной ответственностью;
- command для импорта тестового каталога;
- корректные exit codes;
- отсутствие бизнес-логики внутри command.

## Урок 19. Messenger и асинхронная работа

### Источник

- [Messenger](https://symfony.com/doc/8.1/messenger.html)

### Практика

- message и handler;
- sync transport как первая стадия;
- async transport;
- worker;
- retry и failure transport;
- идемпотентность handler;
- граница транзакции между сохранением Product и публикацией message.

## Урок 20. Cache и измерение результата

### Источник

- [Cache](https://symfony.com/doc/8.1/cache.html)

### Практика

- кеш списка товаров;
- key design;
- TTL;
- invalidation;
- cache stampede как риск;
- измерение времени до и после;
- доказательство, что кеш реально используется.

## Урок 21. Итоговый архитектурный разбор

### Источник

- [Symfony Best Practices](https://symfony.com/doc/8.1/best_practices.html)

### Задача

Провести review Catalog Lab:

- thin controllers;
- application services;
- entities и invariants;
- repositories;
- input/output DTO;
- framework-independent domain code;
- error contract;
- transactions;
- tests;
- security;
- observability;
- отсутствие преждевременных abstractions.

Результат — не переписывание ради «чистой архитектуры», а список конкретных проблем с объяснимой стоимостью и приоритетом.

## Урок 22. Версионная дисциплина и обновления

### Источники

- [Upgrading a Symfony Application](https://symfony.com/doc/8.1/setup/upgrade_major.html)
- [Symfony 8.1 release](https://symfony.com/releases/8.1)
- [Symfony releases](https://symfony.com/releases)

### Практика

- проверить срок поддержки текущей ветки;
- найти и устранить deprecations до смены ветки;
- определить целевую поддерживаемую версию и читать документацию именно этой версии;
- обновить ограничения зависимостей в отдельной Git-ветке;
- выполнить tests до и после;
- изучить реальный diff;
- отделить изменения framework от улучшений собственного кода.

---

## 7. Диагностическая шпаргалка

Команды не нужно заучивать сразу. Они вводятся по мере прохождения уроков.

```bash
php bin/console about
php bin/console list
php bin/console debug:router
php bin/console router:match /some/path
php bin/console debug:container
php bin/console debug:autowiring
php bin/console debug:config framework
php bin/console debug:event-dispatcher
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
composer show
composer show package/name
composer audit
```

## 8. Шаблон журнала обучения

Заполнять после каждого урока.

```markdown
### Урок N — название

- Дата:
- Статус:
- Что я реализовал:
- Как я понимаю механизм:
- Какие команды диагностики использовал:
- Ошибки и их причины:
- Что осталось непонятным:
- Git commit:
```

## 9. Текущая точка

Текущий урок: **Урок 0. Версия и рабочее окружение**.  
Следующее действие: выполнить задачу 0.1 и прислать вывод команд ментору.  
Не создавать entity, Doctrine migration или API resource до прохождения соответствующих уроков.
