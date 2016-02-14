<?php
/*
 * mysqli_oauth_client.php
 *
 * @(#) $Id: mysqli_oauth_client.php,v 1.4 2013/07/02 05:19:31 mlemos Exp $
 *
 */

class pdo_oauth_client_class extends database_oauth_client_class{
    var $db;
    var $database = array(
        'host'=>'',
        'user'=>'',
        'password'=>'',
        'name'=>'',
        'port'=>0,
        'socket'=>''
    );

    Function Initialize(){
        if(!parent::Initialize()){
            return false;
        }
        $this->db = new PDO("mysql:dbname=".$this->database['name'].";host=".$this->database['host'].";port=".$this->database['port'], $this->database['user'], $this->database['password']);
        if(!$this->db) {
            $this->SetError($this->db->connect_error);
            $this->db = null;
            return false;
        }
        return true;
    }

    Function Finalize($success){
        if(IsSet($this->db)){
            $this->db = null;
        }
        return parent::Finalize($success);
    }

    Function Query($sql, $parameters, &$results, $result_types = null){
        if($this->debug){
            $this->OutputDebug('Query: '.$sql);
        }
        $results = array();
        $statement = $this->db->prepare($sql);
        if(!$statement){
            return $this->SetError($statement->errorInfo());
        }
        $prepared = array();
        $types = '';
        $tp = count($parameters);
        $v = $parameters;

        for($p = 0; $p < $tp;){
            switch($t = $v[$p++]){
                case 's':
                case 'i':
                case 'd':
                    break;
                case 'b':
                    $v[$p] = (IsSet($v[$p]) ? ($v[$p] ? 'Y' : 'N') : null);
                case 't':
                case 'dt':
                case 'ts':
                    $t = 's';
                    break;
            }
            $types .= $t;
            if($this->debug){
                $this->OutputDebug('Query parameter type: '.$t.' value: '.$v[$p]);
            }
            $prepared[] = $v[$p++];
        }

        foreach($prepared as $param => $value){
            $statement->bindValue($param + 1, $value, PDO::PARAM_STR);
        }
        
        if(!$statement->execute()){
            return $this->SetError(implode(".", $statement->errorInfo()));
        }
        $fields = $statement->columnCount();
        if($fields){
            $row = $bind = array();
            for($f = 0; $f < $fields; ++$f){
                $row[$f] = null;
                $bind[$f] = &$row[$f];
            }
            $rows = array();
            while($r = $statement->fetch(PDO::FETCH_ASSOC)){
                $row = $r;
                $rows[] = $row;
            }
            $results['rows'] = $rows;
        }elseif($error = $statement->errorInfo()){
            return $this->SetError(implode(".", $error));
        }else{
            $results['insert_id'] = $this->db->lastInsertId();
            //$results['affected_rows'] = $statement->affected_rows;
        }
        return true;
    }
};

?>