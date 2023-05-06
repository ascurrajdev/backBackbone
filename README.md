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
Para generar ese archivo que indexa las ubicaciones se ejecuta ` php artisan zip-codes:readcsv `, una vez generado el archivo se puede utilizar la aplicacion.

Para que la obtencion del codigo postal tiene implementado un algoritmo con busqueda binaria, seguido de dos busquedas secuenciales tanto arriba como abajo partiendo de la posicion encontrada en la busqueda binaria, una vez obtenido esas posiciones se almacenan en la cache, para que en la proxima peticion solo obtenga las posiciones y mapea los datos

Tambien el endpoint utiliza balanceo de carga con dos servidores en las siguientes ubicaciones, eso es pensando para este caso ya que la empresa es internacional, por lo que hay usuarios en toda america: 
- Santiago de Chile
- Dallas (Texas)

## Funcionalidad del proyecto

- Recibir un código postal (Mexico) y en base a este entregar los datos correspondientes del mismo

## Endpoints y comando

Los endpoints disponibles son:

-/api/zip-codes/{zip_code} (endpoint principal)

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
