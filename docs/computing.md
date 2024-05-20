# Documentation

## get token
- method : `Post`
- host : `producer.surintrants.com`
- path : `/api/login`
- Body :
  ```json
    {
        "username":"phoneNumber",
        "password":"password"
    }

  ```
  
- headers :
  ```json
    {
        "Content-type":"application/json",
        "Accept":"application/json",
    }

  ```

  - response :
  ce qui va nous concerner est la proprieté `access_token` qui correspond au token de l'utilisateur
  ```json
    {
      "name": "Ndongala",
      "last_name": "Ndongala",
      "first_name": "Michée",
      "phone": "0824019836",
      "email": "micheenkusu@agromwinda.com",
      "roles": [
        "ROLE_ADMIN",
        "ROLE_USER",
        "ROLE_DIRECTOR",
        "ROLE_VOUCHER_ADMIN",
        "ROLE_HELP_DESK",
        "ROLE_INVESTIGATOR",
        "ROLE_VOUCHER_VALIDATOR",
        "ROLE_ANALYST",
        "ROLE_STAFF",
        "ROLE_INVESTIGATION",
        "ROLE_FOLLOWED_RECORDING",
        "ROLE_VALIDATOR",
        "ROLE_AGENT",
        "ROLE_VOUCHER_INITIATOR",
        "ROLE_VOUCHER_COORDINATOR",
        "ROLE_SUBSCRIBER",
        "ROLE_AM",
        "ROLE_ALLOWED_TO_SWITCH",
        "ROLE_VOUCHER_SUPERVISOR",
        "ROLE_VOUCHER_RECEIVER"
      ],
      "my_op_metadata": [],
      "api_token": "",
      "token_type": "Bearer",
      "expires_in": 31536000,
      "access_token": "{access_token}"
    }
  ```

## get beneficiaries
- method : `Get`
- host : `producer.surintrants.com`
- path : `/api/productors?page={page}`
  ```
    avec {page} qui represente entier qui est le nomber de page (1,2,3, etc.)

  ```
- headers :
  ```json
    {
        "Authorization":"Bearer {token}",
        "Content-type":"application/json",
        "Accept":"application/json",
    }

  ```
  ```
    avec {token} qui represente une chaine de caractère (un String) qui est le token

  ```

  - response :
  ça retourne un map qui contient 3 propriétés (data, totalItems, lastPage).
  Avec 
    data : les données proprement dites
    totalItems : le nombre totals des données
    lastPage : la derniere page

    le propriété `data` contient au plus 30 enregistrements selon le page. chaque donné correspond un map. ce map a plusièur propriétés.
    Nous selon nous focalisez sur la propriété `data.documents` pour les photos de l'activité et `data.images` pour les photos de la dame.
    Pour les photos de l'activité nous nous focalisez precisement sur les sous propriété `data.documents.entrepreneurialActivities[0].documents` qui est un tableau des maps. ces maps correspondant à une photo de l'activité. chaque map de `data.documents.entrepreneurialActivities[0].documents` possede 
    `path` qui est lien de la photo vers notre stockage dans le cloud. 

    comme exemple du contenu de la propriété `path` : `https://storage.cloud.google.com/agromwinda_platform/0976701874-product-0.png`.

    ce qui va nous concerner est la derniere partie de ce contenu. Pour l'exemple ci-haut c'est `0976701874-product-0.png`. Que nous allons coller au 
    prefix `https://pic.surintrants.com/api/productors/photo/show/` pour recupére le lieux qui nous permet facilement de récupérer l'image simplement.

    Donc le contenu de `path` `https://storage.cloud.google.com/agromwinda_platform/0976701874-product-0.png` va correspondre à 
     `https://pic.surintrants.com/api/productors/photo/show/0976701874-product-0.png`

    Pour rappele, les sous propriété `data.documents.entrepreneurialActivities[0].documents` contient plusièurs map que nous allons convertir pour recuperer les image.

    Ci-bas un example d'une reponse :
    ```json
                

    ```
 ## set description

 ## get token
- method : `Post`
- host : `producer.surintrants.com`
- path : `/api/productors/{id}/change/preprocessing/ia`
  avec `{id}` l'identifiant du producteur
- Body :
  ```json
    {
        "desc":"la description....",
        "activitySector":"secteur d'activité"
    }

  ```
  
- headers :
  ```json
    {
        "Authorization":"Bearer {token}",
        "Content-type":"application/json",
        "Accept":"application/json",
    }

  ```

  - response :
  json qui reprensente le corps du map de producteur
  ```json

	"data": {
      ...
		  "id": 91,
		  "isActive": false,
    	"aiDesc": "la description....",
		  "aiActivitySector": "secteur d'activité",
      ...
    }
    
  ```