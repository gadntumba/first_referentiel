# Create productor

```js

    const data = new FormData();
    // localisation

    data.append("longitude", "0");
    data.append("latitude", "0");
    data.append("altitude", "0");

    // identité personnel

    data.append("personnalIdentityData[name]", "Nkusu");
    data.append("personnalIdentityData[firstName]", "Michée");
    data.append("personnalIdentityData[lastName]", "Ndongala");
    data.append("personnalIdentityData[sexe]", "M");
    data.append("personnalIdentityData[phone]", "0824019836");
    // Numéro d'identification unique (9 chiffre)
    data.append("personnalIdentityData[nui]", "912233333");
    //Taille du ménage (Nombre de personne dans la maison)
    data.append("personnalIdentityData[householdSize]", "5");
    // Niveau d'étude (A recupérer coté serveur)
    data.append("personnalIdentityData[levelStudy]", "/api/level_studies/1");
    // date de naissance
    data.append("personnalIdentityData[birthday]", "1995-12-23");

    //photo de la dame (un fichier image)
    data.append("incumbentPhoto", "images/vue-bon-reception.png");
    // 
    // Adresse de la personne
    //numero du menage  (unique)
    data.append("houseKeeping[NIM]", "344444");
    // Commune ou secteur (A recupérer au serveur)
    data.append("houseKeeping[address][town]", "/api/towns/1");
    // Adresse précise
    data.append("houseKeeping[address][line]", "maringa lubefu");

    //photo de la pièce d'identité (un fichier image)
    data.append("photoPieceOfIdentification", "images/Capture d’écran du 2023-10-08 17-03-51.png");
    // type de carte (A recupérer au serveur)
    data.append("pieceOfIdentificationData[pieceIdentificationType]", "/api/piece_identification_types/1");
    // Numéro de la pièce
    data.append("pieceOfIdentificationData[pieceId]", "55555-4444-1234");

    // information sur l'oragnisation de la personne 
    // Nom de l'organisation
    data.append("activityData[entrepreneurships][0][name]", "Chez maman lili");
    // Année de création
    data.append("activityData[entrepreneurships][0][creationYear]", "2014");
    // L'organisation est enregistré
    data.append("activityData[entrepreneurships][0][isRegistered]", "0");
    // L'organisation a une Acte constitutive
    data.append("activityData[entrepreneurships][0][haveConstitutiveAct]", "1");
    // L'organisation a une réglation interne
    data.append("activityData[entrepreneurships][0][haveInternalRegulations]", "0");
    // L'organisation a un procedure adminitratif
    data.append("activityData[entrepreneurships][0][haveAdministrationProceduresManual]", "1");
    // L'organisation a un manuel de procèdure finacière
    data.append("activityData[entrepreneurships][0][haveFinanceProceduresManual]", "0");
    //L'organisation a un conseil d'éxecutif
    data.append("activityData[entrepreneurships][0][haveManagementConsultancy]", "1");
    //L'organisation a une comptabilité
    data.append("activityData[entrepreneurships][0][haveAccounting]", "1");
    //Le document que l'entreprise peut possder (RCCM ou F92)
    data.append("activityData[entrepreneurships][0][documentType]", "RCCM");
    //Photo du document
    data.append("activityData[entrepreneurships][0][documentPhoto]", "/home/nkusu/Téléchargements/WhatsApp Image 2023-11-22 at 8.39.01 AM.jpeg");
    //Nombre des staffs volontaire
    data.append("activityData[entrepreneurships][0][countVolunteerStaff]", "3");
    //Photo des staffs payés
    data.append("activityData[entrepreneurships][0][countStaffPaid]", "2");
    //Année de la prrmière que l'organisation à payer un staff
    data.append("activityData[entrepreneurships][0][yearFirstTaxPayment]", "2014");
    //Les nom des taxes qu'ils payent
    data.append("activityData[entrepreneurships][0][taxeNames]", "Taxe d'avenu");
    //Si l'organisation paye le taxe journalière, il paye combien en franc
    data.append("activityData[entrepreneurships][0][amountPaidDay]", "23");
    //Si l'organisation paye le taxe chaque mois, il paye combien en franc
    data.append("activityData[entrepreneurships][0][amountPaidMonth]", "3000");
    //Si l'organisation paye le taxe chaque trimestre, il paye combien en franc
    data.append("activityData[entrepreneurships][0][amountPaidQuarter]", "23.45");
    //Si l'organisation paye le taxe chaque semestre, il paye combien en franc
    data.append("activityData[entrepreneurships][0][amountPaidSemester]", "4500.34");
    //Si l'organisation paye le taxe chaquqe année, il paye combien en franc
    data.append("activityData[entrepreneurships][0][amountPaidAnnually]", "35000");
    //l'organisation utilise un mobile banque? (M-pesa, Orange Money etc. )
    data.append("activityData[entrepreneurships][0][useMobileBank]", "0");
    //l'organisation utilise une banque commercial? (Equity, Rawbanque, ecobank, FirstBanque etc. )
    data.append("activityData[entrepreneurships][0][useCommercialBank]", "1");
    //l'organisation utilise une microfinace? (Finca, Bilanga yampa, GuiGal etc. )
    data.append("activityData[entrepreneurships][0][useMicrofinance]", "0");
    // Commune ou Territoire où se trouve l'organisation (Voir le serveur)
    data.append("activityData[entrepreneurships][0][town]", "/api/towns/1");
    //Adresse precise de l'organisation
    data.append("activityData[entrepreneurships][0][addressLine]", "Boyata No 20");
    //Chiffre d'affaire de l'organisation (Voir serveur)
    data.append("activityData[entrepreneurships][0][turnover]", "/api/turnover_ranges/1");
    //Status l'egale de l'organisation (Voir serveur)
    data.append("activityData[entrepreneurships][0][legalStatus]", "/api/legal_statuses/1");
    //Mode d'exposition des produits (Voir serveur)
    data.append("activityData[entrepreneurships][0][productDisplayMode]", "/api/product_display_modes/1");

    const xhr = new XMLHttpRequest();
    xhr.withCredentials = true;

    xhr.addEventListener("readystatechange", function () {
    if (this.readyState === this.DONE) {
        console.log(this.responseText);
    }
    });

    xhr.open("POST", "http://productor.surintrants.com/api/productors");
    xhr.setRequestHeader("User-Agent", "insomnia/8.4.5");
    xhr.setRequestHeader("Authorization", "Bearer {your token}");

    xhr.send(data);


```