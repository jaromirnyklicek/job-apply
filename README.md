# Recruitis.io job apply

## Instalace
```bash
docker compose up -d # run docker container
docker compose exec apache.recruitis npm ci # install frontend dependencies
docker compose exec apache.recruitis npm run build # build Vue app
docker compose exec apache.recruitis composer install # install backend dependencies
```
Aplikace pak běží na URL: http://localhost:3030

Nezapomeňte do souboru`.env.local` vložit API token:
```
RECRUITIS_API_TOKEN=your_personal_api_token
```
## Vývojové tooly
### eslint
```bash
docker compose exec apache.recruitis npm run lint
```
### phpstan
```bash
docker compose exec apache.recruitis composer phpstan
```
### php-cs-fixer
```bash
docker compose exec apache.recruitis composer cs:check
docker compose exec apache.recruitis composer cs:fix
```
### Codeception
```bash
docker compose exec apache.recruitis composer test
```
## Poznámky k řešení
### Frontend vs. backend
Zvažoval jsem, jestli aplikaci rozdělit do dvou repozitářů a vyvíjet frontend ve Vue a backend v Symfony odděleně. Nakonec jsem se ale rozhodl pro monorepo, kde Symfony řídí i rendering markupu, do kterého se pak spouští Vue aplikace. Kupodivu se s tímhle set-upem pracovalo velmi příjemně, takže minimálně v tomhle případě byl pro mě tento úkol přínosný a něco nového jsem se na něm naučil.

### Struktura složky `src`
Mám hodně rád Domain Driven Design resp. Clean architecture přístup, takže jsem toto rozvrstvení použil i při návrhu adresářové struktury ve složce `src`. 
- Domain/ – doménová vrstva (business logika, nezávislá na frameworku)
- Application/ – aplikační logika (use-casy)
- Infrastructure/ – implementace rozhraní, napojení na "okolní svět"
- UI/ - uživatelské rozhraní (API, MVC, CLI apod.)

Domnívám se, že takto je ta struktura testovatelná, dostatečně modulární a připravená na růst aplikace. Pokud by kdykoliv bylo potřeba backend přepsat z RESTu na GraphQL, nebo Symfony na jiný framework, stačí vyměnit UI a částečně Infrastructure – Domain a Application zůstávají.

### Cachování
Pro mě asi nejsložitější část zadání 🙂 Nakonec jsem se rozhodl cachovat na úrovni aplikační vrstvy přímo v jednotlivých use-casech. Výhoda, že tak můžu pro každý use-case použít konfiguraci cache jaká je reálně potřeba (nebo nepoužít žádnou). Nevýhoda je, že to musím v každém use-casu řešit. 

Zvažoval jsem ještě, jestli cachovat přímo na úrovni API klienta, ale nakonec jsem se rozhodl, že z hlediska separace zodpovědnosti by se měl API klient starat pouze o získávání dat. Z pohledu návrhu aplikace je navíc správné místo, kde se má řešit optimalizace výkonu právě aplikační vrstva, nikoliv infrastrukturní.

### Stránkování na Recruitis API
Přiznám se, že tuhle část úkolu jsem si zjednodušil a rozhodl se ji neimplementovat. Endpont `/jobs` z Recruitis API totiž s mým tokenem vrací pouze jeden jediný záznam a neměl jsem úplně sílu to implementovat naslepo bez možnosti to vyzkoušet na reálných datech. Pokud to bude potřeba, tak to ale samozřejmě do implementace doplním 🙂  

### Logování, monitoring
Co jsem při vývoji vůbec neřešil je, jak, co a kdy má aplikace logovat. Defaultní logger v Symfony používá knihovnu Monolog, což je pro většinu případů ideální volba a není potřeba vymýšlet nic jiného. Pro takto malou aplikaci je z hlediska jednoduchosti ideálním nástrojem pro správu logů Betterstack (Logtail). Pro větší aplikace mám nejlepší zkušenost s klasickým ELK stackem. Na management chyb se mi vždy osvědčilo Sentry.

Frontendové chyby by se daly posílat např. přes `window.onerror` a `console.error` interceptor do backendu na vlastní endpoint `/api/log-error`, nebo rovnou do externího nástroje.

Pro pokročilejší monitorování by bylo vhodné minimálně následující metriky:
- zdraví aplikace (response time, 500 chyby, timeouty)
- počet chybových odpovědí
- dostupnost Recruitis API

Jejich hodnoty lze sbírat pomocí listeneru/middlewaru v aplikaci a následně je pak vystavit na samostatný API endpoint (např. /metrics), odkud si je může Prometheus (nebo podobný nástroj) stáhnout a následně je vizualizovat v Grafaně.
