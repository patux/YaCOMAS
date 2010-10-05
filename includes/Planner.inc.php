<?php
/**
 * Class that helps in the construcction of the program
 * @author Argel Arias <levhita@gmail.com>
 */
class Planner {
  /**
   * Link to the open DB Connection
   * @var resource
   */
  private $_db_link = null;
  
  /**
   * Holds the distinct dates which have activities during the event
   * @var array
   */
  private $_dates = array();
  
  /**
   * Holds all the special events(all rooms) indexed under dates_ids
   * @var array
   */
  private $_special_events = array();
  
  /**
   * Holds all the events indexed under dates_ids
   * @var array
   */
  private $_events = array();
  
  /**
   * Holds all the rooms indexed under dates_ids
   * @var array
   */
  private $_rooms = array();
  /**
   * Holds all the types used in the system
   * @var array
   */
  private $_type_names = array();
  
  /**
   * Holds the calendar for everydate in the form of fixed starting times.
   * 
   * Note: All events must start at this times, if not they'll not appear in the
   * calendar.
   * @var array
   */
  private $_slots = array();
  
  public function __construct($db_link){
    $this->_db_link = $db_link;
  }
  
  public function load(){
    
    /** Gets all conference dates and store them in a convenient array **/
    $sql = "SELECT id, fecha, descr
            FROM fecha_evento
            ORDER BY fecha;";
    $results = mysql_query($sql, $this->_db_link);
    $dates = array();
    while ( $row = mysql_fetch_assoc($results)) {
      $dates[] = $row;
    }
    mysql_free_result($results);
        
    $this->_dates = array();
    foreach($dates as $date) {
      $this->_dates[$date['id']] = array(
        'date'=>$date['fecha'],
        'description'=>$date['descr'],
      );
    }
        
    $this->_events = array();
    $this->_special_events = array();
    
    foreach($this->_dates AS $date_id => $date ) {
      /** Gets specific data from all events and store them in a convenient array 
       * 
       * Place name, Place Id
       * Event name, Event Id (id_propuesta)
       * Person Name, Person Id
       * Start Time
       * End Time (calculated)
       * evento.id_evento
       */
      $sql = "SELECT  EO.id_lugar AS place_id, L.nombre_lug AS place_name ,
                E.id_propuesta AS event_id, P.nombre AS event_name,
                P.id_ponente AS person_id, concat(PO.nombrep, ' ', PO.apellidos) AS person_name, 
                EO.hora AS start, EO.hora + P.duracion AS end, P.duracion AS duration,
                P.id_prop_tipo AS type, PT.descr AS type_name
              FROM  evento AS E, 
                propuesta AS P, 
                evento_ocupa AS EO, 
                ponente AS PO, 
                lugar AS L,
                prop_tipo AS PT,
                estado AS ES
              WHERE   E.id_propuesta=P.id 
                AND E.id=EO.id_evento 
                AND P.id_ponente=PO.id 
                AND EO.id_lugar=L.id 
                AND P.id_prop_tipo=PT.id
                AND PO.id_estado=ES.id 
                AND EO.id_fecha='$date_id'
                AND (P.id_prop_tipo <> '101' AND P.id_prop_tipo <> '100')
              GROUP BY EO.id_evento 
              ORDER BY EO.id_fecha,EO.hora";
      $results = mysql_query($sql, $this->_db_link);
      $events = array();
      while( $row = mysql_fetch_assoc($results) ) {
        $events[] = $row;
      }
      mysql_free_result($results);
      $this->_events[$date_id] = $events;
       
      /** Gets specific data from all events and store them in a convenient array 
       * 
       * Place name, Place Id
       * Event name, Event Id (id_propuesta)
       * Person Name, Person Id
       * Start Time
       * End Time (calculated)
       * evento.id_evento
       */
      $sql = "SELECT  EO.id_lugar AS place_id, L.nombre_lug AS place_name ,
                E.id_propuesta AS event_id, P.nombre AS event_name,
                P.id_ponente AS person_id, concat(PO.nombrep, ' ', PO.apellidos) AS person_name, 
                EO.hora AS start, EO.hora + P.duracion AS end, P.duracion AS duration,
                P.id_prop_tipo AS type, PT.descr AS type_name
              FROM  evento AS E, 
                propuesta AS P, 
                evento_ocupa AS EO, 
                ponente AS PO, 
                lugar AS L,
                prop_tipo AS PT,
                estado AS ES
              WHERE   E.id_propuesta=P.id 
                AND E.id=EO.id_evento 
                AND P.id_ponente=PO.id 
                AND EO.id_lugar=L.id 
                AND P.id_prop_tipo=PT.id
                AND PO.id_estado=ES.id
                AND EO.id_fecha='$date_id'
                AND (P.id_prop_tipo = '101' OR P.id_prop_tipo = '100')
              GROUP BY EO.id_evento 
              ORDER BY EO.id_fecha,EO.hora";
      $results = mysql_query($sql, $this->_db_link);
      $special_events = array();
      while( $row = mysql_fetch_assoc($results) ) {
        $special_events[] = $row;
      }
      mysql_free_result($results);
      $this->_special_events[$date_id] = $special_events;
      
      /** Gets all rooms by each date **/
      $sql = "SELECT DISTINCT(L.nombre_lug) AS place_name
              FROM  evento_ocupa AS EO, 
                lugar AS L,
                evento AS E,
                propuesta AS P
              WHERE EO.id_lugar = L.id
                AND EO.id_evento = E.id
                AND E.id_propuesta = P.id
                AND EO.id_fecha = '$date_id'
                AND (P.id_prop_tipo <> '101' AND P.id_prop_tipo <> '100')
              ORDER BY L.nombre_lug";
      $results = mysql_query($sql, $this->_db_link);
      $rooms = array();
      while( $row = mysql_fetch_assoc($results) ) {
        $rooms[] = $row;
      }
      mysql_free_result($results);
      $this->_rooms[$date_id] = $rooms;
    }
    /** Gets all rooms by each date **/
    $sql = "SELECT descr FROM prop_tipo";
    $results = mysql_query($sql, $this->_db_link);
    $this->_type_names = array();
    while( $row = mysql_fetch_array($results) ) {
      $this->_type_names[] = $row[0];
    }
    mysql_free_result($results);
  }
  
  public function getDates(){
    return $this->_dates;
  }
  
  public function getStartHour($date_id) {
    $sql = "SELECT min(hora) FROM evento_ocupa WHERE id_fecha='$date_id'";
    $result = mysql_query($sql, $this->_db_link);
    $values = mysql_fetch_row($result);
    return $values[0];
  }
  
  public function getEndHour($date_id) {
    $sql = "SELECT max(hora) FROM evento_ocupa WHERE id_fecha='$date_id'";
    $result = mysql_query($sql, $this->_db_link);
    $values = mysql_fetch_row($result);
    return $values[0] + 1;
  }
  
  public function startsSpecial($date_id, $hour) {
    foreach($this->_special_events[$date_id] AS $event ){
      if ( $event['start'] == $hour ) {
        return $event;
      }
    }
    return false;
  }
  
  public function startsRegularInRoom($place_name, $date_id, $hour) {
    foreach($this->_events[$date_id] AS $event ){
      if ( ($event['place_name'] == $place_name) && ($event['start'] == $hour) ) {
        return $event;
      }
    }
    return false;
  }
  
  public function emptynessStarts($place_name, $date_id, $hour){
    /** First we check that nothing starts at this hour **/
    if(!$this->startsRegularInRoom($place_name, $date_id, $hour) && !$this->startsSpecial($date_id, $hour)) {
      /** Then we check if is at the start **/
      if ($hour == $this->getStartHour($date_id)){
        return true;
      }
      /** Or  that an event or special event just endeded **/
      foreach($this->_events[$date_id] AS $event ){
        if ( ($event['place_name'] == $place_name) && ($event['end'] == $hour) ) {
          return true;
        }
      }
      foreach($this->_special_events[$date_id] AS $event ){
        if ( $event['end'] == $hour ) {
          return true;
        }
      }
    }
    return false;
  }
  public function getEmptiesFrom($place_name, $date_id, $hour) {
    for($i = $hour;$i<$this->getEndHour($date_id);$i++) {
      if($this->startsRegularInRoom($place_name, $date_id, $i)
      || $this->startsSpecial($date_id, $i) ) {
        break;
      }  
    }
    return ($i - $hour);
  }
  
  public function createCalendar($date_id){
    $events = $this->_events[$date_id];
    $hour = $this->getStartHour($date_id);
    $string = "<table class=\"planner\">\n<tr><th>Hora</th>";
    $columns = count($this->_rooms[$date_id]);
    foreach($this->_rooms[$date_id] AS $room) {
      $string .="<th>{$room['place_name']}</th>";
    }
    $string .= "</tr>\n";
    while( $hour < $this->getEndHour($date_id) ){
      $string .="<tr><td class=\"time\">$hour:00</td>";  
      if( $event = $this->startsSpecial($date_id, $hour) ) {
        $string .= $this->createCell($event, $event['duration'], $columns);
      } else {
        foreach($this->_rooms[$date_id] AS $room) {
          if( $event = $this->startsRegularInRoom($room['place_name'], $date_id, $hour) ) {
            $string .=  $this->createCell($event, $event['duration']);
          } elseif( $this->emptynessStarts($room['place_name'], $date_id, $hour) ) {
            $empties = $this->getEmptiesFrom($room['place_name'], $date_id, $hour);
            $string .= $this->createCell('', $empties, '', true);
          }
        }
      }
      $string .= "</tr>\n";
      $hour++;
    }
    $string .= "</table>\n";
    return $string;
  }
  
  
  
  public function createCell($event, $rows = 1, $columns = 1, $empty = false) {
    $type_class = ($empty)?'empty':strtolower(str_replace(' ', '_', $event['type_name']));
    $string = "<td class=\"type_$type_class\"";
    if ( $columns > 1) {
      $string .= " colspan=\"$columns\"";
    }
    if ($rows > 1) {
      $string .= " rowspan=\"$rows\"";
    }
    $string .= ">";
    if (!$empty) {
      $string .= '<a href="Vponencia.php?vopc='.$event['person_id'] . ' '
      . $event['event_id'] . ' ' 
      . $_SERVER['REQUEST_URI']. '"';
      $string .="<span class=\"event\">".htmlentities($event['event_name']) ."</span><br>" 
                ."<span class=\"person\">".htmlentities($event['person_name'])."</span></a>";
    } else {
      $string .= "&nbsp;";
    }
    $string .= "</td>";
    return $string;
  }
  
  public function createLegend() {
    
    $string = "<table class=\"planner\"><tr>";
    foreach($this->_type_names as $type_name ){
      $type_class = strtolower(str_replace(' ', '_', $type_name));
      $string .= "<td class=\"type_$type_class\">".htmlentities($type_name)."</td>";
    }
    $string .= "</tr></table>";
    return $string;
  }
}