# Create productor

```js

const data = new FormData();

data.append("longitude", "0");
data.append("latitude", "0");
data.append("altitude", "0");


data.append("personnalIdentityData[name]", "Balu");
data.append("personnalIdentityData[firstName]", "Felicien");
data.append("personnalIdentityData[lastName]", "Triller");
data.append("personnalIdentityData[sexe]", "M");
data.append("personnalIdentityData[phone]", "0839021131");
data.append("personnalIdentityData[nui]", "895443311");
// nombre des personnes de le menages
data.append("personnalIdentityData[householdSize]", "5");
data.append("personnalIdentityData[levelStudy]", "/api/level_studies/1");
data.append("personnalIdentityData[birthday]", "1995-12-23");
data.append("incumbentPhoto", "/home/nkusu/Images/vue-bon-reception.png");

//menages
data.append("houseKeeping[NIM]", "34113713");
data.append("houseKeeping[address][line]", "maringa lubefu");
data.append("houseKeeping[address][town]", "/api/towns/1");

data.append("pieceOfIdentificationData[pieceIdentificationType]", "/api/piece_identification_types/1");
data.append("pieceOfIdentificationData[pieceId]", "98544-4EE4-1231");
data.append("photoPieceOfIdentification", "/home/nkusu/Images/Capture d’écran du 2023-10-08 17-03-51.png");


data.append("activityData[entrepreneurships][0][name]", "Chez maman lili");
// Liste des activités de l'entreprise
data.append("activityData[entrepreneurships][0][activities][0]", "Vente des matériaux de construction");
data.append("activityData[entrepreneurships][0][activities][1]", "Vente des chaises plastiques");
//Année de création
data.append("activityData[entrepreneurships][0][creationYear]", "2014");
//Année de légalisation
data.append("activityData[entrepreneurships][0][yearOfLegalization]", "2021");

//Est enregistré
data.append("activityData[entrepreneurships][0][isRegistered]", "0");
//A une acte constructif
data.append("activityData[entrepreneurships][0][haveConstitutiveAct]", "1");
// A une regution interne
data.append("activityData[entrepreneurships][0][haveInternalRegulations]", "0");
// A une procedure adminitratif
data.append("activityData[entrepreneurships][0][haveAdministrationProceduresManual]", "1");
// A un manuel de procedure adminitratif
data.append("activityData[entrepreneurships][0][haveFinanceProceduresManual]", "0");
// A un conseil de gestion
data.append("activityData[entrepreneurships][0][haveManagementConsultancy]", "1");
// A une comptabilité
data.append("activityData[entrepreneurships][0][haveAccounting]", "1");

// Status legal ONG, SARL, SARLU
data.append("activityData[entrepreneurships][0][legalStatus]", "/api/legal_statuses/1");
// Type de document F92 ou RCCM
data.append("activityData[entrepreneurships][0][documentType]", "RCCM");
// Photo du document
data.append("activityData[entrepreneurships][0][documentPhoto]", "/home/nkusu/Téléchargements/WhatsApp Image 2023-11-22 at 8.39.01 AM.jpeg");
// Nombre de staff volontaire
data.append("activityData[entrepreneurships][0][countVolunteerStaff]", "3");
// Nombre de staff payé
data.append("activityData[entrepreneurships][0][countStaffPaid]", "2");

data.append("activityData[entrepreneurships][0][useMobileBank]", "0");
data.append("activityData[entrepreneurships][0][useCommercialBank]", "1");
data.append("activityData[entrepreneurships][0][useMicrofinance]", "0");

//Chiffre d'affaire
data.append("activityData[entrepreneurships][0][turnover]", "/api/turnover_ranges/1");
// Mode d'exposition
data.append("activityData[entrepreneurships][0][productDisplayMode]", "/api/product_display_modes/1");

// Adresse de l'activité
data.append("activityData[entrepreneurships][0][addressLine]", "Boyata No 20");
// Commune ou Secteur de l'activité
data.append("activityData[entrepreneurships][0][town]", "/api/towns/1");

// Année de la première payement de taxe
data.append("activityData[entrepreneurships][0][yearFirstTaxPayment]", "2014");
// Mode payment (Annuel, Journalier, trimestriel, semestriel, mensuel)
data.append("activityData[entrepreneurships][0][taxePayMode]", "/api/taxe_pay_modes/8");
// Montant de taxe payé
data.append("activityData[entrepreneurships][0][taxeAmount]", "3000");
// Liste des taxe payé
data.append("activityData[entrepreneurships][0][taxes][0]", "taxe avenue");
data.append("activityData[entrepreneurships][0][taxes][1]", "taxe commune");


const xhr = new XMLHttpRequest();
xhr.withCredentials = true;

xhr.addEventListener("readystatechange", function () {
  if (this.readyState === this.DONE) {
    console.log(this.responseText);
  }
});

xhr.open("POST", "http://localhost:8000/api/productors");
xhr.setRequestHeader("cookie", "sf_redirect=%257B%2522token%2522%253A%252206d52e%2522%252C%2522route%2522%253A%2522_api_%255C%252Fohada%255C%252Fpost%255C%252Fbulk%255C%252F_post%2522%252C%2522method%2522%253A%2522POST%2522%252C%2522controller%2522%253A%2522api_platform.action.placeholder%2522%252C%2522status_code%2522%253A201%252C%2522status_text%2522%253A%2522Created%2522%257D; PHPSESSID=1b0n8aql15k7s0i5qssl5tvuks");
xhr.setRequestHeader("User-Agent", "insomnia/8.4.5");
xhr.setRequestHeader("Authorization", "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5YWM5ZGJhNy05NTM1LTQzMjYtOWZmYy04OTA5YzFhMDUwMzMiLCJqdGkiOiIyMTQxMjYwN2YwZTRhYTk1NTMyYTY3ZGQ0ZmI3ZjU1ZGY2YTBjNmJiMzdhZDdiNWVlZGEzYWY5YjA2MmM1ZTMyOWMwYzJiOTVkZmNhNzA2YyIsImlhdCI6MTcwMjE5ODk0My4wNDgzOTYsIm5iZiI6MTcwMjE5ODk0My4wNDgzOTksImV4cCI6MTczMzgyMTM0My4wMzk4NTMsInN1YiI6IjEiLCJzY29wZXMiOltdLCJwaG9uZU51bWJlciI6IjAwMDAwMDAwMTAiLCJyb2xlcyI6WyJST0xFX1ZPVUNIRVJfU1VQUExJRVJfTUFOQUdFUiIsIlJPTEVfVVNFUiJdfQ.A2ua5R-52bcqADKPQNRDcsaTFS8-azSfTBzPsDOaklrqUnKWQ_eRDW37sfePpEW2d6GTStOYQ2G3xxXNfxuW5RjS4z2OK48JJ3NaViVxC2K5HenFuwBHLtvlEW4kfLQa5emovyKpUEyRh1zWoUKOgoXbtyZ7PDYXKUjlKiSxrXuZj-XUYS5COfWApll8I-fGRgrSKN4ePt4_mdBw3KxgqMPfolnZJ0A-GlCE9AsG1qD1tOXIagH-1lb2roCScXfsMlucqCoUbghMvFOXwvShFVV4Bpyz0jnBNn7l0jDV1M3hs9HkVSWBY6bznXH4gTRVaQ6gw1NAatyG8gDUR8CAR_O4SjEIqNnNjwN-Dsm5ZCIYPzfxEo3iwpeM9KC6fdCJewqV_Oq_ijlC_5Xy3rfDF89hZXx7hWVAkZKqvBN6LROkbgFx-hZimzUslc3er5qJqtg31Fd784KqndtrMiccY4ltD-wlYe3n4pWOeR6X6mmt5hydKsWnRDmi4AoObPr5XKTbGLNf6jzPcJikPVUSRks0AMF2QUcPmZnV7hDHhf5N2gHUK5-qyqJRp2or7kwc7c5QSMsHaPa-QgHg_qRNxvAAw0UZk5wrhvi2sq3D4RddenKM3FclGsxFIqzOiSQdqHDs2P3zbzSZMYPkgixE0ZDuOCw9VnT6xw-hNE4b8c0");

xhr.send(data);

```