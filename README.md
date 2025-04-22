# Backend – Conversor de Unidades y Cálculo de Incertidumbre (Laravel)

Este proyecto es una **API RESTful desarrollada con Laravel** que sirve como backend para una aplicación web creada en Angular. Fue uno de mis primeros proyectos fullstack, donde trabajé tanto el frontend como el backend de forma conectada.

Aunque se trata de un proyecto desarrollado hace un tiempo, refleja mi iniciativa de integrar tecnologías modernas y construir soluciones completas de forma autónoma.

> Hoy en día estoy enfocado en backend con **Java + Spring Boot**, pero mantengo este proyecto como ejemplo de mi evolución y de mi experiencia trabajando en proyectos reales con frontend y backend conectados por API.

---

## ¿Qué hace esta API?

Esta API permite:

- **Convertir unidades** físicas de distintas magnitudes (masa, temperatura, presión...).
- **Calcular la incertidumbre compuesta** basada en patrones de medida.
- Servir como puente entre el frontend Angular y la lógica de negocio científica.

---

## Tecnologías utilizadas

- PHP 7.4+
- Laravel 8
- MySQL
- Eloquent ORM
- API REST

---

## Principales endpoints

| Método | Endpoint                       | Descripción                                      |
|--------|--------------------------------|--------------------------------------------------|
| GET    | `/api/units/{magnitud}`        | Devuelve las unidades asociadas a una magnitud. |
| POST   | `/api/convert`                 | Realiza la conversión entre dos unidades.       |
| POST   | `/api/patterns/recommend`      | Devuelve patrones recomendados según el valor.  |
| POST   | `/api/patterns/uncertainty`    | Calcula la incertidumbre para un patrón dado.   |

---

## Cómo ejecutar el proyecto

1. Clona el repositorio:

`git clone https://github.com/oscar-co/conversorBack.git`
`cd conversorBack`


2. Instala las dependencias:

`composer install`


3. Copia el archivo .env:
`cp .env.example .env`


4. Configura la base de datos y genera la clave:
``php artisan key:generate``
``php artisan migrate``

5. Lanza el servidor local:
``php artisan serve``
