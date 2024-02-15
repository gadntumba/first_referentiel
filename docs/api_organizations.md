# Doc
- Requette
```Http
GET /api/organizations HTTP/1.1
User-Agent: insomnia/8.5.1
Content-Type: application/json
Accept: application/json
Host: localhost:8000

```

- Reponse

```json

[
	{
		"name": "Equuinox",
		"city": {
			"id": 1,
			"name": "Buta",
			"iri": "\/api\/cities\/1"
		},
		"iri": "\/api\/organizations\/1"
	},
	{
		"name": "Equuinox",
		"city": {
			"id": 1,
			"name": "Buta",
			"iri": "\/api\/cities\/1"
		},
		"iri": "\/api\/organizations\/2"
	},
	{
		"name": "Equuinox",
		"city": {
			"id": 1,
			"name": "Buta",
			"iri": "\/api\/cities\/1"
		},
		"iri": "\/api\/organizations\/3"
	}
]

```