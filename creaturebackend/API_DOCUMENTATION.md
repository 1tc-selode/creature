# KÜLKAT API Dokumentáció

## Bevezető
A KÜLKAT (Különleges Lények Katasztere) API egy RESTful webszolgáltatás, amely lehetővé teszi különleges lények nyilvántartását, kezelését és a hozzájuk kapcsolódó információk elérését.

## Alap URL
```
http://localhost:8000/api
```

## Authentikáció
Az API Bearer token alapú authentikációt használ Laravel Sanctum segítségével.

### Token beszerzése:
```http
POST /login
```

**Request Body:**
```json
{
    "email": "admin@külkat.hu",
    "password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Sikeres bejelentkezés",
    "data": {
        "user": {
            "id": 1,
            "name": "KÜLKAT Admin",
            "email": "admin@külkat.hu"
        },
        "token": "1|abcd1234...",
        "token_type": "Bearer"
    }
}
```

### Token használata:
```http
Authorization: Bearer 1|abcd1234...
```

## API Végpontok

### 1. Authentikáció

#### POST /login
Bejelentkezés és token megszerzése.

#### POST /logout
Kijelentkezés (token érvénytelenítése).
- **Authentikáció:** Kötelező

#### GET /user
Aktuális felhasználó adatai.
- **Authentikáció:** Kötelező

---

### 2. Lények (Creatures)

#### GET /creatures
Lények listázása szűrőkkel és lapozással.

**Query Parameters:**
- `kategoria_id` (integer): Kategória szerinti szűrés
- `meret` (string): Méret szerinti szűrés
- `veszelyesseg` (string): Veszélyesség szerinti szűrés
- `allapot` (string): Állapot szerinti szűrés
- `search` (string): Keresés a név, leírás, élőhely mezőkben
- `per_page` (integer): Elemek száma oldalanként (alapértelmezett: 15)

**Response:**
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "nev": "Teleportáló Teve",
                "tudomanyos_nev": "Camelus teleporticus",
                "leiras": "...",
                "meret": "nagy",
                "veszelyesseg": "alacsony",
                "kategoria": {
                    "id": 1,
                    "nev": "Mágikus Lények"
                },
                "kepessegek": [...],
                "galeriakepek": [...]
            }
        ],
        "current_page": 1,
        "total": 50
    },
    "filters": {
        "kategoriak": [...],
        "meretek": [...],
        "veszelyessegek": [...],
        "allapotok": [...]
    }
}
```

#### GET /creatures/{id}
Egy lény részletes adatai.

#### POST /creatures
Új lény létrehozása.
- **Authentikáció:** Kötelező

**Request Body:**
```json
{
    "nev": "Új Lény",
    "leiras": "Részletes leírás...",
    "kategoria_id": 1,
    "meret": "közepes",
    "veszelyesseg": "alacsony",
    "ritkasag": 5,
    "elohely": "Erdők",
    "kepessegek": [1, 3, 5]
}
```

#### PUT /creatures/{id}
Lény adatainak frissítése.
- **Authentikáció:** Kötelező
- **Jogosultság:** Csak a lény tulajdonosa

#### DELETE /creatures/{id}
Lény törlése.
- **Authentikáció:** Kötelező
- **Jogosultság:** Csak a lény tulajdonosa

#### GET /categories
Elérhető kategóriák listája.

---

### 3. Képességek (Abilities)

#### GET /abilities
Képességek listázása.

**Query Parameters:**
- `tipus` (string): Típus szerinti szűrés (fizikai, mágikus, mentális, speciális)

#### POST /creatures/{creatureId}/abilities
Képesség hozzáadása lényhez.
- **Authentikáció:** Kötelező

**Request Body:**
```json
{
    "kepesseg_id": 1,
    "szint": 5,
    "megjegyzes": "Természetes képesség"
}
```

#### PUT /creatures/{creatureId}/abilities/{abilityId}
Lény képességének szintjének frissítése.
- **Authentikáció:** Kötelező

#### DELETE /creatures/{creatureId}/abilities/{abilityId}
Képesség eltávolítása lénytől.
- **Authentikáció:** Kötelező

---

### 4. Galéria (Gallery)

#### GET /creatures/{creatureId}/gallery
Lény galériájának megtekintése.

#### POST /creatures/{creatureId}/gallery
Kép feltöltése a galériába.
- **Authentikáció:** Kötelező
- **Content-Type:** multipart/form-data

**Request Body:**
```
image: [file] (kötelező, max 5MB, jpeg/png/jpg/gif/webp)
leiras: [string] (opcionális)
fo_kep: [boolean] (opcionális)
sorrend: [integer] (opcionális)
```

#### PUT /creatures/{creatureId}/gallery/{imageId}
Kép adatainak frissítése.
- **Authentikáció:** Kötelező

#### DELETE /creatures/{creatureId}/gallery/{imageId}
Kép törlése.
- **Authentikáció:** Kötelező

#### PUT /creatures/{creatureId}/gallery/order
Képek sorrendjének frissítése.
- **Authentikáció:** Kötelező

---

### 5. Kapcsolatfelvétel (Contact)

#### POST /contact
Kapcsolati üzenet küldése (nyilvános).

**Request Body:**
```json
{
    "nev": "Felhasználó Név",
    "email": "user@example.com",
    "telefon": "+36 1 234 5678",
    "targy": "Üzenet tárgya",
    "uzenet": "Üzenet szövege...",
    "tipus": "általános",
    "leny_id": null
}
```

#### GET /contact
Kapcsolati üzenetek listázása (admin).
- **Authentikáció:** Kötelező

**Query Parameters:**
- `allapot` (string): Állapot szerinti szűrés
- `tipus` (string): Típus szerinti szűrés  
- `search` (string): Keresés
- `per_page` (integer): Elemek száma oldalanként

#### GET /contact/{id}
Kapcsolati üzenet részletei (admin).
- **Authentikáció:** Kötelező

#### PUT /contact/{id}/status
Üzenet állapotának frissítése (admin).
- **Authentikáció:** Kötelező

**Request Body:**
```json
{
    "allapot": "feldolgozás_alatt",
    "admin_megjegyzes": "Admin megjegyzés..."
}
```

#### GET /contact/stats
Kapcsolati üzenetek statisztikái (admin).
- **Authentikáció:** Kötelező

#### GET /creatures/{creatureId}/messages
Lényhez kapcsolódó üzenetek.
- **Authentikáció:** Kötelező

---

## Hibakezelés

Az API konzisztens hibaformátumot használ:

```json
{
    "success": false,
    "message": "Hibaüzenet",
    "errors": {
        "field_name": ["Validációs hibaüzenet"]
    }
}
```

### HTTP státuszkódok:
- `200` - OK (sikeres kérés)
- `201` - Created (sikeres létrehozás)
- `400` - Bad Request (hibás kérés)
- `401` - Unauthorized (nincs authentikáció)
- `403` - Forbidden (nincs jogosultság)
- `404` - Not Found (nem található)
- `422` - Unprocessable Entity (validációs hiba)
- `500` - Internal Server Error (szerver hiba)

## Adattípusok

### Lény mezők:
- `meret`: 'apró', 'kicsi', 'közepes', 'nagy', 'hatalmas', 'gigantikus'
- `veszelyesseg`: 'ártalmatlan', 'alacsony', 'közepes', 'magas', 'extrém'
- `allapot`: 'aktív', 'inaktív', 'kivizsgálás_alatt', 'eltűnt'
- `ritkasag`: 1-10 közötti egész szám

### Képesség típusok:
- `tipus`: 'fizikai', 'mágikus', 'mentális', 'speciális'

### Kapcsolati üzenet típusok:
- `tipus`: 'általános', 'lény_bejelentés', 'hiba_jelentés', 'javaslat', 'egyéb'
- `allapot`: 'új', 'feldolgozás_alatt', 'válaszolt', 'lezárt'

## Rate Limiting
Az API alapértelmezés szerint 60 kérést engedélyez percenként authentikált felhasználók számára.

## CORS
A CORS be van állítva a frontend alkalmazás számára.

---

**Utolsó frissítés:** 2025-12-10  
**API verzió:** 1.0  
**Laravel verzió:** 12.x
