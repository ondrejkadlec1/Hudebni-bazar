# Prvni projekt

## Domain Driver Design & CQRS

- https://www.infoq.com/articles/ddd-in-practice/
- https://programmer.group/php-implementation-of-domain-driven-design-services.html
- https://medium.com/nerd-for-tech/going-into-php-cqrs-85cf8e21fa57
- https://github.com/jorge07/ddd-playground

### Užitečná videa od PeckaDesign

- https://www.youtube.com/watch?v=Bd_ntFcqN7M&list=PLtzY2tCed56dfYvSwGPHvBw3ZC_rXnEfZ&index=4
- https://www.youtube.com/watch?v=-AEGHFC-Omw&list=PLtzY2tCed56dfYvSwGPHvBw3ZC_rXnEfZ&index=3
- https://www.youtube.com/watch?v=oKN9a6JL54o&list=PLtzY2tCed56dfYvSwGPHvBw3ZC_rXnEfZ&index=2
- https://www.youtube.com/watch?v=_9OyhJtebos&list=PLtzY2tCed56dfYvSwGPHvBw3ZC_rXnEfZ&index=1
- https://www.youtube.com/watch?v=1osB75lagD4&list=PLtzY2tCed56dfYvSwGPHvBw3ZC_rXnEfZ&index=11

## Docker - lokální vývoj

1) Pro lokální vývoj je potřeba mít nainstalovaný Docker a Docker Compose.
2) Spustit příkaz `docker-compose up -d` - musíš být přepnutý v root projektu - můžeš to klidně udělat v PhpStormu - v
   levém panelu - pravým tlačítkem na složku projektu - Open In Terminal a pak spustit příkaz
4) Upravit si hosts v systému a přidat si záznam

```127.0.01 nevim.local```

5) spouštění příkazů v rámci docker kontejner
    - `docker-compose exec web bash` - připojení do kontejneru a tam budeš spouštět jednotlivé příkazy
    - `docker-compose exec web bash -c "cd /var/www/html && xxxxxxx"` - spuštění příkazu v
      kontejneru
6) Je potřeba v rámci kontejneru pustit příkaz `composer install` - nainstalují se všechny potřebné závislosti
7) Je potřeba v rámci kontejneru pustit příkaz `vendor/bind/phinx migrate` - naplní se databáze

Pokud se vše provede správně bude na adrese https://nevim.local/ dostupná lokální kopie projektu

Strukturu projektu jsem Ti nachystal podle DDD a CQRS a zároveň jsem Ti udělal příklad, jak se to používá.
Na adrese https://nevim.local/test/ máš výstup.

## Kontrola kvality napsaného kódu

https://phpstan.org/ && https://github.com/easy-coding-standard/easy-coding-standard && https://phpmd.org/

```composer run checks``` - uvnitř kontejneru ve složce /var/www/html

```docker-compose exec web bash -c "cd /var/www/html && composer run checks"```

pokud tam jsou nějaké chyby, které lze opravit automaticky a hlásí je nástorj easy coding standard, tak lze spustit

```composer run ecsf``` - uvnitř kontejneru ve složce /var/www/html

```docker-compose exec web bash -c "cd /var/www/html && composer run ecsf"```

## Automatické vylepšení kódu pomocí nástroje RECTOR

https://getrector.com/documentation/

Tímto si zobrazíš navrhovaná vylepšení

```composer run rector``` - uvnitř kontejneru ve složce /var/www/html

```docker-compose exec web bash -c "cd /var/www/html && composer run rector"```

Tímto je pak rovnou necháš automaticky napravit

```composer run rectorf``` - uvnitř kontejneru ve složce /var/www/html

```docker-compose exec web bash -c "cd /var/www/html && composer run rectorf"```


