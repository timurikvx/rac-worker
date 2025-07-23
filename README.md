# RAC - WORKER

Библиотека обертка для работы с компонентами RAS/RAC для платформы 1С Предприятие 8

С помощью нее можно взаимодействовать с:

 - Администраторами
 - Кластерами
 - Рабочими серверами кластера
 - Рабочими процессами кластера
 - Соединениями
 - Сеансами

## Установка

Для установки используйте команду

`` composer require timurikvx/rac-worker ``

## Использование

### Для авторизации при работе с RAC используются классы

#### Основной aдминистратор

```php
$clusterAgent = new ClusterAgent('name', 'password');
```
#### Администатор кластера

```php
$clusterUser = new ClusterUser('name', 'password');
```
#### Администатор базы данных

```php
$infobaseUser = new InfobaseUser('name', 'password');
```
## Основной класс RacWorker 

```php
$version = '8.3.23.2137'; //Версия 1С платформы
$worker = new RacWorker($version, 'localhost', 1545, RacArchitecture::X86_64);
```
#### Архитектура приложения может быть 
```php
RacArchitecture::X86_64
RacArchitecture::X64
```
## Работа с основными администраторами

### Список администраторов
```php
$error = '';
$agents = $worker->agent->list($clusterAgent, $error); //array<AgentEntity::class>
```
### Добавление администратора
```php
$error = '';
$agent = AgentEntity::create('Timmy', 'при оываыва ываываыв аыва');
$agent->setPassword('password');
$agent->setOsAuth('\\\\MACHINE\\UserName');
$worker->agent->add($agent, $clusterAgent, $error);
```
где ``$clusterAgent`` - уже существующий администратор, если он есть

### Удаление администратора
```php
$error = '';
$worker->agent->remove($agent, $clusterAgent, $error);
```

## Работа с Кластерами

### Список кластеров
```php
$clusters = $worker->cluster->list(); //array<ClusterEntity::class>
```
### Получить первый кластер 
```php
$cluster = $worker->cluster->first(); //ClusterEntity::class|null
```
### Получение кластера по имени
```php
$cluster = $worker->cluster->getByName('Локальный кластер'); //ClusterEntity::class|null
```
### Установка администоратора кластера

Если у вас уставнолен пароль на кластер или указаны основные администраторы нужно указат их, если у вас нет 
администраторов этот пункт можно пропустить или любой из методов

```php
$cluster->setUser($clusterUser); //администратор кластера
$cluster->setAgent($clusterAgent); //основной администратор
```
После этого у вас есть доступ к базам данных и кластерам

### Создание кластера
```php
$error = '';
$newCluster = ClusterEntity::create('localhost', 1800); //host, port
$newCluster->setName('Имя кластера');
$worker->cluster->add($newCluster, $error); //bool
```
### Изменение кластера
```php
$error = '';
$cluster->setLifetimeLimit(220);
$cluster->setMaxMemorySize(4123);
$cluster->setMaxMemoryTimeLimit(640);
$cluster->setSecurityLevel(300);
$cluster->setSessionFaultToleranceLevel(143);
$worker->cluster->update($cluster, $error); //bool
```
### Удаление кластера
```php
$error = '';
$worker->cluster->remove($cluster, $error); //bool
```

## Работа с администратором кластера

Это администраторы относящиеся к тому кластеру в который они добавлены, а основные администраторы защищают все кластера

### Список администраторов кластера
```php
$error = '';
$list = $worker->cluster->adminList($cluster, $error); //array<ClusterAdminEntity::class>
```
### Добавление администратора кластера
```php
$error = '';
$admin = ClusterAdminEntity::create('Имя администратора', 'Описание');
$admin->setPassword('пароль');
$admin->setOsAuth('имя пользователя ОС'); // для примера \\COMP\User1
$worker->cluster->adminAdd($cluster, $admin, $error);
```
где ``setOsAuth`` - установка авторизации ОС, в этом случае пароль передавать на нужно

### Удаление администратора кластера
```php
$error = '';
$worker->cluster->adminRemove($cluster, $admin, $error);
```
где ``$admin`` - сущность ClusterAdminEntity::class

## Работа с Базами данных

### Список баз данных кластера
```php
$error = '';
$infobases = $worker->infobase->list($cluster, $error); //array<InfobaseShortEntity::class>
```
``$error`` - выходной параметр если возникнет ошибка

### Базу данных по имени
```php
$error = '';
$infobase = $worker->infobase->getByName('Имя базы данных', $cluster, $error); //InfobaseShortEntity::class|null
```
### Первая база данных
```php
$error = '';
$infobase = $worker->infobase->first($cluster, $error); //InfobaseShortEntity::class|null
```
### Расширенная информация о базе данных
```php
$error = '';
$infobase = $worker->infobase->info($cluster, $infobase, $error); //InfobaseEntity::class|null
```
*После получения полного описания заново утсановите пользователя базы данных если будете с ней работать*
```php
$infobase->setUser($infobaseUser);
```
### Изменение базы данных
```php
$infobase->setDeniedFrom(''); //Начало блокировки сеансов \DateTime
$infobase->setDeniedTo(''); //Завершение блокировки сеансов \DateTime
$infobase->setDescription('Описание базы'); //Изменить описание базы
$infobase->setScheduledJobsDeny(true); //Блокировка регламетных заданий
$infobase->setSessionsDeny(true); //Блокировка сеанса
$infobase->setDeniedMessage('message');//Собщение пользователю
$infobase->setDeniedParameter(''); //Параметр блокировки
$worker->infobase->update($cluster, $infobase, $error); //bool
```
## Рабочие серверы кластера

### Список серверов кластера
```php
$error = '';
$servers = $worker->server->list($cluster, $error); //array<ServerEntity::class>
```
### Сервер по имени
```php
$error = '';
$infobase = $worker->infobase->getByName("Имя сервера", $cluster, $error); //ServerEntity::class|null
```
### Первый сервер
```php
$error = '';
$infobase = $worker->infobase->first($cluster, $error); //ServerEntity::class|null
```
### Добавление сервера
```php
$error = '';
$server = ServerEntity::create('Имя сервера', 'localhost', 1600);
$server->setInfobasesLimit(16);
$server->setPortMin(1600);
$server->setPortMax(1700);
$worker->server->add($cluster, $server, $error);
```
### Изменение сервера
```php
$error = '';
$server->setConnectionsLimit(998000);
$server->setInfobasesLimit(15000);
$server->setSafeCallMemoryLimit(7770000);
$server->setCriticalTotalMemory(450000);
$server->setCriticalTotalMemory(888000);
$server->setTemporaryAllowedTotalMemory(999000);
$server->setTemporaryAllowedTotalMemoryTimeLimit(300000);
$worker->server->update($cluster, $server, $error);
```
***
*Будьте аккуратны при изменении параметров сервера - это может привести к его неработоспособности!*
***
### Удаление сервера
```php
$error = '';
$worker->server->remove($cluster, $server, $error);
```

## Управление рабочими процессами кластера

### Список процессов
```php
$error = '';
$processes = $worker->process->list($cluster, $server, $error); //array<ProcessEntity::class>
```

## Соединения с кластером

### Список соединений
```php
$error = '';
$connections = $worker->connection->list($cluster, $process, $infobase, $error); //array<ConnectionEntity::class>
```

### Список соединений по типу приложения
```php
$error = '';
$connections = $worker->connection->getByAppID('1CV8', $cluster, $process, $infobase, $error); //array<ConnectionEntity::class>
```

### Список соединений по имени компьютера
```php
$error = '';
$connections = $worker->connection->getByHost('Имя компьютера', $cluster, $process, $infobase, $error); //array<ConnectionEntity::class>
```

### Список соединений по пользователю
```php
$error = '';
$connections = $worker->connection->getByUser('Имя пользователя', $cluster, $process, $infobase, $error); //array<ConnectionEntity::class>
```
### Удаление соединений
```php
$error = '';
foreach ($list as $connection) {
     $worker->connection->remove($cluster, $process, $connection, $infobase->getInfobaseUser(), $error);
}
```