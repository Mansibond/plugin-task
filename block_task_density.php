<?php
/**
 * Clase principal del bloque Task Density (Versión Avanzada con Tercios)
 * @package    block_task_density
 */

class block_task_density extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_task_density');
    }

    public function get_content() {
        global $DB, $USER, $COURSE, $PAGE;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $html = "";
        $now = time();

        //Configuramos el tiempo por defecto a semana.
        $default_date = date('Y-m-d', $now + (7 * 24 * 60 * 60));
        
        $selected_date = optional_param('task_end_date', $default_date, PARAM_TEXT);
        
        $end_time = strtotime($selected_date . ' 23:59:59');

        //Esto es para mantener la URL limpia y evitar que se acumulen parámetros cada vez que se actualiza el filtro.
        $current_url = $PAGE->url->out_omit_querystring();
        
        $html .= '<form action="' . $current_url . '" method="GET" style="margin-bottom: 15px;">';
        $html .= '<input type="hidden" name="id" value="' . $COURSE->id . '">';
        $html .= '<strong>' . get_string('fecha_filtro', 'block_task_density') . '</strong><br>';
        $html .= '<input type="date" name="task_end_date" value="' . $selected_date . '" style="width: 100%; margin-bottom: 5px;" required>';
        $html .= '<button type="submit" class="btn btn-secondary btn-sm" style="width: 100%;">' . get_string('btn_filtro', 'block_task_density') . '</button>';
        $html .= '</form>';
        $html .= '<hr>';

        //Busqueda de datos
        $sql = "SELECT id, name, duedate FROM {assign} 
                WHERE course = ? AND duedate > ? AND duedate <= ?";
        $tasks = $DB->get_records_sql($sql, array($COURSE->id, $now, $end_time));

        $count = count($tasks);

        if ($count > 0) {
            $html .= html_writer::tag('p', get_string('tareas_encontradas', 'block_task_density', $count));
            
            
            $total_duration = $end_time - $now;
            $third_1 = $now + ($total_duration / 3);
            $third_2 = $now + (2 * $total_duration / 3);
            
            //Vigilante de cuello de botella
            $next_2_days = $now + (3 * 24 * 60 * 60);
            $tasks_this_week = 0;

            $base_hours_total = 0;
            $pressure_score = 0;

            // Tarea una por una
            foreach ($tasks as $task) {
                $base_hours_total += 1.5; 

                if ($task->duedate <= $next_2_days) {
                    $tasks_this_week++;
                }


                if ($task->duedate <= $third_1) {
                    // Muy urgente -> Multiplicador x3
                    $pressure_score += (1.5 * 3);
                } elseif ($task->duedate <= $third_2) {
                    // Medio -> Multiplicador x2
                    $pressure_score += (1.5 * 2);
                } else {
                    // Lejos -> Multiplicador x1
                    $pressure_score += (1.5 * 1);
                }
            }

            $html .= html_writer::tag('strong', get_string('horas_estimadas', 'block_task_density', $base_hours_total));
            
            // Consejos basados en la presión calculada
            $html .= html_writer::start_tag('div', array('style' => 'margin-top:10px; padding:5px; border-left:3px solid #052e16; background:#f0f0f0;'));
            $html .= html_writer::tag('span', '<strong>' . get_string('consejo', 'block_task_density') . '</strong><br>');

            //Este if es para visivilizar si hay cuello de botella inminente.
            if ($tasks_this_week > 3) {
                $html .= '<span style="color:#d32f2f; font-weight:bold;">' . get_string('alerta_cuelloBotella', 'block_task_density', $tasks_this_week) . '</span><br>';
                
            }
            
            // Si la puntuación de presión supera el doble de las horas base, es urgente
            if ($pressure_score >= ($base_hours_total * 2.5)) {
                $html .= get_string('alta_presion', 'block_task_density');
            } elseif ($pressure_score >= ($base_hours_total * 1.5)) {
                $html .= get_string('media_presion', 'block_task_density');
            } else {
                $html .= get_string('baja_presion', 'block_task_density');
            }
            $html .= html_writer::end_tag('div');
            
        } else {
            $html .= html_writer::tag('p', get_string('sin_tareas', 'block_task_density'));
        }

        $this->content->text = $html;
        return $this->content;
    }
}