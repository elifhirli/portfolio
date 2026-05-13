# Theme Cat Toggle

Dark/light mode icin navbar yanina eklenebilen kedi animasyon modulu.

## Klasor Yapisi

```txt
components/theme-cat-toggle/
  component.php
  theme-cat.css
  theme-cat.js
  frames/
    sleep-1.png
    sleep-2.png
    sleep-3.png
    sleep-4.png
    sleep-5.png
```

## Import

`includes/header.php` icine CSS:

```php
<link rel="stylesheet" href="/portfolio/components/theme-cat-toggle/theme-cat.css?v=<?php echo $cssVersion; ?>">
```

`includes/navbar.php` icinde `</nav>` kapanmadan once:

```php
<?php include __DIR__ . "/../components/theme-cat-toggle/component.php"; ?>
```

`includes/footer.php` icinde `</body>` oncesine JS:

```html
<script src="/portfolio/components/theme-cat-toggle/theme-cat.js"></script>
```

## Not

Kedi frame gorselleri tek set olarak kullanilir:

- `sleep-5.png`: dark mode / kedi uyuyor
- `sleep-1.png`: light mode / kedi uyanik
- Dark'tan light'a gecerken `sleep-5` -> `sleep-1` oynar.
- Light'tan dark'a gecerken `sleep-1` -> `sleep-5` oynar.

Navbar icin 90-120px genislik yeterli olur.
