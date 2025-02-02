### **3. PHP úloha**

Napíšte prosím **jednoduchú knižnicu (ucelený blok kódu)** na načítanie údajov firiem z českého registra spoločností. Nechávame na vás, **aký formát vrátených dát** zvolíte, no odporúčame myslieť na ich **ďalšie spracovanie** a vybrať **vhodnú** formu.  
Táto úloha je zameraná na **kvalitu kódu**, aby sme videli, ako:

* ošetrujete **neplatné alebo chýbajúce vstupy** (napr. neexistujúce IČO),
* riešite **chybové stavy** (error handling),
* dodržiavate **best practices** (čitateľnosť, princípy SOLID, štruktúru, testovateľnosť).

**Dostupný endpoint (Ares – český register spoločností):**  

* https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty/{ICO}

**Príklad volania:**  

* https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty/01569651
  
  

### **Požiadavky na riešenie:**

* **PHP 8+**

* **Ošetrenie vstupov** (validácia IČO, práca s chýbajúcim alebo neplatným vstupom)

* **Chybové hlášky** (error handling)

* **Čitateľnosť a testovateľnosť kódu** (dokumentácia, prípadne aj krátka ukážka automatických testov)

* **Zohľadnenie SOLID princípov** (aby bol kód dobre udržiavateľný a rozšíriteľný)

### **Na čo si dať pozor:**

Najčastejšie chyby sa pri riešení tejto úlohy objavujú **v slabom dodržiavaní SOLID princípov**, v **nedostatočnej testovateľnosti** a v **neprehľadnej štruktúre kódu**.  
Odporúčame preto venovať týmto oblastiam zvýšenú pozornosť, aby ste predišli bežným nedostatkom a predviedli, ako kvalitný a udržiavateľný kód dokážete vytvoriť.



## Vypracovanie úlohy:

* Vypracované zadanie je dostupné na URL adrese: **[ARES-CompanyDataFetcher](https://github.com/urogibarni/AresClient/tree/develop)**.
  
  * Knižnica popisuje základné fungovanie a implementáciu, vrátane príkladov použitia
