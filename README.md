# Тг бот ковры

## Как развернуть проект?
#### На примере хостинга beget
1. Спулить с гита.
2. Сделать bashrc на php 7.4
3. Установить композер
4. Сделать символьную ссылку на папку
5. Накатить миграции
6. Прописать токен бота в env
7. Привязать вебхук
8. Написать боту в тг /start
9. Зайти в бд и поменять себе значение поля Status на VERIFIED и Role на admin
10. Вы великолепны

#### Как сделать символьную ссылку
```
ln -s public public_html

```
#### Как сделать bashrc
```
//в корне проекта
echo 'export PATH=/usr/local/php/cgi/7.4/bin/:$PATH:$HOME/.composer/vendor/bin' >> .bashrc
source .bashrc
```

## Что делать если бот сломався?
1. Отвязываем вебхук
2. Заходим и смотрим Update_id сообщений в getinfo
3. Заходим в BotController и внизу ищем метод skip, там в месте где написаны цифирки ставим на 1 больше чем макс Update_id в getinfo
4. Переходим по роуту %url сайта%/bot/updates

#### Все возможные вебхуки
```
add - https://api.telegram.org/bot%TOKEN BOT%/setWebhook?url=%url сайта%/bot/updates

delete - https://api.telegram.org/bot%TOKEN BOT%/deleteWebhook?url=%url сайта%/bot/updates

getinfo - https://api.telegram.org/bot%TOKEN BOT%/getInfo?url=%url сайта%/bot/updates
```
