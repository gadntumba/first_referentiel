# Add FIle

## get all type documents

- path `http://producer.surintrants.com/api/document_types/`
- Method `GET`
- Headers :
  ```json
    {
        "Content-Type": "application/json",
        "Accept": "application/json",
    }
  ```
- response :
  ```json
    [
        {
            "id": 1,
            "wording": "RCCM",
            "iri": "\/api\/document_types\/1"
        },
        {
            "id": 2,
            "wording": "F92",
            "iri": "\/api\/document_types\/2"
        },
        {
            "id": 3,
            "wording": "ID NAT",
            "iri": "\/api\/document_types\/3"
        },
        {
            "id": 4,
            "wording": "Numéro Impôt",
            "iri": "\/api\/document_types\/4"
        },
        {
            "id": 5,
            "wording": "Status",
            "iri": "\/api\/document_types\/5"
        }
    ]

  ```

## add File

- Path `http://producer.surintrants.com/api/productors/{id}/documents`, with `id` productor id
- Method `GET` 
- Headers :
  ```json

    {
        "Content-Type": "multipart/form-data",
        "Accept": "application/json",
    }

  ```
- Data (Multipart data) :
```javascript

        const data = new FormData();
        data.append("file", "/home/nkusu/Images/Capture d’écran du 2023-04-10 11-51-10.png");
        data.append("documentType", "/api/document_types/2");

        const xhr = new XMLHttpRequest();
        xhr.withCredentials = true;

        xhr.addEventListener("readystatechange", function () {
        if (this.readyState === this.DONE) {
            console.log(this.responseText);
        }
        });

        xhr.open("POST", "http://producer.surintrants.com/api/productors/44/documents");
        xhr.setRequestHeader("User-Agent", "insomnia/8.5.1");
        xhr.setRequestHeader("Authorization", "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5YWM5ZGJhNy05NTM1LTQzMjYtOWZmYy04OTA5YzFhMDUwMzMiLCJqdGkiOiJmNzY0YzI2NGRlMDAwMmQ1ZjFmYTU2ZmMwNzY3N2Y4YzcxMWZlOTg3Y2RjYjc1YjMxNTRmNTAwY2UwYmM4YzA2ZTY2NzMzOTQ2MGE2ZDFjOCIsImlhdCI6MTcwNDg5OTg0OC4wMDE3NDUsIm5iZiI6MTcwNDg5OTg0OC4wMDE3NDgsImV4cCI6MTczNjUyMjI0Ny45OTg0OTgsInN1YiI6IjEiLCJzY29wZXMiOltdLCJwaG9uZU51bWJlciI6IjAwMDAwMDAwMTAiLCJyb2xlcyI6WyJST0xFX1ZPVUNIRVJfU1VQUExJRVJfTUFOQUdFUiIsIlJPTEVfVVNFUiJdfQ.nXSC8t0iadxHhul9FiqgmvfxetR8Q9Vp5_0xzxNQ3FzEgnjWV6gKrsTn7e92hRU3oAV-zFQvZ2VwCrNtJxlD8QxQgBICze_NvfwhjbhcckeVo91eAjNa7C2cjpywt3HsmRdS2qA9u04uK5CzCdWm1uzKb-2p6TPXOTRz7qfYgzncg4Dt_-VkzxzmEHIlQONzzNJYLHDh5UFzKReMmgRtYNwRjFvGi8hIAv-jCmU7TMYOTwgaKPEumzKjxLk1XolplyZKJad4YBdvYuSBG30PBy9bjMF9CpI0e3nl_6xZxyKQjwVGR3FP-I6PQQ-J3dLGjjcYA_Goku2iz0gPK09E5YBc8ViMDDJeeuXHy75BWrnAhRIdMDwn7GjniRazYmlRohXHk6XeHGc5Kxf-A-aKd8Lof3RwieLPHpsn8Yqa339Rwn85WfkUEm9dSM7IJqQvwV8FbVgeG_qDl8El5vQejf1fdnmGCRcD5cD_yW7iEC2ywiTgh8zEcckJpoKAU2gZTqVpIPxzW7yEwz_ebtfr0JYbVf-HBALFaxCuKWvBReac-kAvJDTeAn7mO5Fj5QYnZKNrfTLxV7Ie9QCM0NLqdvtx690RsHQtNB_UDHsLgLs1U9p6eesBKzgYHRqH0rDtreFrKJTI91NB4YO2bbyvHAKVHudDDqWOcg0QfovSuDQ");

        xhr.send(data);
        
```