# Moodle Block: Task Density (Calculadora de Densidad de Tareas)

## Descripción
El bloque **Task Density** (Calculadora de Densidad de Tareas) es un plugin para Moodle que asiste a los usuarios en la gestión de su carga de trabajo académico. Este bloque analiza las tareas (`assign`) pendientes dentro de un curso específico y calcula un nivel de presión temporal en función de las fechas de entrega.

## Características Principales
* **Filtro de Fechas:** Incluye un formulario para filtrar las tareas hasta una fecha específica, configurado por defecto a una semana (7 días) desde el momento actual.
* **Estimación de Horas:** Calcula un tiempo estimado de trabajo base, asignando 1.5 horas por cada tarea encontrada en el periodo filtrado.
* **Sistema de Presión por Tercios:** El algoritmo divide el tiempo total hasta la fecha límite en tres partes (tercios) para aplicar multiplicadores de urgencia a las tareas: urgentes (x3), medio plazo (x2) y lejanas (x1).
* **Detección de Cuellos de Botella:** Monitorea los próximos 3 días y muestra una alerta roja destacada si se acumulan más de 3 tareas en ese corto periodo.
* **Consejos Dinámicos:** Muestra mensajes de baja, media o alta presión comparando la puntuación de estrés obtenida con las horas base totales.

## Requisitos del Sistema
* **Moodle:** Requiere Moodle versión 2022111800 o superior.

## Instalación
1. Descarga el código de este repositorio.
2. Extrae o copia la carpeta del plugin en el directorio `/blocks/task_density` de tu instalación de Moodle.
3. Inicia sesión en Moodle como administrador.
4. Moodle detectará el nuevo plugin. Sigue las instrucciones en pantalla para completar la instalación y actualizar la base de datos.
5. Una vez instalado, podrás añadir el bloque "Task Density" en cualquier curso.

## Información del Plugin
* **Componente:** `block_task_density`
* **Versión de Lanzamiento:** v1.0 (Estable - 2026041300)
* **Autor:** Adrián Mansilla
* **Licencia:** GNU GPL v3 o posterior