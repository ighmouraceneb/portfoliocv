<?php
class ErrorsManager
{
	// Déclarer les propriétés
	private $db;

	// Constructeur

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->class = str_replace("Manager", "", get_class($this));
        $this->table = DB_PREFIX.strtolower($this->class);
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getTable()
    {
        return $this->table;
    }

    private function getBy($field, $data) {
        $query = 'SELECT * FROM '.$this->getTable().' WHERE '.$field.'='.$data ;
        $res = $this->db->query($query);
        if($res){
            $item = $res->fetchObject($this->getClass());
            if($item){
                return $item;
            }
            else{
                throw new Exception('No Error with this '.$field);
            }
        }else{
            throw new Exception("Erreur interne");
        }
    }

    public function getById($id) {
        return $this->getBy("id", $id);
    }

	public function create($Error)
	{
		$fields = ['type','position','message'];
        $values = [];
        foreach($fields as $field){
            $getter = "get".ucfirst($field);
            $values[$field] = $this->db->quote( $Error->$getter() );
        }

        $values = implode(", ", $values);
        $fieldsStr = implode(", ", $fields);

        $query = "INSERT INTO ".$this->getTable()." (".$fieldsStr.") VALUES (".$values.")";
		// var_dump($query);
		$res = $this->db->exec($query);
        if($res){
            $id = $this->db->lastInsertId();
            if($id){
                return $this->getById($id);
            }
            else{
                throw new Exception('Last insert error');
            }
        }
        else{
            throw new Exception('DataBase error');
        }

	}

 	public function getAll($limit)
 	{
 		$limit = intval($limit);
 		$query = "SELECT * FROM ".$this->getTable()." ORDER BY create_date DESC LIMIT ".$limit ;
 		$res = $this->db->query($query);
        if($res) {
            while( $item = $res->fetchObject($this->getClass()) ) {
                $list[] = $item;
            }
            if(!empty($list)){
                return $list;
            }
            else{
                throw new Exception('No DB entry found');
            }
        }
        else{
            throw new Exception('DataBase error');
        }
	}

}
?>