<h1 align="center"> Reto Técnico BackBone Systems </h1>

<p align="left">
   <img src="https://img.shields.io/badge/Status-Stable-brightgreen">
   </p>

## Sobre esta API

Esta API, es para el reto técnico para el cargo Backend Developer en Backbone systems.
La API replica la funcionalidad de la siguiente API:
 
- https://jobs.backbonesystems.io/api/zip-codes/{zip_code}

Esta API tiene como su base de datos exclusivamente archivos txt donde obtiene los datos,
para que el acceso sea lo mas rapido posible he almacenado en un archivo adicional el codigo postal seguido con la posicion en bytes del archivo donde se encuentran todos los codigos postales.
Para generar ese archivo que indexa las ubicaciones se ejecuta ` php artisan zip-codes:readcsv`

## Funcionalidad del proyecto

- Recibir un código postal (Mexico) y en base a este entregar los datos correspondientes del mismo
- Importar los datos del Excel a la base de datos mediante un endpoint.
- Importar los datos del Excel a la base de datos mediante un comando artisan.

## Endpoints y comando

Los endpoints disponibles son:

-/api/zip-codes/{zip_code} (endpoint principal)
-/api/import (carga el archivo excel)

El comando para cargar el archivo excel es el siguiente

- php artisan import:codes

## Ejemplo de respuesta

<pre>
{
    "zip_code": "20020",
    "locality": "AGUASCALIENTES",
    "federal_entity": {
            "key": 1,
            "name": "AGUASCALIENTES",
            "code": null
    },
    "settlements": [
            {
            "key": 16,
            "name": "BUENOS AIRES",
            "zone_type": "URBANO",
            "settlement_type": {
                "name": "Colonia"
                }
            },
            {
            "key": 18,
            "name": "CIRCUNVALACION NORTE",
            "zone_type": "URBANO",
            "settlement_type": {
                "name": "Fraccionamiento"
                }
            },
            {
            "key": 19,
            "name": "LAS ARBOLEDAS",
            "zone_type": "URBANO",
            "settlement_type": {
                "name": "Fraccionamiento"
                }
            },
            {
            "key": 20,
            "name": "VILLAS DE SAN FRANCISCO",
            "zone_type": "URBANO",
            "settlement_type": {
                "name": "Fraccionamiento"
                }
            }
    ],
    "municipality": {
    "key": 1,
    "name": "AGUASCALIENTES"
    }
}
</pre>
