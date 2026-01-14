# Custom Preloader Pro

Zaawansowany preloader WordPress z animowanym logo.

## Instalacja

1. Pobierz lub sklonuj repozytorium do: `/wp-content/plugins/preloader-jakubdura/`
2. Stwórz folder `/assets/` obok pliku pluginu
3. Wgraj swoje logo jako `logo.png` do folderu `/assets/`
4. Aktywuj wtyczkę w panelu WordPress
5. Idź do **Settings → Preloader** aby skonfigurować

## Funkcjonalności

- ✅ Włączanie/wyłączanie preloadera
- ✅ Kontrola czasu trwania (0.1 - 10 sekund)
- ✅ Animacja pulsowania logo
- ✅ Gładkie zanikanie po określonym czasie
- ✅ Czarne tło (#0a0a0a)
- ✅ Logo 150x150px

## Ustawienia

### Włącz Preloader
Checkbox do włączania/wyłączania preloadera na stronie.

### Czas trwania (sekundy)
Określa jak długo preloader będzie widoczny (domyślnie 0.6s).
- Min: 0.1 sekundy
- Max: 10 sekund

## Struktura

```
preloader-jakubdura/
├── preloader-jakubdura.php
├── .gitignore
├── README.md
└── assets/
    └── logo.png
```

## Wymagania

- WordPress 5.0+
- PHP 7.2+

## Autor

Jakub Dura

## Licencja

MIT
