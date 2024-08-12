BESTAURANT

Az app leírása:
 Ez az web app arra lenne való, hogy ha egy felhasználó beregisztrál és fizet érte akkor kap egy admin felületet és egy tokent.
 Az admin felület az alapján lesz kigenerálva ahogy a felhasználó beregisztrálta éttermét, fodrászatát, masszázs szalonját, kozmetikáját , de egyenlőre most az éttermet.
 Az app feladat:
    -Adott egység regisztrációja,
    -Egység foglalásának és nyitvatartásának nyilvántartása, irányítása

A felhasználó beírja a nevét
Telefonszámát
e-mail címét 

 A felhasználó beregisztrálja éttermét:
    - Étterem nevét,
    - Nyitvatartási idejét:
    - Asztalokat, (Asztal száma, hány fős(KAPACITÁS))
    

Utolsónak Kiválaszthatja hogy most fizet, vagy 30 napos próbát nyomat




Restaurant -> Files:
    Controllers:
        - UserController
        - RestaurantController
        - TableController
        - OpeningController
        

    Models:
        - UserModel
        - RestaurantModel
        - TableModel
        - OpeningModel
        - CosmeticsModel
        - HairDresserModel
        - ServicesModel



User folyamat:
    - User fellép az oldalra, beirja adatait , kiválasztja a kozmetikát 
    - Lefut a cosmetic Controller register modelj
    - Felviszi adatbázisba az adatokat.
    - Felviszi hogy fizetve van-e már vagy 14 napos próba verziót kér
    - Megkapja emailben a belépési adatokat és a tokent
    - Az adatbázisba felmentődik az is hogy generáláskor melyik views-et válassza ki majd frontendről
    - Amikor a user belép az adminfelületre a tokennel és a jelszavával meg mindennel azonosítja magát,   
    - Az adatbázis megküldi az adatokat és hogy melyik mappa tartalma renderelődjön 
