<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />


<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
                },
            }
        }
    }
</script>
<link rel="stylesheet" href="{{ asset('css/flux.css') }}">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@@livewire/flux@@1.x.x/dist/flux.min.js" defer></script>
@fluxAppearance
