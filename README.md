**Тестовое задание.**

Создать приложение на Yii2 advanced, позволяющее создать карточку с наименованием и описанием, количеством просмотров.

1) Создать миграции для формирования БД в MySQL и создания индекса в elasticsearch.
2) Создать CRUD для карточек
3) Создаваемые/изменяемые кароточки помещать в elasticsearch
4) Сделать страницу с отображением 3 последних добавленных карточек услуг (из elasticsearch) и указанием количества просмотров каждой карточки.
5) При просмотре карточки увеличивать счетчик просмотров.


**Дополнительная информация**

По информации, полученной от product owner-a проекта, в который прохожу собеседование:

- Проекту 3 месяца. Проект забирается у аутсорсеров. 
- Написан на Yii2 + MySQL в качестве поискового движка используется elasticsearch
- Планируемое количество активных пользователей - 1 миллион за первый год развития стартапа. Дальше - больше.
- У каждого пользователя может быть несколько активных карточек (точной цифры нет, но зная специфику предметной области, примем в среднем 10 на пользователя)
- Наличие прочей сложной бизнес логики по образу социальной сети.  

**Общие замечания по проекту.**

1) Yii2 никак не фреймворк enterprise-уровня, что подразумевает сам проект и планируемый объем данных. 
2) PostgreSQL значительно больше подходит для enterprise-решений. Даже в этом задании (CRUD подразумевает soft delete) 
частичный индекс по не удаленным данным был бы быстрее полного. На объеме в ~10 млн. записей это будет заметно.

**Общие замечания по заданию.**

1) Использование elasticsearch для решения задачи избыточно. На небольших объемах данных вполне хватит реляционной БД, а на больших прийдется все равно делать витрину на Redis.
2) Количество просмотров у последних добавленных карточек будет стремится к нулю при массовом добавлении карточек. 
То есть показ счетчика просмотров последних добавленых карточек при интенсивной работе сервиса с точки зрения бизнес-value имеет мало смысла.
3) Для подсчета просмотров лучше использовать сторонние решения, предоставляющие к тому же значительный функционал для web-аналитики (YM, GA, Piwik). 
Последний к тому же не сложно научить самостоятельно складывать количество просмотров в БД карточек (например через MQ).

**Комментарии к выполнению**

1) Миграция на чистом MySQL. Чтобы минимизировать зависимость от фреймворка и сделать ее более читаемой для людей, не сведущих в Yii2 (devops, системные администраторы)
2) Миграция должна быть обратно совсестимой поэтому не имеет функции отката.
3) Как видно из комментарие выше счетчик просмотров - вполне себе отдельная сущность предметной области, слабо связанная с самой карточкой. 
Это отражено в миграции - таблица с карточками отдельно от таблицы со счетчиками. 
4) В качестве идетификатора используется сгенерированный кодом UUID, а не автоинкремент БД. Так работать будет быстрее (особенно при интенсивном добавлении записей)
 и это более масштабируемое решение. 
5) В качестве архитектуры принята многослойная, характерная для монолитных enterprise-приложений
6) Библиотека yiisoft/yii2-elasticsearch выглядит очень странно (AR для поиска? Зачем?). С дефолтной конфигурацией не завелась:


    [responseBody] => Array
         (
             [error] => Content-Type header [application/x-www-form-urlencoded] is not supported
             [status] => 406
         )
    )
    
 
ruflin/elastica имеет более высокий порог вхождения для человека вообще незнакомого с ElasticSearch. 
Изучить можно, но явно не в рамках тестового задания.

