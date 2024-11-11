# Documentation

## recuperer les femmes à enregistrer

- Method : `GET`
- Path : `http://producer.surintrants.com/api/productors/affectations/all`
- Header : 
  ```json
    {
        "Authorization": "Bearer token"
    }
  ```
- Response :
  ```json
    [
        {
            "id": 5,
            "name": "AGANZE",
            "lastname": "BAGUMA",
            "phone1": "243984978649",
            "phone2": "243984978649",
            "structure": "Association Médicale pour l'Assistance des Persosnnes Agées",
            "firstname": "Aliance",
            "sector": "Economie verte",
            "town": "Ibanda",
            "address": "Irambo II",
            "quarter": "Nyalukemba",
            "cityEntity": {
                "iri": "\/api\/cities\/28"
            },
            "agentAffect": "0000000010",
            "adminDoAffect": "0000000010",
            "affectAt": "2024-10-03T05:06:45+00:00",
            "contactAt": "2024-10-03T06:51:13+00:00",
            "contactRepport": "Cas litigé",
            "contanctComment": "INDENTITE NON CONFORME"
        },
        {
            "id": 203,
            "name": "BAHATI ",
            "lastname": "RHUNEMUNGU",
            "phone1": "991560597",
            "phone2": "892771954",
            "structure": "REGROUPEMENT VESOS (VILLAGE D'ENFANTS SOS)",
            "firstname": "TAKOBAGIRA",
            "sector": "SERVICE",
            "town": "IBANDA",
            "address": "AV. MULENGEZA 2 CEPAC HEBRONE",
            "quarter": "PANZI",
            "cityEntity": {
                "iri": "\/api\/cities\/28"
            },
            "agentAffect": "0000000010",
            "adminDoAffect": "0000000010",
            "affectAt": "2024-10-03T05:06:28+00:00",
            "contactAt": null,
            "contactRepport": null,
            "contanctComment": null
        }
    ]

  ```

## Requiperer la liste eventuel situation

- Method : `GET`
- Path : `http://producer.surintrants.com/api/productors/preload/contacts/repports`
- Header : 
  ```json
    {
        "Authorization": "Bearer token"
    }
  ```
- Response :
  ```json
    [
        "Cas litigé",
        "Rendez-vous accepté",
        "Rendez-vous reporté",
        "Autres"
    ]
  ```

## Requiperer la liste eventuel situation

- Method : `GET`
- Path : `http://producer.surintrants.com/api/productors/preload/contacts/comments`
- Header : 
  ```json
    {
        "Authorization": "Bearer token"
    }
  ```
- Response :
  ```json
    [
        null,
        "ENREGISTREE",
        "BENEFICIAIRE DE PADMPME",
        "DOUBLON",
        "FAUX NUMERO",
        "INDISPONIBLE",
        "INJOIGNABLE",
        "MINEURE",
        "NE DECROCHE PAS",
        "NE RECONNAIT PAS LE PROJET",
        "NUMERO DE TELEPHONE VIDE",
        "NUMERO INCORRECT",
        "PAS D'ACTIVITE",
        "PAS DANS LA CIBLE",
        "PAS DE CARTE",
        "PAS DE STRUCTURE",
        "PAS PRESENTE DANS LA VILLE",
        "RDV REPORTE",
        "REFUS D'ENREGISTREMENT",
        "PAS INTERESSE",
        "INDENTITE NON CONFORME"
    ]
  ```

## Rapporter la prise de contact

- Method : `POST`
- Path : `http://producer.surintrants.com/api/productors/preload/5/contacts/set`
- Header : 
  ```json
    {
        "Authorization": "Bearer token"
    }
  ```
- Body :
  ```json
    {
        "contactRepport":"Cas litigé",
        "contactComment":"INDENTITE NON CONFORME"
    }
  ```
- Response :
  ```json
    {
        "id": 5,
        "name": "AGANZE",
        "lastname": "BAGUMA",
        "phone1": "243984978649",
        "phone2": "243984978649",
        "structure": "Association Médicale pour l'Assistance des Persosnnes Agées",
        "firstname": "Aliance",
        "sector": "Economie verte",
        "town": "Ibanda",
        "address": "Irambo II",
        "quarter": "Nyalukemba",
        "cityEntity": {
            "iri": "\/api\/cities\/28"
        },
        "agentAffect": "0000000010",
        "adminDoAffect": "0000000010",
        "affectAt": "2024-10-03T05:06:45+00:00",
        "contactAt": "2024-10-03T06:51:13+00:00",
        "contactRepport": "Cas litigé",
        "contanctComment": "INDENTITE NON CONFORME"
    }
  ```