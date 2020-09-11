![tests](https://github.com/jeyroik/extas-grades/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/extas-grades/coverage.svg?branch=master)
<a href="https://github.com/phpstan/phpstan"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a> 

[![Latest Stable Version](https://poser.pugx.org/jeyroik/extas-grades/v)](//packagist.org/packages/jeyroik/extas-q-crawlers)
[![Total Downloads](https://poser.pugx.org/jeyroik/extas-grades/downloads)](//packagist.org/packages/jeyroik/extas-q-crawlers)
[![Dependents](https://poser.pugx.org/jeyroik/extas-grades/dependents)](//packagist.org/packages/jeyroik/extas-q-crawlers)

# Описание

Пакет позволяет организовать вычисление любых составных параметров - оценок.

- `Оценка` - это параметр, который высчитывается на основании каких-либо коэффициентов.
- `Коэффициент` - это параметр, который вычисляется на основании простых слагаемых. Не рекомендуется рассчитывать коэффициент на основании других коэффициентов - для этого есть оценки.
- `Слагаемое` - это простой параметр, который либо имеет статическое значение, либо вычисляется бес использования других слагаемых/коэффициентов/оценок.

Все три сущности имеют единый интерфейс, что позволяет организовать их размещение любым удобным образом.

# Установка

Пакет предоставляет несколько плагинов по-умолчанию для запуска расчётов термов (слагаемых), коэффициентов и оценок.

Чтобы эти плагины установить, их необходимо импортировать:

`extas.json`

```json
{
  "import": {
    "from": {
      "extas/grades": {
        "plugins": "*"
      }
    },
    "parameters": {
      "on_miss_package": {
        "name": "on_miss_package",
        "value": "continue"
      },
      "on_miss_section": {
        "name": "on_miss_section",
        "value": "throw"
      }
    }
  }
}
```

Инициализация и установка extas'a:

`# vendor/bin/extas init`
`# vendor/bin/extas install`

Запуск тестов:

`# composer test`

# Использование

1. Создайте (или установите) термы (слагаемые).
1.1. Создайте (или установите) калькуляторы термов.
2. Создайте (или установите) коэффициенты.
2.1. Создайте (или установите) калькуляторы коэффициентов.
3. Создайте (или установите) грейды (оценки).
3.1. Создайте (или установите) калькуляторы грейдов. 
4. `# php -S 0.0.0.0:8080 -t vendor/jeyroik/extas-api/public`
5. `# curl localhost:8080/api/jsonrpc -d '{"method":"grade.calculate", "params": {"names":["grade1"], "tag":"some.tag"}}'`

В результате выполнения запроса будет выполнен расчёт оценки `grade1`. 

Ответ будет примерно следующим:

```json
{
  "id": "<request.id>",
  "jsonrpc": "2.0",
  "result": {
    "grades": {
        "grade1": {
          "<grade.name>": "<grade.value>"
        }
    }
  }
}
```

# Использование со стандартными плагинами

Стандартные плагины ориентируются на теги термов, а именно:

- `grade.term.*`, `grade.term.<grade>` - теги для термов (слагаемых). 
- `grade.coefficient.*`, `grade.coefficient.<grade>` - теги для коэффициентов. 
- `grade.self.*`, `grade.self.<grade>` - теги для грейдов (оценок).

Учитывая эту информацию, пройдите шаги из инструкции выше.

## Использование вайлдкарда

"Плагины по-умолчанию" предоставляют возможность использования общего вайлдкарда для тегов - `*`. 

Данный нюанс позволяет "протаскивать" в ответ значение любых слагаемых и коэффициентов, если это требуется.

Допустим, есть терм с именем `term1`, для которого вычислено значение `public` и который имеет тег `*`.

В ответе (для запроса, указанного выше) его можно будет увидеть среди данных грейда:

 ```json
 {
   "id": "<request.id>",
   "jsonrpc": "2.0",
   "result": {
     "grades": {
         "grade1": {
           "<grade.name>": "<grade.value>",
           "term1": "public"
         }
     }
   }
 }
 ```

