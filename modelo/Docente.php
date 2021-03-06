<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Docente
 *
 * @author JoseCarlos
 */
class Docente extends Persona{
    
    public function __construct() {
        parent::__construct();
    }

    public function crearDocente(Docente $docente) {
        $sql = "INSERT INTO docente (idDocente, nombres, pApellido, sApellido, sexo, telefono, direccion, correo, fNacimiento) VALUES (:idDocente,?,?,?,?,?,?,?,?)";
        $this->__setSql($sql);
        $this->ejecutar($this->getParametros($docente));
    }

    public function leerDocentes() {
        $sql = "SELECT idDocente, nombres, pApellido, sApellido, sexo, telefono, direccion, correo, fNacimiento FROM docente";
        $this->__setSql($sql);
        $resultado = $this->consultar($sql);
        $docs = array();
        foreach ($resultado as $fila) {
            $docente = new Docente();
            $this->mapearDocente($docente, $fila);
            $docs[$docente->getIdDocente()] = $docente;
        }
        return $docs;
    }

    public function actualizarDocente(Docente $docente) {
        $sql = "UPDATE docente SET nombres=?, pApellido=?, sApellido=?, sexo=?, telefono=?, direccion=?, correo=?, fNacimiento=? WHERE idDocente=?";
        $this->__setSql($sql);
        $this->ejecutar($this->getParametros($docente));        
        }
    
    
    public function eliminarDocente(Docente $docente) {
        $sql = "DELETE docentes where idDocente=?";
        $this->__setSql($sql);
        $param = array(':idDocente' => $docente->getIdDocente());
        $this->ejecutar($param);        
    }
  
    public function crearConsulta($idSalon,$idMateria){
        $fecha = getdate();
        $anio=$fecha["year"];
        $sql = "SELECT p.idPersona as idPersona, p.nombres as nombres, p.pApellido as pApellido, p.sApellido as sApellido, n.primerP as primerP, n.segundoP as segundoP, n.tercerP as tercerP, n.cuartoP as cuartoP, n.definitiva as def FROM notas n , matricula m , persona p WHERE n.idPersona=m.idPersona AND m.idPersona=p.idPersona AND m.idSalon='".$idSalon."'  AND m.estado='1' AND m.año_lectivo='".$anio."' AND n.idMateria='".$idMateria."' ORDER BY p.pApellido";
        $this->__setSql($sql);
        $resultado = $this->consultar($sql);
        return $resultado;
    }
    
    public function crearConsulta2($idSalon,$idMateria){
        $fecha = getdate();
        $anio=$fecha["year"];
        $sql = "SELECT p.idPersona as idPersona, p.nombres as nombres, p.pApellido as pApellido, p.sApellido as sApellido, n.primerP as primerP, n.segundoP as segundoP, n.tercerP as tercerP, n.cuartoP as cuartoP FROM fallas n , matricula m , persona p WHERE n.idPersona=m.idPersona AND m.idPersona=p.idPersona AND m.idSalon='".$idSalon."' AND m.estado='1' AND m.año_lectivo='".$anio."' AND n.idMateria='".$idMateria."' ORDER BY p.pApellido";
        $this->__setSql($sql);
        $resultado = $this->consultar($sql);
        return $resultado;
    }
    
    public function crearConsultaPorIdPersona($idPersona, $idSalon, $idMateria){
        
        $sql = "SELECT p.idPersona as idPersona, p.nombres as nombres, p.pApellido as pApellido, p.sApellido as sApellido, n.primerP as primerP, n.segundoP as segundoP, n.tercerP as tercerP, n.cuartoP as cuartoP, n.definitiva as def FROM notas n , matricula m , persona p WHERE n.idPersona=m.idPersona AND m.idPersona=p.idPersona AND m.idSalon='".$idSalon."' AND n.idMateria='".$idMateria."' AND n.idPersona='".$idPersona."' ORDER BY p.pApellido"  ;
        $this->__setSql($sql);
        $resultado = $this->consultar($sql);
        return $resultado;
    }
    
    public function actualizarNota($idPersona,$idMateria,$primerP,$segundoP,$tercerP,$cuartoP){
        if ($primerP==""){
            $primerP=NULL;
        }
        if ($segundoP==""){
            $segundoP=NULL;
        }
        if ($tercerP==""){
            $tercerP=NULL;
        }
        if ($cuartoP==""){
            $cuartoP=NULL;
        }
        $def= $this->calcularDef($primerP,$segundoP,$tercerP,$cuartoP);
        $sql = "UPDATE notas SET primerP=:primerP, segundoP=:segundoP, tercerP=:tercerP, cuartoP=:cuartoP, definitiva=:def WHERE idPersona=:idPersona and idMateria=:idMateria";
        $this->__setSql($sql);
        $param = array(':idPersona' => $idPersona, ':idMateria'=> $idMateria, ':primerP'=>$primerP, ':segundoP'=>$segundoP, ':tercerP'=>$tercerP, ':cuartoP'=>$cuartoP, ':def'=>$def);
        $this->ejecutar($param);   
    }
    
    public function actualizarNotaPorPeriodo($idPersona,$idMateria,$periodo,$nota){
        if ($periodo=="PRIMERO"){
            $p="primerP";
        }
        if ($periodo=="SEGUNDO"){
            $p="segundoP";
        }
        if ($periodo=="TERCERO"){
            $p="tercerP";
        }
        if ($periodo=="CUARTO"){
            $p="cuartoP";
        }
        if ($nota==""){
            $nota=NULL;
        }
 
        $sql = "UPDATE notas SET ".$p."=:nota WHERE idPersona=:idPersona and idMateria=:idMateria";
        $this->__setSql($sql);
        $param = array(':idPersona' => $idPersona, ':idMateria'=> $idMateria, ':nota'=>$nota);
        $this->ejecutar($param);   
    }
    
     public function actualizarFallaPorPeriodo($idPersona,$idMateria,$periodo,$falla){
        if ($periodo=="PRIMERO"){
            $p="primerP";
        }
        if ($periodo=="SEGUNDO"){
            $p="segundoP";
        }
        if ($periodo=="TERCERO"){
            $p="tercerP";
        }
        if ($periodo=="CUARTO"){
            $p="cuartoP";
        }
        if ($falla==""){
            $falla=NULL;
        }
 
        $sql = "UPDATE fallas SET ".$p."=:falla WHERE idPersona=:idPersona and idMateria=:idMateria";
        $this->__setSql($sql);
         $param = array(':idPersona' => $idPersona, ':idMateria'=> $idMateria, ':falla'=>$falla);
        $this->ejecutar($param);   
    }
    
    public function actualizarFalla($idPersona,$idMateria,$primerP,$segundoP,$tercerP,$cuartoP){
        if ($primerP==""){
            $primerP=NULL;
        }
        if ($segundoP==""){
            $segundoP=NULL;
        }
        if ($tercerP==""){
            $tercerP=NULL;
        }
        if ($cuartoP==""){
            $cuartoP=NULL;
        }
        $def= $this->calcularDef($primerP,$segundoP,$tercerP,$cuartoP);
        $sql = "UPDATE fallas SET primerP=:primerP, segundoP=:segundoP, tercerP=:tercerP, cuartoP=:cuartoP WHERE idPersona=:idPersona and idMateria=:idMateria";
        $this->__setSql($sql);
        $param = array(':idPersona' => $idPersona, ':idMateria'=> $idMateria, ':primerP'=>$primerP, ':segundoP'=>$segundoP, ':tercerP'=>$tercerP, ':cuartoP'=>$cuartoP);
        $this->ejecutar($param);   
    }
    
    public function calcularDef($n1,$n2,$n3,$n4){
        if($n1 == NULL && $n2 == NULL && $n3 == NULL && $n4 == NULL ){
        $def =NULL;
        }elseif ($n2 == NULL && $n3 == NULL && $n4 == NULL  ){
            $def = $n1;
        }elseif($n1 == NULL && $n3 == NULL && $n4 == NULL ){
            $def= $n2;
        }elseif($n1 == NULL && $n2 == NULL && $n4 == NULL ){
            $def= $n3;
        }elseif($n1 == NULL && $n2 == NULL && $n3 == NULL ){
            $def= $n4;
        }elseif($n3 == NULL && $n4 == NULL ){
            $def= ($n1+$n2)/2;
        }elseif($n2 == NULL && $n4 == NULL ){
            $def= ($n1+$n3)/2;
        }elseif($n1 == NULL && $n4 == NULL ){
            $def= ($n2+$n3)/2;
        }elseif($n2 == NULL && $n3 == NULL ){
            $def= ($n1+$n4)/2;
        }elseif($n1 == NULL && $n3 == NULL ){
            $def= ($n2+$n4)/2;
        }elseif($n1 == NULL && $n2 == NULL ){
            $def= ($n4+$n3)/2;
        }elseif($n4 == NULL ){
            $def= ($n1+$n2+$n3)/3;
        }elseif($n3 == NULL ){
            $def= ($n1+$n2+$n4)/3;
        }elseif($n2 == NULL ){
            $def= ($n1+$n3+$n4)/3;
        }elseif($n1 == NULL ){
            $def= ($n2+$n3+$n4)/3;
        }else{
            $def= ($n1 + $n2 + $n3 + $n4)/4;
        }
        return $def;
    }
    
}
?>
