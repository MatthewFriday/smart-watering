# Smart-Watering

A kertészektől állandó odafigyelést igényel a megfelelő öntözés biztosítása, különösen a nyári időszakban. Ennek megkönnyítésére szolgál a **Smart-Watering** rendszer, amely eltárolja a szenzorok által mért adatokat, ezeket képes ábrázolni, valamint az adatok felhasználásával szabályrendszer alapján öntözést indít.

## Telepítés

1. A git repo klónozása
```
git clone https://github.com/MatthewFriday/smart-watering.git
```
2. Telepítő script futtatása
	> **Figyelem!** A script telepíteni fogja a szükséges függőségeket, valamint egy apache2 webszervert és egy MySQL adatbázis szervert. A script egy friss telepítést feltételez.
```
sudo bash smart-watering/install.sh
```
3. Weboldal elérése
http://localhost/ vagy http://eszkoz.ip.cime/

## Képernyőképek
### Aktuális állapot megtekintése
![Aktuális állapot](/pictures/overview.jpg)

### Mérési adatok megjelenítése
![Mérési adatok](/pictures/vizualization.jpg)

### Öntözési szabályok meghatározása
![Öntözési szabályok](/pictures/rules.jpg)

### Pushover értesítések beállítása
![Értesítések](/pictures/notification.jpg)

## Felhasznált 3rd-party projektek

- **[grove.py](https://github.com/Seeed-Studio/grove.py) by Seeed-Studio**: Python library a SeeedStudio Grove eszközeihez [MIT]
- **[Seeed_Python_DHT](https://github.com/Seeed-Studio/Seeed_Python_DHT) by Seeed-Studio**: Python library a DHT szenzor olvasásához [MIT]
- **[python-pushover](https://github.com/Thibauth/python-pushover) by Thibauth**: Python library a Pushover szolgáltató API-jával való kommunikációhoz [GPL-3.0]
- **[mysql-connector-python](https://dev.mysql.com/doc/connector-python/en/) by Oracle and/or its affiliates**: MySQL driver Python-hoz [GPL-2.0]