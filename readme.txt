database    - DomainObjectAssembler
controllers - app/Commands

Короткая справка по классам и их функционалу:

FrontController   - центральная точка входа. Делегирует инициализацию ApplicationHelper

ApplicationHelper - Инициализация параметров системы. Чтрение файлов конфигурации, создание Registry, создаёт Request

Request           - сам заполняет себя параметрами запроса при создании объекта Request

CommandResolver   - Фабрика команд, создаёт команду по пути, полученному из Request и роутов из Registry