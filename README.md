BD2013-Projekt
==============

Projekt na Bazy Danych 2013, temat: Quizy tematyczne.

Development - Guard:
--------------------

Będziemy korzystać z Sassa i CoffeeScriptu - to są języki, które kompilują się do, odpowiednio - CSSa i JavaScriptu.

Żeby uniknąć problemu, który wynika z tego, że musielibyśmy po każdej zmianie te pliki przekompilować, użyjemy [guarda](https://github.com/guard/guard).

Jak tego używać?

Zainstalujcie sobie rubiego (na Ubuntu):
`apt-get install ruby`

Wpiszcie komendę:
`gem install bundler`

Po czym wejdźcie do katalogu z projektem i zróbcie:
`bundle install`

Po tej operacji, jak chcecie, żeby guard nasłuchiwał zmiany plików i je przekompilowywał, wystarczy że odpalicie sobie w terminalu:
`bundle exec guard`

I guard będzie automatycznie kompilował sobie te pliki.

Development - Uruchomienie:
---------------------------

Pamiętajcie, żeby stworzyć sobie plik config.php - po prostu przekopiujcie config.php.ex i zmieńcie wartości na takie jak macie w bazie.
Dodatkowo, wygenerujcie sobie sekret: wbijcie na [ten link](http://hash.online-convert.com/sha512-generator), wpiszcie cokolwiek randomowego i kliknijcie convert - wstawcie to sobie do zmiennej APP_SECRET.

Schemat:
--------

Schemat znajduje się w pliku `schemat.sql`.

Ważne informacje:
-----------------

Dodałem tabelę 'klucz_przegladarki', bo musimy jakoś sensownie i bezpiecznie trzymać sesję w bazie.
Zaktualizujcie te schematy ;).

TODO:
-----

-Zrobić funkcje do odczytu i zapisu zmiennych w sesji. (Sowa to zrobi)
-Zrobić zmienną globalną gdzie będzie id sesji(gry)
