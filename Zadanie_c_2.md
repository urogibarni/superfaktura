## 2. Databázová úloha

Máte jednoduchú tabuľku s primárnym kľúčom (id) a hodnotou v druhom stĺpci (value).
Niektoré z týchto hodnôt môžu byť duplicitné. Napíšte prosím SQL query, ktoré vráti všetky
riadky z tabuľky s duplicitnými hodnotami (celé riadky).

**Definícia tabuľky a ukážkové dáta**

```sql
CREATE TABLE `duplicates` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`value` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO `duplicates` (`id`, `value`) VALUES

(1, 1),
(2, 2),
(3, 3),
(4, 2),
(5, 4),
(6, 4),
(7, 5),
(8, 6),
(9, 6),
(10, 2);
```

***Želaný výstup***

| id  | value |
| --- | ----- |
| 2   | 2     |
| 4   | 2     |
| 5   | 4     |
| 6   | 4     |
| 8   | 6     |
| 9   | 6     |
| 10  | 2     |

**Otázky na zamyslenie:**
● Bude vaše riešenie efektívne fungovať aj pre tabuľku s veľkým počtom riadkov
(napríklad milión a viac)?
● Vysvetlite prosím, prečo a ako by ste prípadne ďalej optimalizovali.



##  Vypracovanie úlohy:

- Na prvý pohľad som si zostavil nasledovný SQL dotaz:

```sql
SELECT 
    dup_1.id AS id_zaznamu, 
    dup_1.`value` AS duplicitna_hodnota
FROM duplicates AS dup_1
INNER JOIN duplicates AS dup_2 -- Spajam 1:1 (INNER) tabulky: duplicates a duplicates 
    ON dup_1.`value` = dup_2.`value` -- JOIN cez stlpec hodnot (ich duplicity hladame)
        AND dup_1.id <> dup_2.id -- vylucuje parovanie riadku so samym sebou 
                                 -- (tato podmienka moze byt presunuta aj do casti WHERE, ak je to vhodne)
GROUP BY dup_1.id    
;
```

- Výsledok mi vracia nasledovné hodnoty, ktoré zodpovedajú zadaniu:

| id_zaznamu | duplicitna_hodnota |
| ---------- | ------------------ |
| 2          | 2                  |
| 4          | 2                  |
| 5          | 4                  |
| 6          | 4                  |
| 8          | 6                  |
| 9          | 6                  |
| 10         | 2                  |

- Následne som použil **EXPLAIN** a začal optimalizovať SQL dotaz.
- Optimalizácia spočívala v odstránení použitia: _Using temporary; Using filesort_.
- Tieto dodatočné operácie SQL využíva pri použití **DISTINCT**, **ORDER BY** alebo **GROUP BY** v dopyte.
- Iteračným procesom som dospel k nasledujúcemu SQL dotazu:

```sql
SELECT 
    DISTINCT dup_1.id AS id_zaznamu, 
    dup_1.`value` AS duplicitna_hodnota
FROM duplicates AS dup_1
INNER JOIN duplicates AS dup_2
    ON dup_1.`value` = dup_2.`value`
        AND dup_1.id <> dup_2.id
;
```

| id_zaznamu | duplicitna_hodnota |
| ---------- | ------------------ |
| 4          | 2                  |
| 10         | 2                  |
| 2          | 2                  |
| 6          | 4                  |
| 5          | 4                  |
| 9          | 6                  |
| 8          | 6                  |

- Síce som nedostal zoradený výsledok podľa zadania, ale podarilo sa mi eliminovať _Using filesort_.
- Tento upravený dotaz využíva už len _Using temporary_.
- Následne sa pokúsim odstrániť aj **DISTINCT** a dokonca aj **JOIN**, čím sa dostávam k nasledujúcemu SQL dotazu:

```sql
SELECT 
    dup_1.id AS id_zaznamu, 
    dup_1.`value` AS duplicitna_hodnota
FROM duplicates AS dup_1
WHERE EXISTS ( -- podmienim vysledok so sub query s duplicitnou hodnotou
    SELECT 1
    FROM duplicates AS dup_2
    WHERE dup_1.`value` = dup_2.`value` -- spajam hlavnu a sub poziadavku cez stlpec hodnot (ich duplicity hladame)
        AND dup_1.id <> dup_2.id -- vylucujem naparovanie riadku sameho seba
);
```

| id_zaznamu | duplicitna_hodnota |
| ---------- | ------------------ |
| 2          | 2                  |
| 4          | 2                  |
| 5          | 4                  |
| 6          | 4                  |
| 8          | 6                  |
| 9          | 6                  |
| 10         | 2                  |

- V tomto prípade nepoužívam ani **JOIN**, ani **GROUP BY** (ani **ORDER BY**).

- Úplne som eliminoval **Using temporary** aj **Using filesort**.

- Duplikátne záznamy sú filtrované priamo pomocou **WHERE** podmienok.

### + **Bonusová verzia SQL** ;-)

- Výstup je síce odlišný, ale úplne bez **JOIN**-u.

```sql
SELECT dup_1.`value` AS duplicitna_hodnota, 
    CONCAT('[',  GROUP_CONCAT(DISTINCT dup_1.id SEPARATOR  ', '), ']') AS duplicity_na_riadkoch
FROM duplicates AS dup_1
GROUP BY dup_1.`value`
HAVING COUNT(dup_1.`value`) > 1 -- pocet `value` je viac ako 1 -> teda: duplicita, triplicita atd...
;
```

| duplicitna_hodnota | duplicity_na_riadkoch |
| ------------------ | --------------------- |
| 2                  | [2, 4, 10]            |
| 4                  | [5, 6]                |
| 6                  | [8, 9]                |

- Tento prístup má však svoje **limity** a nie je vhodný pre veľké tabuľky.

- Využíva **Using filesort**, čo môže ovplyvniť výkon.

- Do výstupného stĺpca _duplicity_na_riadkoch_ sa nemusia zmestiť všetky **ID** riadky, kde sa daná duplicita vyskytuje.
  
  
  
  

**Ďalšie optimalizácie by bolo možné vykonať na úrovni samotnej tabuľky**, napríklad:

- **Partitioning** tabuľky
- Zavedenie **indexu** na stĺpci _value_
