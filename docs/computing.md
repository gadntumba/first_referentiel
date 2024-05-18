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
    {
	"data": [
		{
			"id": 91,
			"latitude": -2.500756,
			"longitude": 28.8728225,
			"altitude": 1513.0999755859,
			"isActive": false,
			"instigator": {
				"id": 36,
				"name": "test@ZAINA ",
				"firstname": "ZAZA",
				"lastname": "NGAKANI",
				"phoneNumber": "0819450570",
				"location": []
			},
			"validateAt": "2024-05-01T08:45:30+00:00",
			"feedBack": null,
			"personnalIdentityData": {
				"name": "FURAHA ",
				"firstName": "DORCAS ",
				"lastName": "MAYANGE ",
				"sexe": "F",
				"phoneNumber": "0973707823",
				"birthdate": "1989-05-05T00:00:00+00:00",
				"nui": "220415228",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 9,
				"organization": {
					"name": "ASAD",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "NN 33640539242",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "Umoja",
						"creationYear": 2011,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "saio",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/80",
						"otherData": {
							"desc": "coupe et couture",
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "0",
							"sectorServices": "1",
							"sectorGreeEconomy": "0",
							"otherActivitySector": null,
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "",
							"affiliationStructure": "ASAD ",
							"turneOverAmount": "6300",
							"journalierStaff": "6",
							"pernanentStaff": "6",
							"familyStaff": "0",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": null,
							"trainningDificuty": null,
							"financingDificuty": null,
							"tracaserieDificuty": null,
							"marketAccessDificuty": null,
							"productionDificuty": null,
							"otherDificuty": null,
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "0",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "0",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "0",
							"visionOther": "",
							"otherContectNames": "PAPA JANVIER ",
							"otherContectPhoneNumber": "0974387732",
							"otherContectAddress": "IRAMBO II ",
							"instigatorOpinion": "potentiel"
						}
					},
					{
						"name": "ALAIN COUTURE ",
						"creationYear": 2017,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "IRAMBO II ",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": null,
							"25": null,
							"26": null,
							"27": null,
							"28": null,
							"29": null,
							"30": null,
							"31": "0",
							"32": "0",
							"33": "0",
							"34": "",
							"35": "1",
							"36": "0",
							"37": "0",
							"38": null,
							"39": "1",
							"40": "",
							"41": "0",
							"42": "1",
							"43": "0",
							"44": "1",
							"45": "0",
							"46": ""
						},
						"activities": {
							"0": "coupe et couture",
							"2": "Mariée",
							"4": "Personne physique",
							"5": "0",
							"6": "0",
							"7": "1",
							"8": "0",
							"9": null,
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "",
							"15": "ASAD ",
							"16": "6300",
							"17": "6",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "1",
							"40": "",
							"41": "0",
							"42": "1",
							"43": "0",
							"44": "1",
							"45": "0",
							"46": "",
							"47": "PAPA JANVIER ",
							"48": "0974387732",
							"49": "IRAMBO II ",
							"50": "potentiel",
							"55": "6",
							"56": "0"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/57120"
					}
				]
			},
			"housekeeping": {
				"id": 91,
				"NIM": "646731822",
				"reference": null,
				"address": {
					"id": 57138,
					"line": "IRAMBO II ",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/91"
			},
			"timestamp": {
				"createdAt": "2024-02-17T08:17:53+00:00",
				"updatedAt": "2024-05-16T09:16:46+00:00",
				"slug": "1eecd6d0-bfa1-614a-9338-c3e227c0fd69"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973707823-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973707823-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 67,
								"path": "rccm-65d06bb309e52.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/67"
							},
							{
								"id": 68,
								"path": "f92-65d06bb3a53d9.",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/68"
							},
							{
								"id": 69,
								"path": "statut-65d06bb44bc15.",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/69"
							},
							{
								"id": 147583,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973707823-product-1.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/147583"
							},
							{
								"id": 147592,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973707823-activity-1.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/147592"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/80"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/57120"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973707823-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973707823-profil.png"
		},
		{
			"id": 105,
			"latitude": -5.8333599,
			"longitude": 13.4558483,
			"altitude": 42.8,
			"isActive": null,
			"instigator": {
				"id": 43,
				"name": "Buasa",
				"firstname": "Princilia",
				"lastname": "Buasa",
				"phoneNumber": "0812781690",
				"location": []
			},
			"validateAt": null,
			"feedBack": {
				"desc": "Pas de structure d’affiliation et photos de l’activité non appropriées.",
				"noStructure": true,
				"photoActivityInvisible": true
			},
			"personnalIdentityData": {
				"name": "Ngengo ",
				"firstName": "Odette ",
				"lastName": "Masaka",
				"sexe": "F",
				"phoneNumber": "0976701874",
				"birthdate": "1962-07-02T00:00:00+00:00",
				"nui": "452843700",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 6,
				"organization": {
					"name": "synergie des jeunes entrepreneurs de matadi",
					"city": {
						"iri": "\/api\/cities\/11"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "30516350437",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "grâce business ",
						"creationYear": 2018,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "fgdhh",
						"town": {
							"id": 32,
							"name": "Matadi",
							"city": {
								"id": 11,
								"name": "Matadi",
								"province": {
									"id": 11,
									"name": "Kongo-Central",
									"iri": "\/api\/location\/provinces\/11"
								},
								"iri": "\/api\/cities\/11"
							},
							"iri": "\/api\/towns\/32"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 2014,
						"iri": "\/api\/entrepreneurial_activities\/94",
						"otherData": {
							"desc": "pâtisserie ",
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "0",
							"sectorServices": "1",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "fabrication des gâteaux, beignets ",
							"affiliationStructure": "synergie des jeunes ",
							"turneOverAmount": "1600",
							"journalierStaff": "0",
							"pernanentStaff": "0",
							"familyStaff": "2",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "0",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "0",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "0",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "0",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "0",
							"visionOther": "",
							"otherContectNames": "Sarah Ngimbi ",
							"otherContectPhoneNumber": "0844377953",
							"otherContectAddress": "de la plaine 30\/ kitomesa\/nzanza ",
							"instigatorOpinion": "potentiel"
						}
					},
					{
						"name": "Esther business ",
						"creationYear": 2018,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "gffhv",
						"town": {
							"id": 32,
							"name": "Matadi",
							"city": {
								"id": 11,
								"name": "Matadi",
								"province": {
									"id": 11,
									"name": "Kongo-Central",
									"iri": "\/api\/location\/provinces\/11"
								},
								"iri": "\/api\/cities\/11"
							},
							"iri": "\/api\/towns\/32"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "0",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "0",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Mariée",
							"4": "",
							"5": "0",
							"6": "1",
							"7": "0",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "pâtisserie ",
							"15": "rdf",
							"16": "5800",
							"17": "0",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "0",
							"40": "",
							"41": "1",
							"42": "0",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "Mavuanda Fidèle ",
							"48": "0856368524",
							"49": "av: huxxf",
							"50": "",
							"55": "0",
							"56": "0"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/598"
					},
					{
						"name": "mère Oda",
						"creationYear": 2022,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "de la plaine 30 ",
						"town": {
							"id": 33,
							"name": "Nzanza",
							"city": {
								"id": 11,
								"name": "Matadi",
								"province": {
									"id": 11,
									"name": "Kongo-Central",
									"iri": "\/api\/location\/provinces\/11"
								},
								"iri": "\/api\/cities\/11"
							},
							"iri": "\/api\/towns\/33"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "0",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "0",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"0": "pâtisserie ",
							"2": "Mariée",
							"4": "Personne physique",
							"5": "0",
							"6": "0",
							"7": "1",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "fabrication des gâteaux, beignets ",
							"15": "synergie des jeunes ",
							"16": "1600",
							"17": "0",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "0",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "0",
							"44": "1",
							"45": "0",
							"46": "",
							"47": "Sarah Ngimbi ",
							"48": "0844377953",
							"49": "de la plaine 30\/ kitomesa\/nzanza ",
							"50": "potentiel",
							"55": "0",
							"56": "2"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/43606"
					}
				]
			},
			"housekeeping": {
				"id": 105,
				"NIM": "341274054",
				"reference": null,
				"address": {
					"id": 43623,
					"line": "de la plaine 30",
					"town": {
						"id": 33,
						"name": "Nzanza",
						"city": {
							"id": 11,
							"name": "Matadi",
							"province": {
								"id": 11,
								"name": "Kongo-Central",
								"iri": "\/api\/location\/provinces\/11"
							},
							"iri": "\/api\/cities\/11"
						},
						"iri": "\/api\/towns\/33"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/105"
			},
			"timestamp": {
				"createdAt": "2024-02-17T09:43:00+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eecd78f-04a5-6250-bf0c-d3ea137c62ff"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0976701874-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0976701874-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 1047,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0976701874-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1047"
							},
							{
								"id": 1048,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0976701874-activity-0.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/1048"
							},
							{
								"id": 115945,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0976701874-product-1.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/115945"
							},
							{
								"id": 115946,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0976701874-product-2.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/115946"
							},
							{
								"id": 115947,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0976701874-activity-1.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/115947"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/94"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/598"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/43606"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0976701874-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0976701874-profil.png"
		},
		{
			"id": 130,
			"latitude": -4.3423423423423,
			"longitude": 15.269055798795,
			"altitude": 0,
			"isActive": null,
			"instigator": {
				"id": 8,
				"name": "Zola",
				"firstname": "Beaudry",
				"lastname": "Mafuta",
				"phoneNumber": "0816215422",
				"location": []
			},
			"validateAt": "2024-04-30T08:17:55+00:00",
			"feedBack": {
				"desc": "La carte n'est pas lisible"
			},
			"personnalIdentityData": {
				"name": "KALAMAY",
				"firstName": "THESIANE ",
				"lastName": "NSAMA",
				"sexe": "F",
				"phoneNumber": "0841027972",
				"birthdate": "1993-04-04T00:00:00+00:00",
				"nui": "558572703",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 4,
				"organization": {
					"name": "ONG CPC",
					"city": {
						"iri": "\/api\/cities\/10"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "30032526451",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "ASBL TZ",
						"creationYear": 2015,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "474 Tuidi, Makelele, Bandalungwa ",
						"town": {
							"id": 8,
							"name": "Bandalungwa",
							"city": {
								"id": 10,
								"name": "kinshasa",
								"province": {
									"id": 10,
									"name": "Kinshasa",
									"iri": "\/api\/location\/provinces\/10"
								},
								"iri": "\/api\/cities\/10"
							},
							"iri": "\/api\/towns\/8"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/119",
						"otherData": {
							"desc": "INDUSTRIE LÉGÈRE ",
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "1",
							"sectorServices": "0",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "SAVONNERIE ",
							"affiliationStructure": "ONG CPC",
							"turneOverAmount": "10250",
							"journalierStaff": "2",
							"pernanentStaff": "3",
							"familyStaff": "1",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "0",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "1",
							"otherDificuty": "BESOIN D'UN LOCAL SPACIEUX, DES CITERNES, UNE MOTO TRICYCLE POUR LA DISTRIBUTION, UN LAPTOP ET UNE IMPRIMANTE POUR ÉTIQUETTE, DES CARTOUCHES, BIDONS 25L D'HUILE DE PALME, SOAP CUT MACHINE, SOAP STAMPLING MACHINE",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "1",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "0",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "0",
							"visionOther": "",
							"otherContectNames": "GYNIA MOLEBE",
							"otherContectPhoneNumber": "0986811224",
							"otherContectAddress": "BLVD LUMUMBA 15\/KINGASANI\/MASINA",
							"instigatorOpinion": "mature"
						}
					},
					{
						"name": "THESY GROUPE",
						"creationYear": 2018,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "TUIDI 474\/MAKELELE\/BANDALUNGWA ",
						"town": {
							"id": 8,
							"name": "Bandalungwa",
							"city": {
								"id": 10,
								"name": "kinshasa",
								"province": {
									"id": 10,
									"name": "Kinshasa",
									"iri": "\/api\/location\/provinces\/10"
								},
								"iri": "\/api\/cities\/10"
							},
							"iri": "\/api\/towns\/8"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "0",
							"25": "1",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "1",
							"30": "BESOIN D'UN LOCAL SPACIEUX, DES CITERNES, UNE MOTO TRICYCLE POUR LA DISTRIBUTION, UN LAPTOP ET UNE IMPRIMANTE POUR ÉTIQUETTE, DES CARTOUCHES, BIDONS 25L D'HUILE DE PALME, SOAP CUT MACHINE, SOAP STAMPLING MACHINE",
							"31": "0",
							"32": "1",
							"33": "0",
							"34": ""
						},
						"activities": {
							"0": "INDUSTRIE LÉGÈRE ",
							"2": "Mariée",
							"4": "Personne physique",
							"5": "0",
							"6": "1",
							"7": "0",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "SAVONNERIE ",
							"15": "ONG CPC",
							"16": "10250",
							"17": "2",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "1",
							"40": "",
							"41": "0",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "0",
							"46": "",
							"47": "GYNIA MOLEBE",
							"48": "0986811224",
							"49": "BLVD LUMUMBA 15\/KINGASANI\/MASINA",
							"50": "mature",
							"55": "3",
							"56": "1"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/53736"
					}
				]
			},
			"housekeeping": {
				"id": 130,
				"NIM": "315016471",
				"reference": null,
				"address": {
					"id": 53754,
					"line": "TUIDI 474\/MAKELELE\/BANDALUNGWA ",
					"town": {
						"id": 8,
						"name": "Bandalungwa",
						"city": {
							"id": 10,
							"name": "kinshasa",
							"province": {
								"id": 10,
								"name": "Kinshasa",
								"iri": "\/api\/location\/provinces\/10"
							},
							"iri": "\/api\/cities\/10"
						},
						"iri": "\/api\/towns\/8"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/130"
			},
			"timestamp": {
				"createdAt": "2024-02-17T10:24:30+00:00",
				"updatedAt": "2024-05-06T10:44:54+00:00",
				"slug": "1eecd7eb-c4ee-6262-a22f-89d54f78da5c"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0841027972-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0841027972-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 139957,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0841027972-product-1.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/139957"
							},
							{
								"id": 139958,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0841027972-activity-1.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/139958"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/119"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/53736"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0841027972-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0841027972-profil.png"
		},
		{
			"id": 141,
			"latitude": -4.3008198,
			"longitude": 15.310133,
			"altitude": 278.89999389648,
			"isActive": null,
			"instigator": {
				"id": 58,
				"name": "test@betuku ",
				"firstname": "euphragie ",
				"lastname": "Mbelempo ",
				"phoneNumber": "0828486296",
				"location": []
			},
			"validateAt": "2024-05-04T10:59:51+00:00",
			"feedBack": {
				"desc": "tous semble bien il n'y a que des photos qui ne sont pas approprié",
				"photoProfileInvisible": true,
				"photoActivityInvisible": true
			},
			"personnalIdentityData": {
				"name": "Munga ",
				"firstName": "benjamine ",
				"lastName": "ramazani ",
				"sexe": "F",
				"phoneNumber": "0830088997",
				"birthdate": "2006-01-01T00:00:00+00:00",
				"nui": "730011644",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 8,
				"organization": {
					"name": "ongd rehema",
					"city": {
						"iri": "\/api\/cities\/10"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "3334455775",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "biotech",
						"creationYear": 2022,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "tubal 2 cité verte ",
						"town": {
							"id": 30,
							"name": "Nsele",
							"city": {
								"id": 10,
								"name": "kinshasa",
								"province": {
									"id": 10,
									"name": "Kinshasa",
									"iri": "\/api\/location\/provinces\/10"
								},
								"iri": "\/api\/cities\/10"
							},
							"iri": "\/api\/towns\/30"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/130",
						"otherData": {
							"desc": null,
							"stateMarital": "Celibataire",
							"otherIDCard": null,
							"legalStatus": "Société commerciale",
							"sectorAgroForestry": "0",
							"sectorIndustry": "1",
							"sectorServices": "1",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "fabrication des briques ",
							"affiliationStructure": "ongd rehema ",
							"turneOverAmount": "150",
							"journalierStaff": "6",
							"pernanentStaff": "13",
							"familyStaff": "3",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "1",
							"marketAccessDificuty": "1",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "1",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "safi assumani",
							"otherContectPhoneNumber": "0825793368",
							"otherContectAddress": "kanga 3 cité verte, mont ngafula ",
							"instigatorOpinion": "promoteuse"
						}
					},
					{
						"name": "business woman",
						"creationYear": 2023,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "mvuemba n°2, cité pumbu ",
						"town": {
							"id": 25,
							"name": "Mont-Ngafula",
							"city": {
								"id": 10,
								"name": "kinshasa",
								"province": {
									"id": 10,
									"name": "Kinshasa",
									"iri": "\/api\/location\/provinces\/10"
								},
								"iri": "\/api\/cities\/10"
							},
							"iri": "\/api\/towns\/25"
						},
						"territory": null,
						"taxes": {
							"0": "salongo ",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "1",
							"28": "1",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "1",
							"34": ""
						},
						"activities": {
							"2": "Celibataire",
							"4": "Société commerciale",
							"5": "0",
							"6": "1",
							"7": "1",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "fabrication des briques ",
							"15": "ongd rehema ",
							"16": "150",
							"17": "6",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "safi assumani",
							"48": "0825793368",
							"49": "kanga 3 cité verte, mont ngafula ",
							"50": "promoteuse",
							"55": "13",
							"56": "3"
						},
						"yearOfLegalization": 2024,
						"iri": "\/api\/entrepreneurial_activities\/934"
					}
				]
			},
			"housekeeping": {
				"id": 141,
				"NIM": "765237121",
				"reference": null,
				"address": {
					"id": 945,
					"line": "mvuemba  n°2 , cité pumbu ",
					"town": {
						"id": 25,
						"name": "Mont-Ngafula",
						"city": {
							"id": 10,
							"name": "kinshasa",
							"province": {
								"id": 10,
								"name": "Kinshasa",
								"iri": "\/api\/location\/provinces\/10"
							},
							"iri": "\/api\/cities\/10"
						},
						"iri": "\/api\/towns\/25"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/141"
			},
			"timestamp": {
				"createdAt": "2024-02-17T11:14:15+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eecd85a-f88b-6d4e-8ce7-2704b48d07d4"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0830088997-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0830088997-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 135,
								"path": "rccm-65d0950b7c804.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/135"
							},
							{
								"id": 1613,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0830088997-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1613"
							},
							{
								"id": 1614,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0830088997-activity-0.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/1614"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/130"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/934"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0830088997-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0830088997-profil.png"
		},
		{
			"id": 163,
			"latitude": -1.63169,
			"longitude": 29.2307017,
			"altitude": 1676,
			"isActive": true,
			"instigator": {
				"id": 279,
				"name": "Indjili",
				"firstname": "Seth",
				"lastname": "Seth",
				"phoneNumber": "820727679",
				"location": []
			},
			"validateAt": "2024-04-22T15:00:19+00:00",
			"feedBack": {
				"desc": "VALIDER"
			},
			"personnalIdentityData": {
				"name": "kahambu",
				"firstName": "veronique",
				"lastName": "wakeuka",
				"sexe": "F",
				"phoneNumber": "0810047185",
				"birthdate": "1954-02-22T00:00:00+00:00",
				"nui": "657841313",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 8,
				"organization": {
					"name": "Economie verte",
					"city": {
						"iri": "\/api\/cities\/23"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33073695466",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "jjiii",
						"creationYear": 2005,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "jjjjj",
						"town": {
							"id": 56,
							"name": "Goma",
							"city": {
								"id": 23,
								"name": "Goma",
								"province": {
									"id": 19,
									"name": "Nord-Kivu",
									"iri": "\/api\/location\/provinces\/19"
								},
								"iri": "\/api\/cities\/23"
							},
							"iri": "\/api\/towns\/56"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/152",
						"otherData": {
							"desc": null,
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "0",
							"sectorServices": "0",
							"sectorGreeEconomy": "1",
							"otherActivitySector": null,
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "",
							"affiliationStructure": "Économie verte ",
							"turneOverAmount": "2140",
							"journalierStaff": "0",
							"pernanentStaff": "0",
							"familyStaff": "1",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": null,
							"trainningDificuty": null,
							"financingDificuty": null,
							"tracaserieDificuty": null,
							"marketAccessDificuty": null,
							"productionDificuty": null,
							"otherDificuty": null,
							"activityLinkwasteProcessing": "1",
							"activityLinkImprovedStoves": "1",
							"activityLinkRecycling": "1",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "0",
							"visionDiversifyClient": "0",
							"visionUsePackaging": "0",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "0",
							"visionOther": "",
							"otherContectNames": "marie",
							"otherContectPhoneNumber": "0973538375",
							"otherContectAddress": "turunga ",
							"instigatorOpinion": "promoteuse"
						}
					},
					{
						"name": "Véronique ",
						"creationYear": 2020,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "turunga ",
						"town": {
							"id": 57,
							"name": "Karisimbi",
							"city": {
								"id": 23,
								"name": "Goma",
								"province": {
									"id": 19,
									"name": "Nord-Kivu",
									"iri": "\/api\/location\/provinces\/19"
								},
								"iri": "\/api\/cities\/23"
							},
							"iri": "\/api\/towns\/57"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": null,
							"25": null,
							"26": null,
							"27": null,
							"28": null,
							"29": null,
							"30": null,
							"31": "1",
							"32": "1",
							"33": "1",
							"34": "",
							"35": "1",
							"36": "0",
							"37": "0",
							"38": null,
							"39": "1",
							"40": "",
							"41": "0",
							"42": "0",
							"43": "0",
							"44": "1",
							"45": "0",
							"46": ""
						},
						"activities": {
							"0": null,
							"2": "Mariée",
							"4": "Personne physique",
							"5": "0",
							"6": "0",
							"7": "0",
							"8": "1",
							"9": null,
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "",
							"15": "Économie verte ",
							"16": "2140",
							"17": "0",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "1",
							"40": "",
							"41": "0",
							"42": "0",
							"43": "0",
							"44": "1",
							"45": "0",
							"46": "",
							"47": "marie",
							"48": "0973538375",
							"49": "turunga ",
							"50": "promoteuse",
							"55": "0",
							"56": "1"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/16528"
					}
				]
			},
			"housekeeping": {
				"id": 163,
				"NIM": "512165582",
				"reference": null,
				"address": {
					"id": 16539,
					"line": "turunga ",
					"town": {
						"id": 57,
						"name": "Karisimbi",
						"city": {
							"id": 23,
							"name": "Goma",
							"province": {
								"id": 19,
								"name": "Nord-Kivu",
								"iri": "\/api\/location\/provinces\/19"
							},
							"iri": "\/api\/cities\/23"
						},
						"iri": "\/api\/towns\/57"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/163"
			},
			"timestamp": {
				"createdAt": "2024-02-17T12:56:02+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eecd93e-770b-66cc-902e-17acde83a35e"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0810047185-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0810047185-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 170,
								"path": "rccm-65d0ace514f36.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/170"
							},
							{
								"id": 45949,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0810047185-product-1.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/45949"
							},
							{
								"id": 45951,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0810047185-activity-1.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/45951"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/152"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/16528"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0810047185-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0810047185-profil.png"
		},
		{
			"id": 166,
			"latitude": 1.5700467,
			"longitude": 30.2411832,
			"altitude": 1176.6,
			"isActive": true,
			"instigator": {
				"id": 275,
				"name": "Masimango",
				"firstname": "Vincent",
				"lastname": "Vincent",
				"phoneNumber": "824054902",
				"location": []
			},
			"validateAt": "2024-04-29T23:57:22+00:00",
			"feedBack": null,
			"personnalIdentityData": {
				"name": "MANZIAMA ",
				"firstName": "Consolatrice",
				"lastName": "INYONDAYE",
				"sexe": "F",
				"phoneNumber": "0828890448",
				"birthdate": "1993-03-04T00:00:00+00:00",
				"nui": "170265831",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 4,
				"organization": {
					"name": "LWL( love without limite)",
					"city": {
						"iri": "\/api\/cities\/6"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "32526261933",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "ELIMU BUSINESS ",
						"creationYear": 2023,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Nsele3",
						"town": {
							"id": 79,
							"name": "Shari",
							"city": {
								"id": 6,
								"name": "Bunia",
								"province": {
									"id": 6,
									"name": "Ituri",
									"iri": "\/api\/location\/provinces\/6"
								},
								"iri": "\/api\/cities\/6"
							},
							"iri": "\/api\/towns\/79"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/155",
						"otherData": {
							"desc": null,
							"stateMarital": "Celibataire",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "1",
							"sectorServices": "0",
							"sectorGreeEconomy": "0",
							"otherActivitySector": null,
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "1",
							"otherActity": "",
							"affiliationStructure": "LWL",
							"turneOverAmount": "1500",
							"journalierStaff": "0",
							"pernanentStaff": "0",
							"familyStaff": "2",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": null,
							"trainningDificuty": null,
							"financingDificuty": null,
							"tracaserieDificuty": null,
							"marketAccessDificuty": null,
							"productionDificuty": null,
							"otherDificuty": null,
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "1",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "les etablissements scolaires ",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "Agnès MABAKUMBA",
							"otherContectPhoneNumber": "0836227814",
							"otherContectAddress": "Nsele 3",
							"instigatorOpinion": "promoteuse"
						}
					},
					{
						"name": "ELIMO BUSINESS ",
						"creationYear": 2023,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "NSELE 3",
						"town": {
							"id": 79,
							"name": "Shari",
							"city": {
								"id": 6,
								"name": "Bunia",
								"province": {
									"id": 6,
									"name": "Ituri",
									"iri": "\/api\/location\/provinces\/6"
								},
								"iri": "\/api\/cities\/6"
							},
							"iri": "\/api\/towns\/79"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": null,
							"25": null,
							"26": null,
							"27": null,
							"28": null,
							"29": null,
							"30": null,
							"31": "0",
							"32": "0",
							"33": "0",
							"34": "",
							"35": "1",
							"36": "0",
							"37": "1",
							"38": null,
							"39": "1",
							"40": "les etablissements scolaires ",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": ""
						},
						"activities": {
							"0": null,
							"2": "Celibataire",
							"4": "Personne physique",
							"5": "0",
							"6": "1",
							"7": "0",
							"8": "0",
							"9": null,
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "1",
							"14": "",
							"15": "LWL",
							"16": "1500",
							"17": "0",
							"35": "1",
							"36": "0",
							"37": "1",
							"39": "1",
							"40": "les etablissements scolaires ",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "Agnès MABAKUMBA",
							"48": "0836227814",
							"49": "Nsele 3",
							"50": "promoteuse",
							"55": "0",
							"56": "2"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/2822"
					}
				]
			},
			"housekeeping": {
				"id": 166,
				"NIM": "813733220",
				"reference": null,
				"address": {
					"id": 2833,
					"line": "MUZIBALA ",
					"town": {
						"id": 79,
						"name": "Shari",
						"city": {
							"id": 6,
							"name": "Bunia",
							"province": {
								"id": 6,
								"name": "Ituri",
								"iri": "\/api\/location\/provinces\/6"
							},
							"iri": "\/api\/cities\/6"
						},
						"iri": "\/api\/towns\/79"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/166"
			},
			"timestamp": {
				"createdAt": "2024-02-17T13:08:25+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eecd95a-2857-6956-b012-0316689ec731"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0828890448-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0828890448-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 174,
								"path": "rccm-65d0afcbf3b1c.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/174"
							},
							{
								"id": 175,
								"path": "f92-65d0afcce2172.",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/175"
							},
							{
								"id": 176,
								"path": "statut-65d0afcd9358c.",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/176"
							},
							{
								"id": 6669,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0828890448-product-1.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/6669"
							},
							{
								"id": 6670,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0828890448-product-2.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/6670"
							},
							{
								"id": 6671,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0828890448-activity-1.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/6671"
							},
							{
								"id": 6672,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0828890448-activity-2.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/6672"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/155"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/2822"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0828890448-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0828890448-profil.png"
		},
		{
			"id": 197,
			"latitude": -6.1110283,
			"longitude": 23.5758633,
			"altitude": 618.4,
			"isActive": true,
			"instigator": {
				"id": 77,
				"name": "test@monda",
				"firstname": "hummm",
				"lastname": "tshiala",
				"phoneNumber": "0819656738",
				"location": []
			},
			"validateAt": "2024-04-26T08:43:55+00:00",
			"feedBack": null,
			"personnalIdentityData": {
				"name": "META",
				"firstName": "JEANNE CLAUDE",
				"lastName": "KASONGA",
				"sexe": "F",
				"phoneNumber": "0840085187",
				"birthdate": "1981-01-28T00:00:00+00:00",
				"nui": "243860024",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 5,
				"organization": {
					"name": "Jddc",
					"city": {
						"iri": "\/api\/cities\/9"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "34312141524",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "Est maman Jeanne Claude",
						"creationYear": 2009,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Ngalula Mpanda, référence deux antennes",
						"town": {
							"id": 87,
							"name": "Bipemba",
							"city": {
								"id": 9,
								"name": "Mbuji-Mayi",
								"province": {
									"id": 9,
									"name": "Kasaï-Oriental",
									"iri": "\/api\/location\/provinces\/9"
								},
								"iri": "\/api\/cities\/9"
							},
							"iri": "\/api\/towns\/87"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/186",
						"otherData": {
							"desc": null,
							"stateMarital": "Celibataire",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "0",
							"sectorServices": "0",
							"sectorGreeEconomy": "1",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "la fabrication de vin",
							"affiliationStructure": "JDDC",
							"turneOverAmount": "600",
							"journalierStaff": "0",
							"pernanentStaff": "2",
							"familyStaff": "4",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "1",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "0",
							"otherCustomer": "",
							"visionManyBranches": "0",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "AIMERENCE MUANZA ",
							"otherContectPhoneNumber": "0856248032",
							"otherContectAddress": "commune de Dibindi, avenue :Inga,quartier :Bonzola 2,référence :École Eurêka ",
							"instigatorOpinion": "potentiel"
						}
					},
					{
						"name": "Est maman Meta",
						"creationYear": 2009,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "avenue :Ngalula Mpanda, quartier :Bimpe, référence deux antennes non loin du siège provincial du parti de l'AFDC",
						"town": {
							"id": 87,
							"name": "Bipemba",
							"city": {
								"id": 9,
								"name": "Mbuji-Mayi",
								"province": {
									"id": 9,
									"name": "Kasaï-Oriental",
									"iri": "\/api\/location\/provinces\/9"
								},
								"iri": "\/api\/cities\/9"
							},
							"iri": "\/api\/towns\/87"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "1",
							"30": "",
							"31": "1",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Celibataire",
							"4": "Personne physique",
							"5": "0",
							"6": "0",
							"7": "0",
							"8": "1",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "la fabrication de vin",
							"15": "JDDC",
							"16": "600",
							"17": "0",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "0",
							"40": "",
							"41": "0",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "AIMERENCE MUANZA ",
							"48": "0856248032",
							"49": "commune de Dibindi, avenue :Inga,quartier :Bonzola 2,référence :École Eurêka ",
							"50": "potentiel",
							"55": "2",
							"56": "4"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/737"
					}
				]
			},
			"housekeeping": {
				"id": 197,
				"NIM": "052186733",
				"reference": null,
				"address": {
					"id": 748,
					"line": "Avenue:Ngalula Mpanda, quartier:bimpe,référence :deux antenne ",
					"town": {
						"id": 87,
						"name": "Bipemba",
						"city": {
							"id": 9,
							"name": "Mbuji-Mayi",
							"province": {
								"id": 9,
								"name": "Kasaï-Oriental",
								"iri": "\/api\/location\/provinces\/9"
							},
							"iri": "\/api\/cities\/9"
						},
						"iri": "\/api\/towns\/87"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/197"
			},
			"timestamp": {
				"createdAt": "2024-02-17T15:44:03+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eecdab6-0a9e-6786-9906-1dbbdd748349"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0840085187-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0840085187-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 1295,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0840085187-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1295"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/186"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/737"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0840085187-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0840085187-profil.png"
		},
		{
			"id": 198,
			"latitude": -6.1354033,
			"longitude": 23.56813,
			"altitude": 645.9,
			"isActive": null,
			"instigator": {
				"id": 78,
				"name": "test@monda",
				"firstname": "hummm",
				"lastname": "tshiala",
				"phoneNumber": "0819656738",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "NJIBA",
				"firstName": "THÉRÈSE ",
				"lastName": "KALONJI",
				"sexe": "F",
				"phoneNumber": "0843072782",
				"birthdate": "1955-05-25T00:00:00+00:00",
				"nui": "725405657",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 7,
				"organization": {
					"name": "Jddc",
					"city": {
						"iri": "\/api\/cities\/9"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "35587805641",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "Est Maman Njiba ",
						"creationYear": 1995,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Avenue :Mulumba kabua ka katenda,référence l'antenne de Tigo",
						"town": {
							"id": 87,
							"name": "Bipemba",
							"city": {
								"id": 9,
								"name": "Mbuji-Mayi",
								"province": {
									"id": 9,
									"name": "Kasaï-Oriental",
									"iri": "\/api\/location\/provinces\/9"
								},
								"iri": "\/api\/cities\/9"
							},
							"iri": "\/api\/towns\/87"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/187",
						"otherData": {
							"desc": null,
							"stateMarital": "Veuve",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "0",
							"sectorServices": "0",
							"sectorGreeEconomy": "1",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "la production de la boisson alcoolique ",
							"affiliationStructure": "JDD,FOPAKOR",
							"turneOverAmount": "2000",
							"journalierStaff": "4",
							"pernanentStaff": "2",
							"familyStaff": "3",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "1",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "0",
							"visionOther": "",
							"otherContectNames": "TSHISUAKA ELIE ",
							"otherContectPhoneNumber": "0899468418",
							"otherContectAddress": "commune de Bipemba, quartier :Bakenda,avenue :Mulumba, référence :Antenne de Tigo",
							"instigatorOpinion": "potentiel"
						}
					},
					{
						"name": "Est Maman Thérèse Njiba",
						"creationYear": 1995,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "avenue :Mulumba, quartier :Bakenda,référence :antenne de Tigo",
						"town": {
							"id": 87,
							"name": "Bipemba",
							"city": {
								"id": 9,
								"name": "Mbuji-Mayi",
								"province": {
									"id": 9,
									"name": "Kasaï-Oriental",
									"iri": "\/api\/location\/provinces\/9"
								},
								"iri": "\/api\/cities\/9"
							},
							"iri": "\/api\/towns\/87"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "1",
							"30": "",
							"31": "1",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Veuve",
							"4": "Personne physique",
							"5": "0",
							"6": "0",
							"7": "0",
							"8": "1",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "la production de la boisson alcoolique ",
							"15": "JDD,FOPAKOR",
							"16": "2000",
							"17": "4",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "0",
							"46": "",
							"47": "TSHISUAKA ELIE ",
							"48": "0899468418",
							"49": "commune de Bipemba, quartier :Bakenda,avenue :Mulumba, référence :Antenne de Tigo",
							"50": "potentiel",
							"55": "2",
							"56": "3"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/1549"
					}
				]
			},
			"housekeeping": {
				"id": 198,
				"NIM": "720240367",
				"reference": null,
				"address": {
					"id": 1560,
					"line": "avenue :Mulumba, quartier :Bakenda,référence :antenne de réseau Tigo(Orange)",
					"town": {
						"id": 87,
						"name": "Bipemba",
						"city": {
							"id": 9,
							"name": "Mbuji-Mayi",
							"province": {
								"id": 9,
								"name": "Kasaï-Oriental",
								"iri": "\/api\/location\/provinces\/9"
							},
							"iri": "\/api\/cities\/9"
						},
						"iri": "\/api\/towns\/87"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/198"
			},
			"timestamp": {
				"createdAt": "2024-02-17T15:44:17+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eecdab6-8afa-62d6-b407-b354446badaa"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0843072782-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0843072782-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 243,
								"path": "rccm-65d0d4527fc94.",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/243"
							},
							{
								"id": 244,
								"path": "f92-65d0d45366705.",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/244"
							},
							{
								"id": 245,
								"path": "statut-65d0d4545bb72.",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/245"
							},
							{
								"id": 3150,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0843072782-product-1.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/3150"
							},
							{
								"id": 3151,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0843072782-activity-1.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/3151"
							},
							{
								"id": 3152,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0843072782-activity-2.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/3152"
							},
							{
								"id": 3153,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0843072782-activity-3.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/3153"
							},
							{
								"id": 3154,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0843072782-activity-4.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/3154"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/187"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/1549"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0843072782-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0843072782-profil.png"
		},
		{
			"id": 220,
			"latitude": -4.3558685,
			"longitude": 15.2419124,
			"altitude": 384.4,
			"isActive": null,
			"instigator": {
				"id": 16,
				"name": "Diki",
				"firstname": "Merveille",
				"lastname": "Minga",
				"phoneNumber": "0810057067",
				"location": []
			},
			"validateAt": "2024-04-29T08:56:20+00:00",
			"feedBack": {
				"desc": "pas d'opinion de l'enquêteur",
				"noStructure": true,
				"noTurnorOver": true,
				"noActivityDescription": true
			},
			"personnalIdentityData": {
				"name": "Hepi",
				"firstName": "Irène",
				"lastName": "Nzupia",
				"sexe": "F",
				"phoneNumber": "0898956812",
				"birthdate": "1974-12-22T00:00:00+00:00",
				"nui": "714854020",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 10,
				"organization": {
					"name": "independant",
					"city": {
						"iri": "\/api\/cities\/10"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "30125926747",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "bika maman pasteur",
						"creationYear": 2019,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "quartier L1",
						"town": {
							"id": 28,
							"name": "Ngaliema",
							"city": {
								"id": 10,
								"name": "kinshasa",
								"province": {
									"id": 10,
									"name": "Kinshasa",
									"iri": "\/api\/location\/provinces\/10"
								},
								"iri": "\/api\/cities\/10"
							},
							"iri": "\/api\/towns\/28"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/209",
						"otherData": {
							"desc": null,
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "",
							"sectorAgroForestry": "0",
							"sectorIndustry": "1",
							"sectorServices": "0",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "1",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "",
							"affiliationStructure": "",
							"turneOverAmount": "20",
							"journalierStaff": "2",
							"pernanentStaff": "0",
							"familyStaff": "0",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "1",
							"marketAccessDificuty": "1",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "1",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "0",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "Mr kiaza",
							"otherContectPhoneNumber": "0898956812",
							"otherContectAddress": "Ngaliema, QL1",
							"instigatorOpinion": ""
						}
					},
					{
						"name": "mbika Fumé",
						"creationYear": 2019,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "QL1",
						"town": {
							"id": 28,
							"name": "Ngaliema",
							"city": {
								"id": 10,
								"name": "kinshasa",
								"province": {
									"id": 10,
									"name": "Kinshasa",
									"iri": "\/api\/location\/provinces\/10"
								},
								"iri": "\/api\/cities\/10"
							},
							"iri": "\/api\/towns\/28"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "1",
							"28": "1",
							"29": "1",
							"30": "",
							"31": "1",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Mariée",
							"4": "",
							"5": "0",
							"6": "1",
							"7": "0",
							"8": "0",
							"9": "",
							"10": "1",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "",
							"15": "",
							"16": "20",
							"17": "2",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "0",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "Mr kiaza",
							"48": "0898956812",
							"49": "Ngaliema, QL1",
							"50": "",
							"55": "0",
							"56": "0"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/678"
					}
				]
			},
			"housekeeping": {
				"id": 220,
				"NIM": "413713040",
				"reference": null,
				"address": {
					"id": 689,
					"line": "QL1",
					"town": {
						"id": 28,
						"name": "Ngaliema",
						"city": {
							"id": 10,
							"name": "kinshasa",
							"province": {
								"id": 10,
								"name": "Kinshasa",
								"iri": "\/api\/location\/provinces\/10"
							},
							"iri": "\/api\/cities\/10"
						},
						"iri": "\/api\/towns\/28"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/220"
			},
			"timestamp": {
				"createdAt": "2024-02-17T17:42:14+00:00",
				"updatedAt": "2024-05-07T08:18:13+00:00",
				"slug": "1eecdbbe-2b4c-6380-af5f-b9e0ecea24e6"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0898956812-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0898956812-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 299,
								"path": "rccm-65d0eff6c4e69.",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/299"
							},
							{
								"id": 300,
								"path": "f92-65d0eff76763a.",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/300"
							},
							{
								"id": 301,
								"path": "statut-65d0eff80a1de.",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/301"
							},
							{
								"id": 1193,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0898956812-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1193"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/209"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/678"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0898956812-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0898956812-profil.png"
		},
		{
			"id": 311,
			"latitude": -4.3423423423423,
			"longitude": 15.232916021756,
			"altitude": 0,
			"isActive": null,
			"instigator": {
				"id": 291,
				"name": "Bandusha",
				"firstname": "Rosita",
				"lastname": "Amini",
				"phoneNumber": "898731955",
				"location": []
			},
			"validateAt": "2024-05-03T20:38:37+00:00",
			"feedBack": {
				"photoProfileInvisible": true
			},
			"personnalIdentityData": {
				"name": "BIYE",
				"firstName": "PERPETUE ",
				"lastName": "BAYALE ",
				"sexe": "F",
				"phoneNumber": "0820485588",
				"birthdate": "1966-05-07T00:00:00+00:00",
				"nui": "147661737",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 3,
				"organization": {
					"name": "ADPEV",
					"city": {
						"iri": "\/api\/cities\/10"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "30315552807",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "la joie ",
						"creationYear": 2018,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Av\/ Mobutu  N 11 Q\/ Don bosco  ",
						"town": {
							"id": 25,
							"name": "Mont-Ngafula",
							"city": {
								"id": 10,
								"name": "kinshasa",
								"province": {
									"id": 10,
									"name": "Kinshasa",
									"iri": "\/api\/location\/provinces\/10"
								},
								"iri": "\/api\/cities\/10"
							},
							"iri": "\/api\/towns\/25"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 2021,
						"iri": "\/api\/entrepreneurial_activities\/300",
						"otherData": {
							"desc": "SERVICE TRAITEUR  RESTAURANT ",
							"stateMarital": "Celibataire",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "1",
							"sectorServices": "0",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "FUFU, PONDU , HARICOTS,  POISSON,  CUISSE,  POULET,  MAKOSO, MAKEMBA , MABOKE YA PONDU , FUMBWA,  POMME DE TERRE,  ",
							"affiliationStructure": "ADEPV",
							"turneOverAmount": "9850",
							"journalierStaff": "0",
							"pernanentStaff": "10",
							"familyStaff": "2",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "1",
							"otherDificuty": "AVOIR UN ENDROIT STABLE  POUR INSTALLER  NOS SERVICE,  PROBLÈME DE TRANSPORT  POUR LES ACHATS ET LA LIVRAISON,  PROBLÈME D'ÉLECTRICITÉ, ",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "0",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "0",
							"visionOther": "OUVRIR  UN GRAND RESTAURANT PROPRE ET CLASSE ",
							"otherContectNames": "MABIALA BITIBA YANNICK ",
							"otherContectPhoneNumber": "0815424618",
							"otherContectAddress": "AV\/ KAPANGA N 31 Q\/ CPA MUSHIE ",
							"instigatorOpinion": "mature"
						}
					},
					{
						"name": "",
						"creationYear": 2019,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "AV\/ JEUNESSE  N 41 Q\/ DON BOSCO ",
						"town": {
							"id": 25,
							"name": "Mont-Ngafula",
							"city": {
								"id": 10,
								"name": "kinshasa",
								"province": {
									"id": 10,
									"name": "Kinshasa",
									"iri": "\/api\/location\/provinces\/10"
								},
								"iri": "\/api\/cities\/10"
							},
							"iri": "\/api\/towns\/25"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "1",
							"30": "AVOIR UN ENDROIT STABLE  POUR INSTALLER  NOS SERVICE,  PROBLÈME DE TRANSPORT  POUR LES ACHATS ET LA LIVRAISON,  PROBLÈME D'ÉLECTRICITÉ, ",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"0": "SERVICE TRAITEUR  RESTAURANT ",
							"2": "Celibataire",
							"4": "Personne physique",
							"5": "0",
							"6": "1",
							"7": "0",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "FUFU, PONDU , HARICOTS,  POISSON,  CUISSE,  POULET,  MAKOSO, MAKEMBA , MABOKE YA PONDU , FUMBWA,  POMME DE TERRE,  ",
							"15": "ADEPV",
							"16": "9850",
							"17": "0",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "0",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "0",
							"46": "OUVRIR  UN GRAND RESTAURANT PROPRE ET CLASSE ",
							"47": "MABIALA BITIBA YANNICK ",
							"48": "0815424618",
							"49": "AV\/ KAPANGA N 31 Q\/ CPA MUSHIE ",
							"50": "mature",
							"55": "10",
							"56": "2"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/58560"
					}
				]
			},
			"housekeeping": {
				"id": 311,
				"NIM": "271227710",
				"reference": null,
				"address": {
					"id": 58578,
					"line": "AV\/ JEUNESSE  N 41 Q\/ DON BOSCO ",
					"town": {
						"id": 25,
						"name": "Mont-Ngafula",
						"city": {
							"id": 10,
							"name": "kinshasa",
							"province": {
								"id": 10,
								"name": "Kinshasa",
								"iri": "\/api\/location\/provinces\/10"
							},
							"iri": "\/api\/cities\/10"
						},
						"iri": "\/api\/towns\/25"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/311"
			},
			"timestamp": {
				"createdAt": "2024-02-19T05:54:31+00:00",
				"updatedAt": "2024-05-07T08:22:56+00:00",
				"slug": "1eeceeb5-9b02-66d4-a142-e7d49dfe14e9"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 450,
								"path": "rccm-65d2ed1972576.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/450"
							},
							{
								"id": 150980,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-product-1.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/150980"
							},
							{
								"id": 150981,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-product-2.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/150981"
							},
							{
								"id": 150982,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-product-3.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/150982"
							},
							{
								"id": 150983,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-product-4.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/150983"
							},
							{
								"id": 150984,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-product-5.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/150984"
							},
							{
								"id": 150985,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-product-6.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/150985"
							},
							{
								"id": 150986,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-product-7.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/150986"
							},
							{
								"id": 150987,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-product-8.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/150987"
							},
							{
								"id": 150988,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-product-9.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/150988"
							},
							{
								"id": 150989,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-activity-1.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/150989"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/300"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/58560"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0820485588-profil.png"
		},
		{
			"id": 358,
			"latitude": -6.118115,
			"longitude": 23.5936633,
			"altitude": 680.4,
			"isActive": true,
			"instigator": {
				"id": 294,
				"name": "Samwanda",
				"firstname": "Isaac",
				"lastname": "Isaac",
				"phoneNumber": "821808251",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "LIONO",
				"firstName": "Divine",
				"lastName": "LILEMBO",
				"sexe": "F",
				"phoneNumber": "0822602131",
				"birthdate": "1999-01-14T00:00:00+00:00",
				"nui": "578608042",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 8,
				"organization": {
					"name": "Independant",
					"city": {
						"iri": "\/api\/cities\/9"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "34322149557",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "Agri Divine",
						"creationYear": 2022,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Lumumba 8, Q. la poste, C\/ Muya",
						"town": {
							"id": 89,
							"name": "Muya",
							"city": {
								"id": 9,
								"name": "Mbuji-Mayi",
								"province": {
									"id": 9,
									"name": "Kasaï-Oriental",
									"iri": "\/api\/location\/provinces\/9"
								},
								"iri": "\/api\/cities\/9"
							},
							"iri": "\/api\/towns\/89"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/347",
						"otherData": {
							"desc": null,
							"stateMarital": "Celibataire",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "1",
							"sectorIndustry": "1",
							"sectorServices": "1",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "1",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "",
							"affiliationStructure": "",
							"turneOverAmount": "1000",
							"journalierStaff": "0",
							"pernanentStaff": "9",
							"familyStaff": "1",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "0",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "0",
							"otherCustomer": "",
							"visionManyBranches": "0",
							"visionDiversifyClient": "0",
							"visionUsePackaging": "0",
							"visionInprouveTurneOver": "0",
							"visionMakeFactory": "0",
							"visionOther": "",
							"otherContectNames": "Flory Mutshipayi",
							"otherContectPhoneNumber": "0842223388",
							"otherContectAddress": "Av Makenga 4, Bonzola, Muya, Réf Monaco ",
							"instigatorOpinion": "potentiel"
						}
					},
					{
						"name": "Agri Divine",
						"creationYear": 2022,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Lumumba 8, La poste",
						"town": {
							"id": 89,
							"name": "Muya",
							"city": {
								"id": 9,
								"name": "Mbuji-Mayi",
								"province": {
									"id": 9,
									"name": "Kasaï-Oriental",
									"iri": "\/api\/location\/provinces\/9"
								},
								"iri": "\/api\/cities\/9"
							},
							"iri": "\/api\/towns\/89"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Celibataire",
							"4": "Personne physique",
							"5": "1",
							"6": "1",
							"7": "1",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "1",
							"12": "0",
							"13": "0",
							"14": "",
							"15": "",
							"16": "1000",
							"17": "0",
							"35": "0",
							"36": "0",
							"37": "0",
							"39": "0",
							"40": "",
							"41": "0",
							"42": "0",
							"43": "0",
							"44": "0",
							"45": "0",
							"46": "",
							"47": "Flory Mutshipayi",
							"48": "0842223388",
							"49": "Av Makenga 4, Bonzola, Muya, Réf Monaco ",
							"50": "potentiel",
							"55": "9",
							"56": "1"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/457"
					}
				]
			},
			"housekeeping": {
				"id": 358,
				"NIM": "224274240",
				"reference": null,
				"address": {
					"id": 468,
					"line": "Makenga 4, Bonzola, derrière Monaco ",
					"town": {
						"id": 89,
						"name": "Muya",
						"city": {
							"id": 9,
							"name": "Mbuji-Mayi",
							"province": {
								"id": 9,
								"name": "Kasaï-Oriental",
								"iri": "\/api\/location\/provinces\/9"
							},
							"iri": "\/api\/cities\/9"
						},
						"iri": "\/api\/towns\/89"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/358"
			},
			"timestamp": {
				"createdAt": "2024-02-20T08:54:58+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eecfcdb-9d82-66aa-9068-9ddcca8b4201"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0822602131-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0822602131-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 793,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0822602131-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/793"
							},
							{
								"id": 794,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0822602131-product-1.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/794"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/347"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/457"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0822602131-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0822602131-profil.png"
		},
		{
			"id": 390,
			"latitude": -2.5013455,
			"longitude": 28.8622155,
			"altitude": 1499.1221541535,
			"isActive": true,
			"instigator": {
				"id": 124,
				"name": "test@Assumani",
				"firstname": "Baxter",
				"lastname": "Assumani",
				"phoneNumber": "0995695550",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "Senga",
				"firstName": "sabina",
				"lastName": "kasongo",
				"sexe": "F",
				"phoneNumber": "0975152058",
				"birthdate": "1976-11-25T00:00:00+00:00",
				"nui": "752158741",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 7,
				"organization": {
					"name": "adepav",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33655515536",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "sabina business ",
						"creationYear": 2005,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "ulindi n°32b",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "1",
							"22": "finca",
							"23": "1000",
							"24": "1",
							"25": "0",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Mariée",
							"4": "Personne physique",
							"5": "1",
							"6": "0",
							"7": "0",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "production miel ",
							"15": "adpav",
							"16": "2000",
							"17": "0",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "1",
							"40": "",
							"41": "0",
							"42": "0",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "kapapa masonga camille",
							"48": "0998682267",
							"49": "avenue ulindi n°32b",
							"50": "potentiel",
							"55": "3",
							"56": "3"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/381",
						"otherData": {
							"desc": null,
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "1",
							"sectorIndustry": "0",
							"sectorServices": "0",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "production miel ",
							"affiliationStructure": "adpav",
							"turneOverAmount": "2000",
							"journalierStaff": "0",
							"pernanentStaff": "3",
							"familyStaff": "3",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "1",
							"institutCredit": "finca",
							"amountCredit": "1000",
							"noDificuty": "1",
							"trainningDificuty": "0",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "0",
							"visionDiversifyClient": "0",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "kapapa masonga camille",
							"otherContectPhoneNumber": "0998682267",
							"otherContectAddress": "avenue ulindi n°32b",
							"instigatorOpinion": "potentiel"
						}
					}
				]
			},
			"housekeeping": {
				"id": 390,
				"NIM": "635001507",
				"reference": null,
				"address": {
					"id": 392,
					"line": "ulindi n°32b",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/390"
			},
			"timestamp": {
				"createdAt": "2024-02-21T07:06:14+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed087b-3a91-6a72-baa8-0ddf5559845e"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0975152058-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0975152058-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 620,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0975152058-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/620"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/381"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0975152058-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0975152058-profil.png"
		},
		{
			"id": 391,
			"latitude": -1.6510011,
			"longitude": 29.2311703,
			"altitude": 0,
			"isActive": null,
			"instigator": {
				"id": 125,
				"name": "MWANASHIMA",
				"firstname": "CLAUDE",
				"lastname": "RENE",
				"phoneNumber": "0811461205",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "Katungu",
				"firstName": "Jacky",
				"lastName": "Kahumba",
				"sexe": "F",
				"phoneNumber": "0990104419",
				"birthdate": "1984-01-01T00:00:00+00:00",
				"nui": "546568546",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 9,
				"organization": null
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33060303172",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "Maison Wasingya",
						"creationYear": 2023,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "avenue kibinda 33",
						"town": {
							"id": 57,
							"name": "Karisimbi",
							"city": {
								"id": 23,
								"name": "Goma",
								"province": {
									"id": 19,
									"name": "Nord-Kivu",
									"iri": "\/api\/location\/provinces\/19"
								},
								"iri": "\/api\/cities\/23"
							},
							"iri": "\/api\/towns\/57"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/382",
						"otherData": {
							"desc": null,
							"stateMarital": null,
							"otherIDCard": null,
							"legalStatus": null,
							"sectorAgroForestry": null,
							"sectorIndustry": null,
							"sectorServices": null,
							"sectorGreeEconomy": null,
							"otherActivitySector": null,
							"transformFruitAndVegetableActivity": null,
							"juiceMakerActivity": null,
							"condimengActivity": null,
							"FumageSalaisonSechageActity": null,
							"otherActity": null,
							"affiliationStructure": null,
							"turneOverAmount": null,
							"journalierStaff": null,
							"pernanentStaff": null,
							"familyStaff": null,
							"concourFinancing": null,
							"padepmeFinancing": null,
							"otherFinancing": null,
							"haveCredit": null,
							"institutCredit": null,
							"amountCredit": null,
							"noDificuty": null,
							"trainningDificuty": null,
							"financingDificuty": null,
							"tracaserieDificuty": null,
							"marketAccessDificuty": null,
							"productionDificuty": null,
							"otherDificuty": null,
							"activityLinkwasteProcessing": null,
							"activityLinkImprovedStoves": null,
							"activityLinkRecycling": null,
							"otherActivityLink": null,
							"indidualCustomer": null,
							"supermarketCustomer": null,
							"businessCustomer": null,
							"onLineCustomer": null,
							"dealerCustomer": null,
							"otherCustomer": null,
							"visionManyBranches": null,
							"visionDiversifyClient": null,
							"visionUsePackaging": null,
							"visionInprouveTurneOver": null,
							"visionMakeFactory": null,
							"visionOther": null,
							"otherContectNames": null,
							"otherContectPhoneNumber": null,
							"otherContectAddress": null,
							"instigatorOpinion": null
						}
					}
				]
			},
			"housekeeping": {
				"id": 391,
				"NIM": "482432148",
				"reference": null,
				"address": {
					"id": 393,
					"line": "avenue Bakungu 121",
					"town": {
						"id": 57,
						"name": "Karisimbi",
						"city": {
							"id": 23,
							"name": "Goma",
							"province": {
								"id": 19,
								"name": "Nord-Kivu",
								"iri": "\/api\/location\/provinces\/19"
							},
							"iri": "\/api\/cities\/23"
						},
						"iri": "\/api\/towns\/57"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/391"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:07:46+00:00",
				"updatedAt": "2024-03-01T08:48:32+00:00",
				"slug": "1eed0904-bea9-6e98-927b-49cc2a9c4168"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/546568546.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/546568546.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 621,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/621"
							},
							{
								"id": 622,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/622"
							},
							{
								"id": 623,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/623"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/382"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/546568546.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/546568546.png"
		},
		{
			"id": 392,
			"latitude": -4.3337974,
			"longitude": 15.2577102,
			"altitude": 287.89999389648,
			"isActive": null,
			"instigator": {
				"id": 290,
				"name": "Bimpa",
				"firstname": "Gedeon",
				"lastname": "Gedeon",
				"phoneNumber": "850847981",
				"location": []
			},
			"validateAt": "2024-05-04T11:00:22+00:00",
			"feedBack": null,
			"personnalIdentityData": {
				"name": "Pambu",
				"firstName": "Guislaine",
				"lastName": "Pambu",
				"sexe": "F",
				"phoneNumber": "0725631245",
				"birthdate": "1989-12-22T00:00:00+00:00",
				"nui": "631646667",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 2,
				"organization": {
					"name": "independant",
					"city": {
						"iri": "\/api\/cities\/10"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "1362763827538892525",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "Mata mata",
						"creationYear": 2004,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "idiofa",
						"town": {
							"id": 9,
							"name": "Barumbu",
							"city": {
								"id": 10,
								"name": "kinshasa",
								"province": {
									"id": 10,
									"name": "Kinshasa",
									"iri": "\/api\/location\/provinces\/10"
								},
								"iri": "\/api\/cities\/10"
							},
							"iri": "\/api\/towns\/9"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "1",
							"28": "0",
							"29": "0",
							"30": "",
							"31": "1",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Celibataire",
							"4": "Personne physique",
							"5": "1",
							"6": "0",
							"7": "1",
							"8": "0",
							"9": "",
							"10": "1",
							"11": "1",
							"12": "1",
							"13": "0",
							"14": "",
							"15": "",
							"16": "3000",
							"17": "1",
							"35": "1",
							"36": "1",
							"37": "0",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "Muana soda",
							"48": "0325649852",
							"49": "bongolo bongolo bongolo",
							"50": "subsistance",
							"55": "2",
							"56": "0"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/383",
						"otherData": {
							"desc": null,
							"stateMarital": "Celibataire",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "1",
							"sectorIndustry": "0",
							"sectorServices": "1",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "1",
							"juiceMakerActivity": "1",
							"condimengActivity": "1",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "",
							"affiliationStructure": "",
							"turneOverAmount": "3000",
							"journalierStaff": "1",
							"pernanentStaff": "2",
							"familyStaff": "0",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "1",
							"marketAccessDificuty": "0",
							"productionDificuty": "0",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "1",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "1",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "Muana soda",
							"otherContectPhoneNumber": "0325649852",
							"otherContectAddress": "bongolo bongolo bongolo",
							"instigatorOpinion": "subsistance"
						}
					}
				]
			},
			"housekeeping": {
				"id": 392,
				"NIM": "610835534",
				"reference": null,
				"address": {
					"id": 394,
					"line": "Idiofa",
					"town": {
						"id": 9,
						"name": "Barumbu",
						"city": {
							"id": 10,
							"name": "kinshasa",
							"province": {
								"id": 10,
								"name": "Kinshasa",
								"iri": "\/api\/location\/provinces\/10"
							},
							"iri": "\/api\/cities\/10"
						},
						"iri": "\/api\/towns\/9"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/392"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:07:54+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed0905-0986-6272-b5bf-93312af85a21"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0725631245-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0725631245-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 624,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0725631245-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/624"
							},
							{
								"id": 625,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0725631245-activity-0.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/625"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/383"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0725631245-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0725631245-profil.png"
		},
		{
			"id": 393,
			"latitude": -1.63796,
			"longitude": 29.2248417,
			"altitude": 1578,
			"isActive": null,
			"instigator": {
				"id": 126,
				"name": "MWANASHIMA",
				"firstname": "CLAUDE",
				"lastname": "RENE",
				"phoneNumber": "0811461205",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "KAVIRA",
				"firstName": "Julie",
				"lastName": "BAGHUMA",
				"sexe": "F",
				"phoneNumber": "0997649434",
				"birthdate": "1984-03-08T00:00:00+00:00",
				"nui": "826674175",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 4,
				"organization": null
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33061095828",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "Makasi",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Kanyangwe",
						"town": {
							"id": 57,
							"name": "Karisimbi",
							"city": {
								"id": 23,
								"name": "Goma",
								"province": {
									"id": 19,
									"name": "Nord-Kivu",
									"iri": "\/api\/location\/provinces\/19"
								},
								"iri": "\/api\/cities\/23"
							},
							"iri": "\/api\/towns\/57"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/384",
						"otherData": {
							"desc": null,
							"stateMarital": null,
							"otherIDCard": null,
							"legalStatus": null,
							"sectorAgroForestry": null,
							"sectorIndustry": null,
							"sectorServices": null,
							"sectorGreeEconomy": null,
							"otherActivitySector": null,
							"transformFruitAndVegetableActivity": null,
							"juiceMakerActivity": null,
							"condimengActivity": null,
							"FumageSalaisonSechageActity": null,
							"otherActity": null,
							"affiliationStructure": null,
							"turneOverAmount": null,
							"journalierStaff": null,
							"pernanentStaff": null,
							"familyStaff": null,
							"concourFinancing": null,
							"padepmeFinancing": null,
							"otherFinancing": null,
							"haveCredit": null,
							"institutCredit": null,
							"amountCredit": null,
							"noDificuty": null,
							"trainningDificuty": null,
							"financingDificuty": null,
							"tracaserieDificuty": null,
							"marketAccessDificuty": null,
							"productionDificuty": null,
							"otherDificuty": null,
							"activityLinkwasteProcessing": null,
							"activityLinkImprovedStoves": null,
							"activityLinkRecycling": null,
							"otherActivityLink": null,
							"indidualCustomer": null,
							"supermarketCustomer": null,
							"businessCustomer": null,
							"onLineCustomer": null,
							"dealerCustomer": null,
							"otherCustomer": null,
							"visionManyBranches": null,
							"visionDiversifyClient": null,
							"visionUsePackaging": null,
							"visionInprouveTurneOver": null,
							"visionMakeFactory": null,
							"visionOther": null,
							"otherContectNames": null,
							"otherContectPhoneNumber": null,
							"otherContectAddress": null,
							"instigatorOpinion": null
						}
					}
				]
			},
			"housekeeping": {
				"id": 393,
				"NIM": "866702242",
				"reference": null,
				"address": {
					"id": 395,
					"line": "mapinduzi 22",
					"town": {
						"id": 57,
						"name": "Karisimbi",
						"city": {
							"id": 23,
							"name": "Goma",
							"province": {
								"id": 19,
								"name": "Nord-Kivu",
								"iri": "\/api\/location\/provinces\/19"
							},
							"iri": "\/api\/cities\/23"
						},
						"iri": "\/api\/towns\/57"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/393"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:08:12+00:00",
				"updatedAt": "2024-03-01T09:31:01+00:00",
				"slug": "1eed0905-ba30-60fa-8056-2d7bcd7c1b3a"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/826674175.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/826674175.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 626,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/626"
							},
							{
								"id": 627,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/627"
							},
							{
								"id": 628,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/628"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/384"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/826674175.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/826674175.png"
		},
		{
			"id": 395,
			"latitude": -2.5122853,
			"longitude": 28.865688,
			"altitude": 0,
			"isActive": true,
			"instigator": {
				"id": 292,
				"name": "Chibembe",
				"firstname": "Christophe",
				"lastname": "Christophe",
				"phoneNumber": "995170824",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "SHUKURU",
				"firstName": "Claudine",
				"lastName": "LUHIRIRI",
				"sexe": "F",
				"phoneNumber": "0973706223",
				"birthdate": "1987-12-10T00:00:00+00:00",
				"nui": "513365744",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 10,
				"organization": {
					"name": "independant",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33644902445",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "Impact Firm ",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Av. kyembwa N°034",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 2023,
						"iri": "\/api\/entrepreneurial_activities\/386",
						"otherData": {
							"desc": null,
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "1",
							"sectorIndustry": "0",
							"sectorServices": "0",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "",
							"affiliationStructure": "",
							"turneOverAmount": "5000",
							"journalierStaff": "2",
							"pernanentStaff": "8",
							"familyStaff": "0",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "1",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "1",
							"businessCustomer": "1",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "MAKULU ILUNGA Rosy",
							"otherContectPhoneNumber": "0998297753",
							"otherContectAddress": "Av. paysage N°132 ibanda",
							"instigatorOpinion": "promoteuse"
						}
					},
					{
						"name": "IMPACT FIRM ",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Av. Kwembwa N°034",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "impôt ",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "0",
							"28": "1",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Mariée",
							"4": "Personne physique",
							"5": "1",
							"6": "0",
							"7": "0",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "",
							"15": "",
							"16": "5000",
							"17": "2",
							"35": "1",
							"36": "1",
							"37": "1",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "MAKULU ILUNGA Rosy",
							"48": "0998297753",
							"49": "Av. paysage N°132 ibanda",
							"50": "promoteuse",
							"55": "8",
							"56": "0"
						},
						"yearOfLegalization": 2023,
						"iri": "\/api\/entrepreneurial_activities\/527"
					}
				]
			},
			"housekeeping": {
				"id": 395,
				"NIM": "742802807",
				"reference": null,
				"address": {
					"id": 538,
					"line": "Av.ISECOF",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/395"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:14:37+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed0914-14ed-65fc-aaee-875403b47f84"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973706223-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973706223-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 632,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/632"
							},
							{
								"id": 633,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/633"
							},
							{
								"id": 634,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/634"
							},
							{
								"id": 918,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973706223-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/918"
							},
							{
								"id": 919,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973706223-product-1.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/919"
							},
							{
								"id": 920,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973706223-product-2.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/920"
							},
							{
								"id": 921,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973706223-activity-0.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/921"
							},
							{
								"id": 922,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973706223-activity-1.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/922"
							},
							{
								"id": 923,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973706223-activity-2.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/923"
							},
							{
								"id": 924,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973706223-activity-3.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/924"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/386"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/527"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973706223-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0973706223-profil.png"
		},
		{
			"id": 397,
			"latitude": -2.5134017,
			"longitude": 28.865555,
			"altitude": 1499.7000732422,
			"isActive": true,
			"instigator": {
				"id": 292,
				"name": "Chibembe",
				"firstname": "Christophe",
				"lastname": "Christophe",
				"phoneNumber": "995170824",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "NTAKWINJA ",
				"firstName": "Grâce ",
				"lastName": "MANIMANI ",
				"sexe": "F",
				"phoneNumber": "0995899470",
				"birthdate": "1993-03-13T00:00:00+00:00",
				"nui": "126407643",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 6,
				"organization": {
					"name": "independant",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "3364713591",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "NYONYO FIRM",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Kembwa N°36",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/388",
						"otherData": {
							"desc": null,
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "1",
							"sectorIndustry": "0",
							"sectorServices": "0",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "",
							"affiliationStructure": "",
							"turneOverAmount": "5000",
							"journalierStaff": "2",
							"pernanentStaff": "4",
							"familyStaff": "0",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "1",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "1",
							"businessCustomer": "1",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "ALEXI BUHENDWA",
							"otherContectPhoneNumber": "09793569",
							"otherContectAddress": "Feu rouge\/ Av. de la poste",
							"instigatorOpinion": "promoteuse"
						}
					},
					{
						"name": "NYONYO FIRM",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Av. Kembwa\/ Q latine ",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "0",
							"28": "1",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Mariée",
							"4": "Personne physique",
							"5": "1",
							"6": "0",
							"7": "0",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "",
							"15": "",
							"16": "5000",
							"17": "2",
							"35": "1",
							"36": "1",
							"37": "1",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "ALEXI BUHENDWA",
							"48": "09793569",
							"49": "Feu rouge\/ Av. de la poste",
							"50": "promoteuse",
							"55": "4",
							"56": "0"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/521"
					}
				]
			},
			"housekeeping": {
				"id": 397,
				"NIM": "105618126",
				"reference": null,
				"address": {
					"id": 532,
					"line": "Feu roge\/ Av. de la poste",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/397"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:14:59+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed0914-df0f-66dc-a7bc-35679b894d23"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995899470-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995899470-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 635,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/635"
							},
							{
								"id": 636,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/636"
							},
							{
								"id": 637,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/637"
							},
							{
								"id": 911,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995899470-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/911"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/388"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/521"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995899470-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995899470-profil.png"
		},
		{
			"id": 398,
			"latitude": -2.5065022,
			"longitude": 28.8619294,
			"altitude": 1516.4000244141,
			"isActive": true,
			"instigator": {
				"id": 292,
				"name": "Chibembe",
				"firstname": "Christophe",
				"lastname": "Christophe",
				"phoneNumber": "995170824",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "BARUTI",
				"firstName": "Rolande",
				"lastName": "NSIMIRE",
				"sexe": "F",
				"phoneNumber": "0995681460",
				"birthdate": "1990-05-13T00:00:00+00:00",
				"nui": "314385773",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 6,
				"organization": {
					"name": "fédération des entrepreneurs du congo\/ femmes entrepreneurs",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33655921736",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "Rwinja",
						"creationYear": 2019,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Av.ISGA\/NDENDERE\/Ibanda",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 2023,
						"iri": "\/api\/entrepreneurial_activities\/389",
						"otherData": {
							"desc": null,
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "Société commerciale",
							"sectorAgroForestry": "1",
							"sectorIndustry": "0",
							"sectorServices": "1",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "",
							"affiliationStructure": "FEC",
							"turneOverAmount": "7000",
							"journalierStaff": "5",
							"pernanentStaff": "4",
							"familyStaff": "0",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "1",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "1",
							"businessCustomer": "1",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "MUTABATABA MBUYI",
							"otherContectPhoneNumber": "0991377044",
							"otherContectAddress": "Av. Isga",
							"instigatorOpinion": "promoteuse"
						}
					},
					{
						"name": "EST RWINJA",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Av. Isga",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "0",
							"28": "1",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Mariée",
							"4": "Société commerciale",
							"5": "1",
							"6": "0",
							"7": "1",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "",
							"15": "FEC",
							"16": "7000",
							"17": "5",
							"35": "1",
							"36": "1",
							"37": "1",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "MUTABATABA MBUYI",
							"48": "0991377044",
							"49": "Av. Isga",
							"50": "promoteuse",
							"55": "4",
							"56": "0"
						},
						"yearOfLegalization": 2023,
						"iri": "\/api\/entrepreneurial_activities\/514"
					}
				]
			},
			"housekeeping": {
				"id": 398,
				"NIM": "825527536",
				"reference": null,
				"address": {
					"id": 525,
					"line": "Av. IISGA",
					"town": {
						"id": 70,
						"name": "Kadutu",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/70"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/398"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:15:23+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed0915-c8cb-66a4-a518-6b1cf7779a0b"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995681460-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995681460-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 638,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/638"
							},
							{
								"id": 639,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/639"
							},
							{
								"id": 640,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/640"
							},
							{
								"id": 895,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995681460-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/895"
							},
							{
								"id": 896,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995681460-product-1.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/896"
							},
							{
								"id": 897,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995681460-product-2.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/897"
							},
							{
								"id": 898,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995681460-product-3.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/898"
							},
							{
								"id": 899,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995681460-activity-0.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/899"
							},
							{
								"id": 900,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995681460-activity-1.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/900"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/389"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/514"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995681460-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995681460-profil.png"
		},
		{
			"id": 399,
			"latitude": -4.3744083,
			"longitude": 15.2842483,
			"altitude": 350,
			"isActive": null,
			"instigator": {
				"id": 281,
				"name": "Batalungu",
				"firstname": "Albertine",
				"lastname": "Albertine",
				"phoneNumber": "897470964",
				"location": []
			},
			"validateAt": null,
			"feedBack": {
				"noActivityDescription": true
			},
			"personnalIdentityData": {
				"name": "MPEMBA",
				"firstName": "MARIE",
				"lastName": "MBOSO",
				"sexe": "F",
				"phoneNumber": "0893038828",
				"birthdate": "1966-08-05T00:00:00+00:00",
				"nui": "588578166",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 5,
				"organization": {
					"name": "œuvre feminine",
					"city": {
						"iri": "\/api\/cities\/10"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "30332144561",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "malewa mpemba marie",
						"creationYear": 2013,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "révolution 218",
						"town": {
							"id": 10,
							"name": "Bumbu",
							"city": {
								"id": 10,
								"name": "kinshasa",
								"province": {
									"id": 10,
									"name": "Kinshasa",
									"iri": "\/api\/location\/provinces\/10"
								},
								"iri": "\/api\/cities\/10"
							},
							"iri": "\/api\/towns\/10"
						},
						"territory": null,
						"taxes": {
							"0": "tiquet du marché, salubrité pour le salongo",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "0",
							"26": "0",
							"27": "0",
							"28": "0",
							"29": "0",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Veuve",
							"4": "Personne physique",
							"5": "0",
							"6": "1",
							"7": "0",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "",
							"15": "œuvre feminine",
							"16": "8088",
							"17": "0",
							"35": "0",
							"36": "0",
							"37": "0",
							"39": "0",
							"40": "les mamans du marché et les porteurs du marché ",
							"41": "0",
							"42": "0",
							"43": "0",
							"44": "0",
							"45": "0",
							"46": "avoir un grand restaurant moderne dans la ville",
							"47": "MALUEKI MANZAMBI SIMEONE",
							"48": "0893380610",
							"49": "ngufu 106 bumbu",
							"50": "potentiel",
							"55": "1",
							"56": "1"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/390",
						"otherData": {
							"desc": null,
							"stateMarital": "Veuve",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "1",
							"sectorServices": "0",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "",
							"affiliationStructure": "œuvre feminine",
							"turneOverAmount": "8088",
							"journalierStaff": "0",
							"pernanentStaff": "1",
							"familyStaff": "1",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "0",
							"financingDificuty": "0",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "0",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "0",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "0",
							"otherCustomer": "les mamans du marché et les porteurs du marché ",
							"visionManyBranches": "0",
							"visionDiversifyClient": "0",
							"visionUsePackaging": "0",
							"visionInprouveTurneOver": "0",
							"visionMakeFactory": "0",
							"visionOther": "avoir un grand restaurant moderne dans la ville",
							"otherContectNames": "MALUEKI MANZAMBI SIMEONE",
							"otherContectPhoneNumber": "0893380610",
							"otherContectAddress": "ngufu 106 bumbu",
							"instigatorOpinion": "potentiel"
						}
					}
				]
			},
			"housekeeping": {
				"id": 399,
				"NIM": "415633086",
				"reference": null,
				"address": {
					"id": 401,
					"line": "NGUFU N°106",
					"town": {
						"id": 10,
						"name": "Bumbu",
						"city": {
							"id": 10,
							"name": "kinshasa",
							"province": {
								"id": 10,
								"name": "Kinshasa",
								"iri": "\/api\/location\/provinces\/10"
							},
							"iri": "\/api\/cities\/10"
						},
						"iri": "\/api\/towns\/10"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/399"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:15:41+00:00",
				"updatedAt": "2024-05-07T08:23:47+00:00",
				"slug": "1eed0916-7198-60a2-a6b2-4505597586c9"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0893038828-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0893038828-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 641,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0893038828-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/641"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/390"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0893038828-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0893038828-profil.png"
		},
		{
			"id": 400,
			"latitude": -2.5065608,
			"longitude": 28.8618491,
			"altitude": 1517.8000488281,
			"isActive": true,
			"instigator": {
				"id": 292,
				"name": "Chibembe",
				"firstname": "Christophe",
				"lastname": "Christophe",
				"phoneNumber": "995170824",
				"location": []
			},
			"validateAt": "2024-04-29T17:19:26+00:00",
			"feedBack": null,
			"personnalIdentityData": {
				"name": "SHRIREGO ",
				"firstName": "Alexandrine",
				"lastName": "NABINTU",
				"sexe": "F",
				"phoneNumber": "0859320143",
				"birthdate": "1972-05-21T00:00:00+00:00",
				"nui": "720370341",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 4,
				"organization": null
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "3339448778",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "ENSHANO TWEZA",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Av.ISGA 148B",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/391",
						"otherData": {
							"desc": null,
							"stateMarital": null,
							"otherIDCard": null,
							"legalStatus": null,
							"sectorAgroForestry": null,
							"sectorIndustry": null,
							"sectorServices": null,
							"sectorGreeEconomy": null,
							"otherActivitySector": null,
							"transformFruitAndVegetableActivity": null,
							"juiceMakerActivity": null,
							"condimengActivity": null,
							"FumageSalaisonSechageActity": null,
							"otherActity": null,
							"affiliationStructure": null,
							"turneOverAmount": null,
							"journalierStaff": null,
							"pernanentStaff": null,
							"familyStaff": null,
							"concourFinancing": null,
							"padepmeFinancing": null,
							"otherFinancing": null,
							"haveCredit": null,
							"institutCredit": null,
							"amountCredit": null,
							"noDificuty": null,
							"trainningDificuty": null,
							"financingDificuty": null,
							"tracaserieDificuty": null,
							"marketAccessDificuty": null,
							"productionDificuty": null,
							"otherDificuty": null,
							"activityLinkwasteProcessing": null,
							"activityLinkImprovedStoves": null,
							"activityLinkRecycling": null,
							"otherActivityLink": null,
							"indidualCustomer": null,
							"supermarketCustomer": null,
							"businessCustomer": null,
							"onLineCustomer": null,
							"dealerCustomer": null,
							"otherCustomer": null,
							"visionManyBranches": null,
							"visionDiversifyClient": null,
							"visionUsePackaging": null,
							"visionInprouveTurneOver": null,
							"visionMakeFactory": null,
							"visionOther": null,
							"otherContectNames": null,
							"otherContectPhoneNumber": null,
							"otherContectAddress": null,
							"instigatorOpinion": null
						}
					}
				]
			},
			"housekeeping": {
				"id": 400,
				"NIM": "857624661",
				"reference": null,
				"address": {
					"id": 402,
					"line": "Av.ISGA N°148",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/400"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:15:44+00:00",
				"updatedAt": "2024-04-29T17:19:26+00:00",
				"slug": "1eed0916-8b0c-6b3c-9ee4-cf7d00621fd2"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/720370341.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/720370341.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 642,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/642"
							},
							{
								"id": 643,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/643"
							},
							{
								"id": 644,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/644"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/391"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/720370341.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/720370341.png"
		},
		{
			"id": 401,
			"latitude": -2.5408767,
			"longitude": 28.8608917,
			"altitude": 1253.3,
			"isActive": null,
			"instigator": {
				"id": 292,
				"name": "Chibembe",
				"firstname": "Christophe",
				"lastname": "Christophe",
				"phoneNumber": "995170824",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "SIFA ",
				"firstName": "Chantale",
				"lastName": "MURHEMULA",
				"sexe": "F",
				"phoneNumber": "0976540356",
				"birthdate": "1967-05-17T00:00:00+00:00",
				"nui": "558636866",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 7,
				"organization": null
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33517710742",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "Chez Mama Sifa",
						"creationYear": 2018,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Av. Mulengeza 1 N°A55",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/392",
						"otherData": {
							"desc": null,
							"stateMarital": null,
							"otherIDCard": null,
							"legalStatus": null,
							"sectorAgroForestry": null,
							"sectorIndustry": null,
							"sectorServices": null,
							"sectorGreeEconomy": null,
							"otherActivitySector": null,
							"transformFruitAndVegetableActivity": null,
							"juiceMakerActivity": null,
							"condimengActivity": null,
							"FumageSalaisonSechageActity": null,
							"otherActity": null,
							"affiliationStructure": null,
							"turneOverAmount": null,
							"journalierStaff": null,
							"pernanentStaff": null,
							"familyStaff": null,
							"concourFinancing": null,
							"padepmeFinancing": null,
							"otherFinancing": null,
							"haveCredit": null,
							"institutCredit": null,
							"amountCredit": null,
							"noDificuty": null,
							"trainningDificuty": null,
							"financingDificuty": null,
							"tracaserieDificuty": null,
							"marketAccessDificuty": null,
							"productionDificuty": null,
							"otherDificuty": null,
							"activityLinkwasteProcessing": null,
							"activityLinkImprovedStoves": null,
							"activityLinkRecycling": null,
							"otherActivityLink": null,
							"indidualCustomer": null,
							"supermarketCustomer": null,
							"businessCustomer": null,
							"onLineCustomer": null,
							"dealerCustomer": null,
							"otherCustomer": null,
							"visionManyBranches": null,
							"visionDiversifyClient": null,
							"visionUsePackaging": null,
							"visionInprouveTurneOver": null,
							"visionMakeFactory": null,
							"visionOther": null,
							"otherContectNames": null,
							"otherContectPhoneNumber": null,
							"otherContectAddress": null,
							"instigatorOpinion": null
						}
					}
				]
			},
			"housekeeping": {
				"id": 401,
				"NIM": "888127470",
				"reference": null,
				"address": {
					"id": 403,
					"line": "Mulengez 1\/ Q. Panzi",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/401"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:16:06+00:00",
				"updatedAt": "2024-03-13T15:25:38+00:00",
				"slug": "1eed0917-620d-664a-9fe2-6fffc1c64239"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/558636866.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/558636866.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 645,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/645"
							},
							{
								"id": 646,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/646"
							},
							{
								"id": 647,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/647"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/392"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/558636866.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/558636866.png"
		},
		{
			"id": 402,
			"latitude": -2.5046633,
			"longitude": 28.8702,
			"altitude": 1493,
			"isActive": true,
			"instigator": {
				"id": 292,
				"name": "Chibembe",
				"firstname": "Christophe",
				"lastname": "Christophe",
				"phoneNumber": "995170824",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "MALANGO ",
				"firstName": "Douce",
				"lastName": "KABANGI",
				"sexe": "F",
				"phoneNumber": "0977263906",
				"birthdate": "1990-05-05T00:00:00+00:00",
				"nui": "772603246",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 6,
				"organization": {
					"name": "fédération des entrepreneurs du congo\/ femmes entrepreneurs",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33647328909",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "God is Great",
						"creationYear": 2023,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Av. Paysage N°056",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/393",
						"otherData": {
							"desc": null,
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "1",
							"sectorIndustry": "0",
							"sectorServices": "1",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "Fabrication de désinfectant",
							"affiliationStructure": "Fec",
							"turneOverAmount": "300",
							"journalierStaff": "0",
							"pernanentStaff": "1",
							"familyStaff": "0",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "1",
							"marketAccessDificuty": "0",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "1",
							"businessCustomer": "1",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "THIERRY TITI MUYI",
							"otherContectPhoneNumber": "0994112455",
							"otherContectAddress": "Av. Paysage  N °052",
							"instigatorOpinion": "promoteuse"
						}
					},
					{
						"name": "God is Greast",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Av. Paysage N °52",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "1",
							"28": "0",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Mariée",
							"4": "Personne physique",
							"5": "1",
							"6": "0",
							"7": "1",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "Fabrication de désinfectant",
							"15": "Fec",
							"16": "300",
							"17": "0",
							"35": "1",
							"36": "1",
							"37": "1",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "THIERRY TITI MUYI",
							"48": "0994112455",
							"49": "Av. Paysage  N °052",
							"50": "promoteuse",
							"55": "1",
							"56": "0"
						},
						"yearOfLegalization": 2023,
						"iri": "\/api\/entrepreneurial_activities\/530"
					},
					{
						"name": "God is Greast",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Av. Paysage N °52",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "1",
							"28": "0",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Mariée",
							"4": "Personne physique",
							"5": "1",
							"6": "0",
							"7": "1",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "Fabrication de désinfectant",
							"15": "Fec",
							"16": "300",
							"17": "0",
							"35": "1",
							"36": "1",
							"37": "1",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "THIERRY TITI MUYI",
							"48": "0994112455",
							"49": "Av. Paysage  N °052",
							"50": "promoteuse",
							"55": "1",
							"56": "0"
						},
						"yearOfLegalization": 2023,
						"iri": "\/api\/entrepreneurial_activities\/628"
					}
				]
			},
			"housekeeping": {
				"id": 402,
				"NIM": "153485872",
				"reference": null,
				"address": {
					"id": 639,
					"line": "Av. Paysage N°052",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/402"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:16:23+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed0918-04ad-68be-9a68-4515f4f69a52"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 648,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/648"
							},
							{
								"id": 649,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/649"
							},
							{
								"id": 650,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/650"
							},
							{
								"id": 932,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/932"
							},
							{
								"id": 933,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-product-1.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/933"
							},
							{
								"id": 934,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-product-2.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/934"
							},
							{
								"id": 935,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-product-3.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/935"
							},
							{
								"id": 936,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-product-4.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/936"
							},
							{
								"id": 937,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-product-5.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/937"
							},
							{
								"id": 938,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-activity-0.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/938"
							},
							{
								"id": 939,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-activity-1.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/939"
							},
							{
								"id": 1093,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1093"
							},
							{
								"id": 1094,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-product-1.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1094"
							},
							{
								"id": 1095,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-product-2.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1095"
							},
							{
								"id": 1096,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-product-3.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1096"
							},
							{
								"id": 1097,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-product-4.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1097"
							},
							{
								"id": 1098,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-product-5.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1098"
							},
							{
								"id": 1099,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-activity-0.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/1099"
							},
							{
								"id": 1100,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-activity-1.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/1100"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/393"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/530"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/628"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0977263906-profil.png"
		},
		{
			"id": 403,
			"latitude": -4.3174025,
			"longitude": 15.3253757,
			"altitude": 0,
			"isActive": null,
			"instigator": {
				"id": 281,
				"name": "Batalungu",
				"firstname": "Albertine",
				"lastname": "Albertine",
				"phoneNumber": "897470964",
				"location": []
			},
			"validateAt": "2024-04-29T08:56:46+00:00",
			"feedBack": {
				"turneOverAmountMin": true,
				"photoProfileInvisible": true,
				"photoActivityInvisible": true
			},
			"personnalIdentityData": {
				"name": "batalungu",
				"firstName": "albertine",
				"lastName": "lubanzu",
				"sexe": "F",
				"phoneNumber": "0715699355",
				"birthdate": "1993-04-27T00:00:00+00:00",
				"nui": "272204244",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 5,
				"organization": {
					"name": "pole pole",
					"city": {
						"iri": "\/api\/cities\/10"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "30512755188",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "yaya business",
						"creationYear": 2020,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "croix rouge 45",
						"town": {
							"id": 9,
							"name": "Barumbu",
							"city": {
								"id": 10,
								"name": "kinshasa",
								"province": {
									"id": 10,
									"name": "Kinshasa",
									"iri": "\/api\/location\/provinces\/10"
								},
								"iri": "\/api\/cities\/10"
							},
							"iri": "\/api\/towns\/9"
						},
						"territory": null,
						"taxes": {
							"0": "patente et tiquet",
							"18": "0",
							"19": "1",
							"20": "Jamais reçu de financement",
							"21": "1",
							"22": "equity",
							"23": "2000",
							"24": "1",
							"25": "1",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "0",
							"30": "",
							"31": "0",
							"32": "1",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Celibataire",
							"4": "Personne physique",
							"5": "0",
							"6": "1",
							"7": "0",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "1",
							"13": "0",
							"14": "",
							"15": "pole pole",
							"16": "0130000",
							"17": "01",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "1",
							"40": "",
							"41": "0",
							"42": "1",
							"43": "1",
							"44": "0",
							"45": "0",
							"46": "avoir une grande pâtisserie moderne",
							"47": "Sylvain lubanzu",
							"48": "0719284123",
							"49": "kalemebe lembe 38",
							"50": "subsistance",
							"55": "03",
							"56": "05"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/394",
						"otherData": {
							"desc": null,
							"stateMarital": "Celibataire",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "1",
							"sectorServices": "0",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "1",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "",
							"affiliationStructure": "pole pole",
							"turneOverAmount": "0130000",
							"journalierStaff": "01",
							"pernanentStaff": "03",
							"familyStaff": "05",
							"concourFinancing": "0",
							"padepmeFinancing": "1",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "1",
							"institutCredit": "equity",
							"amountCredit": "2000",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "0",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "1",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "0",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "0",
							"visionMakeFactory": "0",
							"visionOther": "avoir une grande pâtisserie moderne",
							"otherContectNames": "Sylvain lubanzu",
							"otherContectPhoneNumber": "0719284123",
							"otherContectAddress": "kalemebe lembe 38",
							"instigatorOpinion": "subsistance"
						}
					}
				]
			},
			"housekeeping": {
				"id": 403,
				"NIM": "257811712",
				"reference": null,
				"address": {
					"id": 405,
					"line": "baraka A50",
					"town": {
						"id": 9,
						"name": "Barumbu",
						"city": {
							"id": 10,
							"name": "kinshasa",
							"province": {
								"id": 10,
								"name": "Kinshasa",
								"iri": "\/api\/location\/provinces\/10"
							},
							"iri": "\/api\/cities\/10"
						},
						"iri": "\/api\/towns\/9"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/403"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:19:20+00:00",
				"updatedAt": "2024-05-07T08:16:36+00:00",
				"slug": "1eed091e-96ea-6ba4-9ec9-f37d216e34ac"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0715699355-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0715699355-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 651,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0715699355-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/651"
							},
							{
								"id": 652,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0715699355-activity-0.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/652"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/394"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0715699355-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0715699355-profil.png"
		},
		{
			"id": 404,
			"latitude": -2.5207817,
			"longitude": 28.84632,
			"altitude": 1689.6,
			"isActive": true,
			"instigator": {
				"id": 19,
				"name": "NKULU",
				"firstname": "Shaky",
				"lastname": "SHAKYABO",
				"phoneNumber": "0982495676",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "FAZALI ",
				"firstName": "VANESSA ",
				"lastName": "MIHANDA ",
				"sexe": "F",
				"phoneNumber": "0853210590",
				"birthdate": "1986-04-15T00:00:00+00:00",
				"nui": "123340182",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 11,
				"organization": {
					"name": "chambre de commerce",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33655525842",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "vanessa briqueterie ",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "kabono",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/395",
						"otherData": {
							"desc": null,
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "1",
							"sectorServices": "1",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "production de brique ",
							"affiliationStructure": "chambre de commerce ",
							"turneOverAmount": "6000",
							"journalierStaff": "4",
							"pernanentStaff": "8",
							"familyStaff": "4",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "1",
							"institutCredit": "Banque ",
							"amountCredit": "4500",
							"noDificuty": "1",
							"trainningDificuty": "1",
							"financingDificuty": "0",
							"tracaserieDificuty": "1",
							"marketAccessDificuty": "1",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "1",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "JACQUE LUKUTE ",
							"otherContectPhoneNumber": "0997889294",
							"otherContectAddress": "kabono quartier kasi ",
							"instigatorOpinion": "mature"
						}
					},
					{
						"name": "vannessa briqueterie ",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "kasai",
						"town": {
							"id": 70,
							"name": "Kadutu",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/70"
						},
						"territory": null,
						"taxes": {
							"0": "DPMR ",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "1",
							"22": "Banque ",
							"23": "4500",
							"24": "1",
							"25": "1",
							"26": "0",
							"27": "1",
							"28": "1",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "1",
							"34": ""
						},
						"activities": {
							"2": "Mariée",
							"4": "Personne physique",
							"5": "0",
							"6": "1",
							"7": "1",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "production de brique ",
							"15": "chambre de commerce ",
							"16": "6000",
							"17": "4",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "JACQUE LUKUTE ",
							"48": "0997889294",
							"49": "kabono quartier kasi ",
							"50": "mature",
							"55": "8",
							"56": "4"
						},
						"yearOfLegalization": 2021,
						"iri": "\/api\/entrepreneurial_activities\/899"
					}
				]
			},
			"housekeeping": {
				"id": 404,
				"NIM": "260002627",
				"reference": null,
				"address": {
					"id": 910,
					"line": "kabono ",
					"town": {
						"id": 70,
						"name": "Kadutu",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/70"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/404"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:23:57+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed0928-f1c3-62ce-b656-a959fa61061c"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0853210590-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0853210590-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 653,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/653"
							},
							{
								"id": 654,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/654"
							},
							{
								"id": 655,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/655"
							},
							{
								"id": 1537,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0853210590-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1537"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/395"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/899"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0853210590-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0853210590-profil.png"
		},
		{
			"id": 405,
			"latitude": -2.495285,
			"longitude": 28.8509617,
			"altitude": 1458.3,
			"isActive": true,
			"instigator": {
				"id": 19,
				"name": "NKULU",
				"firstname": "Shaky",
				"lastname": "SHAKYABO",
				"phoneNumber": "0982495676",
				"location": []
			},
			"validateAt": "2024-03-29T11:16:29+00:00",
			"feedBack": null,
			"personnalIdentityData": {
				"name": "MAKO ",
				"firstName": "MATHILDE ",
				"lastName": "BADOSANYA ",
				"sexe": "F",
				"phoneNumber": "0998391737",
				"birthdate": "2002-07-03T00:00:00+00:00",
				"nui": "735362248",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 11,
				"organization": {
					"name": "independant",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33641720552",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "Mathilde pâtisserie ",
						"creationYear": 2020,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "quartier latin ",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/396",
						"otherData": {
							"desc": null,
							"stateMarital": "Celibataire",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "1",
							"sectorIndustry": "0",
							"sectorServices": "0",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "transformation de biscuits ",
							"affiliationStructure": "",
							"turneOverAmount": "0",
							"journalierStaff": "4",
							"pernanentStaff": "4",
							"familyStaff": "0",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "0",
							"financingDificuty": "0",
							"tracaserieDificuty": "1",
							"marketAccessDificuty": "0",
							"productionDificuty": "0",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "1",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "YVES BODJO ",
							"otherContectPhoneNumber": "0998637700",
							"otherContectAddress": "quartier latin ",
							"instigatorOpinion": "subsistance"
						}
					},
					{
						"name": "Mathilde pâtisserie ",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "quartier latin ",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "0",
							"26": "0",
							"27": "1",
							"28": "0",
							"29": "0",
							"30": "",
							"31": "0",
							"32": "1",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Celibataire",
							"4": "Personne physique",
							"5": "1",
							"6": "0",
							"7": "0",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "transformation de biscuits ",
							"15": "",
							"16": "0",
							"17": "4",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "YVES BODJO ",
							"48": "0998637700",
							"49": "quartier latin ",
							"50": "subsistance",
							"55": "4",
							"56": "0"
						},
						"yearOfLegalization": 2022,
						"iri": "\/api\/entrepreneurial_activities\/500"
					}
				]
			},
			"housekeeping": {
				"id": 405,
				"NIM": "634534367",
				"reference": null,
				"address": {
					"id": 511,
					"line": "MUHUNGU METEO ",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/405"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:24:43+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed092a-a53f-6022-9ac7-adea4ce01ad5"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0998391737-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0998391737-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 656,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/656"
							},
							{
								"id": 657,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/657"
							},
							{
								"id": 658,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/658"
							},
							{
								"id": 881,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0998391737-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/881"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/396"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/500"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0998391737-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0998391737-profil.png"
		},
		{
			"id": 406,
			"latitude": -2.5110533,
			"longitude": 28.8564047,
			"altitude": 1561,
			"isActive": true,
			"instigator": {
				"id": 19,
				"name": "NKULU",
				"firstname": "Shaky",
				"lastname": "SHAKYABO",
				"phoneNumber": "0982495676",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "RUBABURA",
				"firstName": "GRÂCE ",
				"lastName": "NABINTU",
				"sexe": "F",
				"phoneNumber": "0995580288",
				"birthdate": "1995-08-25T00:00:00+00:00",
				"nui": "682325723",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 6,
				"organization": {
					"name": "chambre de commerce",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33420905327",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "JACK S A R L ",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "de la poste ",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/397",
						"otherData": {
							"desc": null,
							"stateMarital": "Celibataire",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "1",
							"sectorServices": "1",
							"sectorGreeEconomy": "1",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "production et commercialisation de craie ",
							"affiliationStructure": "chambre de commerce ",
							"turneOverAmount": "8500",
							"journalierStaff": "5",
							"pernanentStaff": "1",
							"familyStaff": "1",
							"concourFinancing": "0",
							"padepmeFinancing": "0",
							"otherFinancing": "Autre source de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "0",
							"trainningDificuty": "1",
							"financingDificuty": "1",
							"tracaserieDificuty": "1",
							"marketAccessDificuty": "1",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "1",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "NDAMUSO MARIE IRENE ",
							"otherContectPhoneNumber": "0970024257",
							"otherContectAddress": "av de la poste",
							"instigatorOpinion": "mature"
						}
					},
					{
						"name": "jack sarl",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "de la poste ",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "IPR ET IBP",
							"18": "0",
							"19": "0",
							"20": "Autre source de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "0",
							"25": "1",
							"26": "1",
							"27": "1",
							"28": "1",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Celibataire",
							"4": "Personne physique",
							"5": "0",
							"6": "1",
							"7": "1",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "production de craie ",
							"15": "chambre de commerce ",
							"16": "8500",
							"17": "5",
							"35": "1",
							"36": "0",
							"37": "1",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "NDAMUSO MARIE IRENE ",
							"48": "0970024257",
							"49": "Avenue de la poste n°18",
							"50": "mature",
							"55": "1",
							"56": "1"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/879"
					},
					{
						"name": "jack sarl",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "de la poste ",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "IPR ET IBP",
							"18": "0",
							"19": "0",
							"20": "Autre source de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "0",
							"25": "1",
							"26": "1",
							"27": "1",
							"28": "1",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Celibataire",
							"4": "Personne physique",
							"5": "0",
							"6": "1",
							"7": "1",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "production de craie ",
							"15": "chambre de commerce ",
							"16": "8500",
							"17": "5",
							"35": "1",
							"36": "0",
							"37": "1",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "NDAMUSO MARIE IRENE ",
							"48": "0970024257",
							"49": "Avenue de la poste n°18",
							"50": "mature",
							"55": "1",
							"56": "1"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/880"
					},
					{
						"name": "JACK SARL",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "de la poste",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "IPR ET IBP ",
							"18": "0",
							"19": "0",
							"20": "Autre source de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "0",
							"25": "1",
							"26": "1",
							"27": "1",
							"28": "1",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Celibataire",
							"4": "Personne physique",
							"5": "0",
							"6": "1",
							"7": "1",
							"8": "1",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "production et commercialisation de craie ",
							"15": "chambre de commerce ",
							"16": "8500",
							"17": "5",
							"35": "1",
							"36": "0",
							"37": "1",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "NDAMUSO MARIE IRENE ",
							"48": "0970024257",
							"49": "av de la poste",
							"50": "mature",
							"55": "1",
							"56": "1"
						},
						"yearOfLegalization": 2019,
						"iri": "\/api\/entrepreneurial_activities\/896"
					}
				]
			},
			"housekeeping": {
				"id": 406,
				"NIM": "032751433",
				"reference": null,
				"address": {
					"id": 907,
					"line": "kibombo ",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/406"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:24:58+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed092b-367c-601c-b7cc-d3aa3d9d8915"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995580288-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995580288-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 659,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/659"
							},
							{
								"id": 660,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/660"
							},
							{
								"id": 661,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/661"
							},
							{
								"id": 1505,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995580288-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1505"
							},
							{
								"id": 1506,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995580288-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1506"
							},
							{
								"id": 1507,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995580288-activity-0.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/1507"
							},
							{
								"id": 1532,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995580288-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/1532"
							},
							{
								"id": 1533,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995580288-activity-0.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/1533"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/397"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/879"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/880"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/896"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995580288-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0995580288-profil.png"
		},
		{
			"id": 407,
			"latitude": -2.4992216,
			"longitude": 28.8864401,
			"altitude": 0,
			"isActive": true,
			"instigator": {
				"id": 267,
				"name": "Safi",
				"firstname": "Christelle",
				"lastname": "Salima",
				"phoneNumber": "826221266",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "Bahati",
				"firstName": "Sophie",
				"lastName": "Bisimwa",
				"sexe": "F",
				"phoneNumber": "0898401242",
				"birthdate": "1980-07-08T00:00:00+00:00",
				"nui": "833573618",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 9,
				"organization": {
					"name": "AVEC",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33641922336",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "ujitegemeye",
						"creationYear": 2022,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "kajangu numero 83",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/398",
						"otherData": {
							"desc": null,
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "1",
							"sectorIndustry": "0",
							"sectorServices": "0",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "fabrication de craies",
							"affiliationStructure": "AVEC",
							"turneOverAmount": "3000",
							"journalierStaff": "0",
							"pernanentStaff": "4",
							"familyStaff": "3",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "0",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "0",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "0",
							"otherCustomer": "les écoles",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "0",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "Nfundiko Louis",
							"otherContectPhoneNumber": "0842094344",
							"otherContectAddress": "kajungu(somenki) numéro 83",
							"instigatorOpinion": "promoteuse"
						}
					},
					{
						"name": "ujitegemeye",
						"creationYear": 2022,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "kajangu( somenki) numéro 83",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "0",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "1",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Mariée",
							"4": "Personne physique",
							"5": "1",
							"6": "0",
							"7": "0",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "fabrication de craies",
							"15": "AVEC",
							"16": "3000",
							"17": "0",
							"35": "0",
							"36": "0",
							"37": "0",
							"39": "0",
							"40": "les écoles",
							"41": "1",
							"42": "1",
							"43": "0",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "Nfundiko Louis",
							"48": "0842094344",
							"49": "kajungu(somenki) numéro 83",
							"50": "promoteuse",
							"55": "4",
							"56": "3"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/550"
					}
				]
			},
			"housekeeping": {
				"id": 407,
				"NIM": "343881364",
				"reference": null,
				"address": {
					"id": 561,
					"line": "kajangu(somenki) numéro 83 \/ Nyalukemba",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/407"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:25:04+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed092b-6c00-65da-bb71-2b119a158e87"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0898401242-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0898401242-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 662,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/662"
							},
							{
								"id": 663,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/663"
							},
							{
								"id": 664,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/664"
							},
							{
								"id": 971,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0898401242-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/971"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/398"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/550"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0898401242-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0898401242-profil.png"
		},
		{
			"id": 408,
			"latitude": -2.499864,
			"longitude": 28.8824355,
			"altitude": 0,
			"isActive": true,
			"instigator": {
				"id": 267,
				"name": "Safi",
				"firstname": "Christelle",
				"lastname": "Salima",
				"phoneNumber": "826221266",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "Engalo",
				"firstName": "Rolande",
				"lastName": "Murhabazi ",
				"sexe": "F",
				"phoneNumber": "0997162559",
				"birthdate": "1997-11-06T00:00:00+00:00",
				"nui": "138434568",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 9,
				"organization": {
					"name": "AVEC",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33067281833",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "la djoie",
						"creationYear": 2000,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Evariste banganga numero 46",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 2008,
						"iri": "\/api\/entrepreneurial_activities\/399",
						"otherData": {
							"desc": null,
							"stateMarital": "Celibataire",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "1",
							"sectorIndustry": "0",
							"sectorServices": "0",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "1",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "",
							"affiliationStructure": "AVEC",
							"turneOverAmount": "3000",
							"journalierStaff": "0",
							"pernanentStaff": "4",
							"familyStaff": "0",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "0",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "1",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "1",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "0",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "0",
							"otherCustomer": "les céremonies",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "1",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "Faida Marie-rose",
							"otherContectPhoneNumber": "0997228262",
							"otherContectAddress": "avenue Evariste numéro 46 ibanda",
							"instigatorOpinion": "mature"
						}
					},
					{
						"name": "ladjoie",
						"creationYear": 2021,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Evariste numéro 46 Nyalukemba",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "0",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "1",
							"30": "",
							"31": "1",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Celibataire",
							"4": "Personne physique",
							"5": "1",
							"6": "0",
							"7": "0",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "1",
							"12": "0",
							"13": "0",
							"14": "",
							"15": "AVEC",
							"16": "3000",
							"17": "0",
							"35": "0",
							"36": "0",
							"37": "0",
							"39": "0",
							"40": "les céremonies",
							"41": "1",
							"42": "1",
							"43": "1",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "Faida Marie-rose",
							"48": "0997228262",
							"49": "avenue Evariste numéro 46 ibanda",
							"50": "mature",
							"55": "4",
							"56": "0"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/549"
					}
				]
			},
			"housekeeping": {
				"id": 408,
				"NIM": "872332674",
				"reference": null,
				"address": {
					"id": 560,
					"line": "semliki numéro 46",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/408"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:25:36+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed092c-9b50-6d48-8f7d-8545f9c84bbb"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0997162559-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0997162559-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 665,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/665"
							},
							{
								"id": 666,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/666"
							},
							{
								"id": 667,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/667"
							},
							{
								"id": 969,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0997162559-activity-0.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/969"
							},
							{
								"id": 970,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0997162559-activity-1.png",
								"documentType": {
									"id": 7,
									"wording": "produit secondaire",
									"iri": "\/api\/document_types\/7"
								},
								"iri": "\/api\/documents\/970"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/399"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/549"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0997162559-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0997162559-profil.png"
		},
		{
			"id": 409,
			"latitude": -2.4991293,
			"longitude": 28.8832007,
			"altitude": 1523.8000488281,
			"isActive": true,
			"instigator": {
				"id": 267,
				"name": "Safi",
				"firstname": "Christelle",
				"lastname": "Salima",
				"phoneNumber": "826221266",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "Musimwa",
				"firstName": "Juliette",
				"lastName": "katabana",
				"sexe": "F",
				"phoneNumber": "0974788414",
				"birthdate": "1992-12-09T00:00:00+00:00",
				"nui": "031762265",
				"levelStudy": {
					"id": 4,
					"libelle": "Universitaire",
					"iri": "\/api\/level_studies\/4"
				},
				"householdSize": 7,
				"organization": {
					"name": "independant",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33613303362",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "Elsa",
						"creationYear": 2022,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Tanganyka numero 19",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/400",
						"otherData": {
							"desc": null,
							"stateMarital": "Mariée",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "0",
							"sectorServices": "1",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "savonnerie ",
							"affiliationStructure": "",
							"turneOverAmount": "2000",
							"journalierStaff": "0",
							"pernanentStaff": "2",
							"familyStaff": "1",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "0",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "0",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "1",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "1",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "0",
							"visionUsePackaging": "0",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "Chentwadi Serge",
							"otherContectPhoneNumber": "0977335269",
							"otherContectAddress": "",
							"instigatorOpinion": "potentiel"
						}
					},
					{
						"name": "Elsa",
						"creationYear": 2023,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "Tanganyka numéro 19",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "0",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "0",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Mariée",
							"4": "Personne physique",
							"5": "0",
							"6": "0",
							"7": "1",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "savonnerie ",
							"15": "",
							"16": "2000",
							"17": "0",
							"35": "1",
							"36": "1",
							"37": "0",
							"39": "1",
							"40": "",
							"41": "1",
							"42": "0",
							"43": "0",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "Chentwadi Serge",
							"48": "0977335269",
							"49": "",
							"50": "potentiel",
							"55": "2",
							"56": "1"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/551"
					}
				]
			},
			"housekeeping": {
				"id": 409,
				"NIM": "655686337",
				"reference": null,
				"address": {
					"id": 562,
					"line": "tanganyka numéro 19",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/409"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:26:04+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed092d-a5e9-62ae-85f2-afb96be1937c"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0974788414-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0974788414-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 668,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/668"
							},
							{
								"id": 669,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/669"
							},
							{
								"id": 670,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/670"
							},
							{
								"id": 972,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0974788414-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/972"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/400"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/551"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0974788414-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0974788414-profil.png"
		},
		{
			"id": 410,
			"latitude": -2.49964,
			"longitude": 28.8811081,
			"altitude": 1575.3,
			"isActive": true,
			"instigator": {
				"id": 267,
				"name": "Safi",
				"firstname": "Christelle",
				"lastname": "Salima",
				"phoneNumber": "826221266",
				"location": []
			},
			"validateAt": null,
			"feedBack": null,
			"personnalIdentityData": {
				"name": "Nshobole ",
				"firstName": "Agnes",
				"lastName": "Rhuhunemungu",
				"sexe": "F",
				"phoneNumber": "0996980499",
				"birthdate": "2003-04-01T00:00:00+00:00",
				"nui": "785071542",
				"levelStudy": {
					"id": 3,
					"libelle": "Secondaire",
					"iri": "\/api\/level_studies\/3"
				},
				"householdSize": 7,
				"organization": {
					"name": "AVEC",
					"city": {
						"iri": "\/api\/cities\/28"
					}
				}
			},
			"pieceOfIdentificationData": {
				"numberPieceOfIdentification": "33620117802",
				"typePieceOfIdentification": {
					"id": 1,
					"libelle": "carte d'electeur",
					"iri": "\/api\/piece_identification_types\/1"
				}
			},
			"activityData": {
				"AgriculturalActivity": [],
				"fichingactivity": [],
				"raisingactivity": [],
				"entrepreneurialActivities": [
					{
						"name": "la perla",
						"creationYear": 2023,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "tanganyka numero 2",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": null,
						"activities": null,
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/401",
						"otherData": {
							"desc": null,
							"stateMarital": "Celibataire",
							"otherIDCard": null,
							"legalStatus": "Personne physique",
							"sectorAgroForestry": "0",
							"sectorIndustry": "0",
							"sectorServices": "1",
							"sectorGreeEconomy": "0",
							"otherActivitySector": "",
							"transformFruitAndVegetableActivity": "0",
							"juiceMakerActivity": "0",
							"condimengActivity": "0",
							"FumageSalaisonSechageActity": "0",
							"otherActity": "fabrication des objets en perle",
							"affiliationStructure": "AVEC",
							"turneOverAmount": "2000",
							"journalierStaff": "0",
							"pernanentStaff": "2",
							"familyStaff": "0",
							"concourFinancing": "1",
							"padepmeFinancing": "0",
							"otherFinancing": "Jamais reçu de financement",
							"haveCredit": "0",
							"institutCredit": "",
							"amountCredit": "0",
							"noDificuty": "1",
							"trainningDificuty": "0",
							"financingDificuty": "1",
							"tracaserieDificuty": "0",
							"marketAccessDificuty": "0",
							"productionDificuty": "0",
							"otherDificuty": "",
							"activityLinkwasteProcessing": "0",
							"activityLinkImprovedStoves": "0",
							"activityLinkRecycling": "0",
							"otherActivityLink": "",
							"indidualCustomer": "1",
							"supermarketCustomer": "0",
							"businessCustomer": "0",
							"onLineCustomer": null,
							"dealerCustomer": "0",
							"otherCustomer": "",
							"visionManyBranches": "1",
							"visionDiversifyClient": "1",
							"visionUsePackaging": "0",
							"visionInprouveTurneOver": "1",
							"visionMakeFactory": "1",
							"visionOther": "",
							"otherContectNames": "Nyabintu katabana",
							"otherContectPhoneNumber": "0994177555",
							"otherContectAddress": "tanganyka numéro 2",
							"instigatorOpinion": "mature"
						}
					},
					{
						"name": "la perle",
						"creationYear": 2022,
						"isRegistered": null,
						"haveConstitutiveAct": null,
						"haveInternalRegulations": null,
						"haveAdministrationProceduresManual": null,
						"haveFinanceProceduresManual": null,
						"haveManagementConsultancy": null,
						"haveAccounting": null,
						"documentType": "RCCM",
						"countVolunteerStaff": null,
						"countStaffPaid": null,
						"yearFirstTaxPayment": null,
						"taxeAmount": null,
						"taxePayMode": null,
						"useMobileBank": null,
						"useCommercialBank": null,
						"useMicrofinance": null,
						"addressLine": "tanganyka numéro 2",
						"town": {
							"id": 69,
							"name": "Ibanda",
							"city": {
								"id": 28,
								"name": "Bukavu",
								"province": {
									"id": 22,
									"name": "Sud-Kivu",
									"iri": "\/api\/location\/provinces\/22"
								},
								"iri": "\/api\/cities\/28"
							},
							"iri": "\/api\/towns\/69"
						},
						"territory": null,
						"taxes": {
							"0": "",
							"18": "1",
							"19": "0",
							"20": "Jamais reçu de financement",
							"21": "0",
							"22": "",
							"23": "0",
							"24": "1",
							"25": "0",
							"26": "1",
							"27": "0",
							"28": "0",
							"29": "0",
							"30": "",
							"31": "0",
							"32": "0",
							"33": "0",
							"34": ""
						},
						"activities": {
							"2": "Celibataire",
							"4": "Personne physique",
							"5": "0",
							"6": "0",
							"7": "1",
							"8": "0",
							"9": "",
							"10": "0",
							"11": "0",
							"12": "0",
							"13": "0",
							"14": "fabrication des objets en perle",
							"15": "AVEC",
							"16": "2000",
							"17": "0",
							"35": "1",
							"36": "0",
							"37": "0",
							"39": "0",
							"40": "",
							"41": "1",
							"42": "1",
							"43": "0",
							"44": "1",
							"45": "1",
							"46": "",
							"47": "Nyabintu katabana",
							"48": "0994177555",
							"49": "tanganyka numéro 2",
							"50": "mature",
							"55": "2",
							"56": "0"
						},
						"yearOfLegalization": 0,
						"iri": "\/api\/entrepreneurial_activities\/552"
					}
				]
			},
			"housekeeping": {
				"id": 410,
				"NIM": "317120246",
				"reference": null,
				"address": {
					"id": 563,
					"line": "Tanganyka numéro 2",
					"town": {
						"id": 69,
						"name": "Ibanda",
						"city": {
							"id": 28,
							"name": "Bukavu",
							"province": {
								"id": 22,
								"name": "Sud-Kivu",
								"iri": "\/api\/location\/provinces\/22"
							},
							"iri": "\/api\/cities\/28"
						},
						"iri": "\/api\/towns\/69"
					},
					"sector": null
				},
				"iri": "\/api\/house_keepings\/410"
			},
			"timestamp": {
				"createdAt": "2024-02-21T08:26:21+00:00",
				"updatedAt": "2024-05-04T16:16:21+00:00",
				"slug": "1eed092e-4911-67e2-9cac-7b82d1093273"
			},
			"images": {
				"photoPieceOfIdentification": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0996980499-document.png",
				"incumbentPhoto": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0996980499-profil.png"
			},
			"documents": {
				"entrepreneurialActivities": [
					{
						"documents": [
							{
								"id": 671,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/rccm.png",
								"documentType": {
									"id": 1,
									"wording": "RCCM",
									"iri": "\/api\/document_types\/1"
								},
								"iri": "\/api\/documents\/671"
							},
							{
								"id": 672,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/f92.png",
								"documentType": {
									"id": 2,
									"wording": "F92",
									"iri": "\/api\/document_types\/2"
								},
								"iri": "\/api\/documents\/672"
							},
							{
								"id": 673,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/statut.png",
								"documentType": {
									"id": 5,
									"wording": "Status",
									"iri": "\/api\/document_types\/5"
								},
								"iri": "\/api\/documents\/673"
							},
							{
								"id": 973,
								"path": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0996980499-product-0.png",
								"documentType": {
									"id": 6,
									"wording": "produit principal",
									"iri": "\/api\/document_types\/6"
								},
								"iri": "\/api\/documents\/973"
							}
						],
						"iri": "\/api\/entrepreneurial_activities\/401"
					},
					{
						"documents": [],
						"iri": "\/api\/entrepreneurial_activities\/552"
					}
				]
			},
			"photoPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0996980499-profil.png",
			"photoNormalPath": "https:\/\/storage.cloud.google.com\/agromwinda_platform\/0996980499-profil.png"
		}
	],
	"totalItems": 68418,
	"lastPage": 2281
}

  ```
