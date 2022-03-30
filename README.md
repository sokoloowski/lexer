# Temat 2 - Implementacja skanera

```
composer run lexer
```

## Terminy

Środa, 23.03 lub czwartek 24.03 - w zależności od grupy.

## Zakres

- Pojęcie tokena
- Przykłady wyodrębniania tokenów
- Tokeny jednowartościowe i wielowartościowe
- Definicja tokena - nieformalna
- Tokeny pomocnicze
- Opis tokena
  - symbol - kod
  - wyrażenie regularne
- Budowa tokena
  - symbol - kod
  - atrybuty - np. wartość
- Błędy leksykalne
- Analizator leksykalny = Skaner
  - rola skanera: ciąg znaków -> ciąg tokenów
  - pomijanie komentarzy, białych znaków
- Opis działania skanera
  - Diagramy przejść - stany i przejścia

## Zadania

- Wybierz format do skanowania i język implementacji
- Napisz program rozpoznający i wypisujący tokeny - pary: (kod, wartość) - w wyrażeniu matematycznym
  - funkcja skaner wywoływana w pętli do napotkania końca wyrażenia
  - rozpoznawane tokeny: liczba całkowita, identyfikator, działania `+`, `-`, `*`, `/`, nawiasy `(`, `)`
- Uzupełnij powyższy program o:
  - obsługę błędów skanera, w tym odpowiednie komentarze
  - lokalizację błędu, tu: numer kolumny gdzie rozpoczyna się błędny token
  - pomijanie spacji i innych białych znaków pomiędzy tokenami, jako pierwszy etap skanowania
