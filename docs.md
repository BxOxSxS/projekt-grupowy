Pliki projektu należy na serwerze HTTP położyć w ścieżce relatywnej `/bsadowski/projekt-grupowy`. Od tej ścieżki są wykonywane żądania opisane niżej. 

Należy również zainstalować zalożności za pomocą narzędzia [composer](https://getcomposer.org/) poleceniem `composer install` w folderze projektu.
# `/`

## GET

Żądanie zwraca w postaci JSON tablice obiektów zawierające wartości kolumna po kolej z kodem 200 (OK) w przypadku powodzenia. W przypadku błędnych danych zwraca kod 400 (Bad Request).
Dostępne paremetry URL manipulujące zwracaną wartością:

- `WHERE` - wartość w postaci `{nazwa_kolumny} {operator porownania} {wartość} {opcjonalny operator logiczny i następna grupa porównania jak wcześniej}`
    - operator porównania jeden z: `"=" , "<>", "!=", "<", ">", "<=", ">=", "<=>", "LIKE"`
    - wartość jeden z:
        - `NULL`
        - wartość typu INT
        - ciąg znaków alfanumeryczny z opcjonalnym prostym wyłapywaniem wzoru (`%` i `_`) jeśli operator porównanie to `LIKE` zgodnie z dokumentacją MySQL w cudzysłowach podwójnych (`"`) i pojedyńczych (`'`)
    
        regex walidujący wartość: `/^(\d+|"[a-zA-Z0-9%_]*"|\'[a-zA-Z0-9%_]*\')$/`

    - opcjanoalny operator logiczny jeden z: `"AND", "&&", "OR", "||", "XOR"`. Pozwala po wykonać kolejne przyrównanie zgodnie z parametrami wyżej

    Przykład wartości `WHERE`: `id > 5 && marka LIKE "G%"`
    Jeśli wyszukanie powoduje że żadne rekordy nie pasują serwer zwraca kod 404 (Not found)
- `ORDER` - zawiera nazwę kolumny po której serwer MySQL ma sortować dane. Domyślnie robi to rosnąco aby ustawić na malojąco należy ustawić wartość parametru `ORDER_DIRECTION` na `DESC`
- `LIMIT` sprawia że API zwraca tylko pierwsze X rekordów z bazy, gdzie X to wartość typu INT parametru


## POST
Żądanie wstawia nowy rekord do bazy na podstawie danych w formie JSON z Body. Wartość ta to tablica z obiektami w formie `"{nazwa kolumny}": {wartość}`. Wartość to jedna z typów: `null, INT, String` zgodnie ze standartem JSON. Kolumna typu ID z autoinkrementacją może przyjąć wartość `null` co spowoduje automatyczne przydzielenie wartości przez serwer bazy danych. Serwer zwraca id nowego obiektu wraz z kodem 201 (Created) jeśli żądanie zostało pomyślnie wykonane. W przypadku błędnych danych zwraca kod 400 (Bad Request)
Przykładowa zawartość body:
```JSON
    [
        {
            "name": "id",
            "value": null
        },
        {
            "name": "marka",
            "value": "test"
        },
        {
            "name": "model",
            "value": "test2"
        },
        {
            "name": "model",
            "value": "test3"
        },
        {
            "name": "rocznik",
            "value": 2024
        },
        {
            "name": "cena",
            "value": 0
        }
    ]
```

## PUT
Żądanie bardzo podobne do POST jednak w przypadku podania istniejącego ID aktualizuje dane. W takim przypadku zwraca kod 204 (No content). Gdy nowy rekord jest utworzony serwer zwraca kod 201 (Created). W przypadku błędnych danych zwraca kod 400 (Bad Request). Body w dokłanie takej samej formie jak w POST

## PATCH
Żądanie służy tylko do aktualizacji danych konkretego rekordu. Rekord ten jest indentyfikowany za pomocą kolumny ID. Dane są w formie JSON w Body żądanie w postaci tablicy wartości do aktualizacji. Nie musi zawierać danych do każdej kolumny. Serwer zwraca kod 204 (No content) w przypadku powodzenia, 400 (Bad request) w przypadku błędnych danych. W przypadku podanie nieistniejącego ID zwaraca 404 (Not Found). 
Przykładowa zawartość body:
```JSON
    [
        {
            "name": "id",
            "value": 20
        },
        {
            "name": "rocznik",
            "value": 2023
        },
        {
            "name": "cena",
            "value": 1
        }
    ]
```

## DELETE
Żądanie usuwa wybrany rekord po ID. Wartośc ta jest przesyłana za pomocą parametru URL `ID` i jest typu INT. Serwer zwraca kod 204 (No content) w przypadku powodzenia, 400 (Bad request) w przypadku błędnych danych. W przypadku podanie nieistniejącego ID zwaraca 404 (Not Found).

# `/columns`
## GET
Żądanie nie przyjmujące parametrów. Zwraca w postaci JSON array kolumn wraz z typem ich danych. Na przykład:
```JSON
[
    {
        "name": "id",
        "type": "int(11)"
    },
    {
        "name": "marka",
        "type": "varchar(50)"
    },
    {
        "name": "model",
        "type": "varchar(50)"
    },
    {
        "name": "rocznik",
        "type": "int(11)"
    },
    {
        "name": "cena",
        "type": "int(11)"
    }
]
```


W przypadku każdego żądanie zwracany kod 400 (bad request) zawiera szczegółowe informacje co jest nie tak w postaci tekstowego kodu. Serwer może także zwrócić kod 500 (Internal Server Error) w nieoczekiwanych błedach wraz ze szczegółami.