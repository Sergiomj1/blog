<?php

    namespace App;

    class DB extends \PDO{
        static $instance;
        protected  $config;

        static function singleton(){
            if(!(self::$instance instanceof self)){
                self::$instance=new self();
            }
            return self::$instance;
        }

        public function __construct(){
            parent::__construct(DSN,USR,PWD);
        }


        
       
        // Db functions
        function insert($table,$data):bool 
        {

           if (is_array($data)){
              $columns='';$bindv='';$values=null;
                foreach ($data as $column => $value) {
                    $columns.='`'.$column.'`,';
                    $bindv.='?,';
                    $values[]=$value;
                }
                $valores='';
               foreach ($values as $valor){

                   $valores.="'".$valor."',";
               }
               $valores=substr($valores,0,-1);


                $columns=substr($columns,0,-1);
                $bindv=substr($bindv,0,-1);
                

               
                $sql="INSERT INTO {$table}({$columns}) VALUES ({$valores})";



                    try{
                        $stmt=self::$instance->prepare($sql);
    
                        $stmt->execute($values);
                    }catch(\PDOException $e){
                        echo $e->getMessage();
                        return false;
                    }
                
                return true;
                }
                return false;
            }
    
            function selectAll($table,array $fields=null):array
            {
                if (is_array($fields)){
                    $columns=implode(',',$fields);
                    
                }else{
                    $columns="*";
                }
                
                $sql="SELECT {$columns} FROM {$table}";
               
                $stmt=self::$instance->prepare($sql);
                $stmt->execute();
                $rows=$stmt->fetchAll(\PDO::FETCH_ASSOC);
                return $rows;
            }
    
            function selectAllWithJoin($table1,$table2,array $fields=null,string $join1,string $join2):array
            {
                if (is_array($fields)){
                    $columns=implode(',',$fields);
                    
                }else{
                    $columns="*";
                }
               
                $inners="{$table1}.{$join1} = {$table2}.{$join2}";
                
                $sql="SELECT {$columns} FROM {$table1} INNER JOIN {$table2} ON {$inners}";
                
                $stmt=self::$instance->prepare($sql);
                $stmt->execute();
                $rows=$stmt->fetchAll(\PDO::FETCH_ASSOC);
                return $rows;
            }
            // només una condició
            function selectWhereWithJoin($table1,$table2,array $fields=null,string $join1,string $join2,array $conditions):array
            {
                if (is_array($fields)){
                    $columns=implode(',',$fields);
                    
                }else{
                    $columns="*";
                }
               
                $inners="{$table1}.{$join1} = {$table2}.{$join2}";
                $cond="{$conditions[0]}='{$conditions[1]}'";
                
            $sql="SELECT {$columns} FROM {$table1} INNER JOIN {$table2} ON {$inners} WHERE {$cond} ";
            
                
                $stmt=self::$instance->prepare($sql);
                $stmt->execute();
                $rows=$stmt->fetchAll(\PDO::FETCH_ASSOC);
                return $rows;   
            }
    
            function update(string $table, array $data,array $conditions)
            {
                if ($data){
                    $keys=array_keys($data);
                    $values=array_values($data);
                    $changes="";
                    for($i=0;$i<count($keys);$i++){
                        $changes.=$keys[$i]."='".$values[$i]."',";
                    }
                    $changes=substr($changes,0,-1);
                    $cond="{$conditions[0]}='{$conditions[1]}'";
                    $sql="UPDATE {$table} SET {$changes} WHERE {$cond}";
                    
                    $stmt=self::$instance->prepare($sql);
                    $res=$stmt->execute();
                    if($res){
                        return true;
                    }    
                }else{
                    return false;
                }
                
    
            }



    
            function remove($tbl,$id){
            
                $sql="DELETE FROM {$tbl} WHERE id=$id";
                $stmt=self::$instance->prepare($sql);
                $res=$stmt->execute();
                if($res){
                    return true;
                }
                else{
                    return false;
                }    
            }

        public function existsCategory($category) {
            $sql = 'SELECT id FROM category WHERE name = :name';
            $stmt = $this->prepare($sql);
            $stmt->bindValue(':name', $category);
            $status = $stmt->execute();

            if ($status) {
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                if (!empty($result)) {
                    $category = reset($result);
                    return $category['id'];
                }
            }

            return false;
        }

        public function addCategory($category) {
            $categoryId = $this->existsCategory($category);

            if ($categoryId) {
                return $categoryId;
            }

            $sql = 'INSERT INTO category (name) VALUES (:name)';
            $stmt = $this->prepare($sql);
            $stmt->bindValue(':name', $category);
            $status = $stmt->execute();

            if ($status) {
                return $this->lastInsertId();
            }

            return false;
        }

        private function prepareInsertSql($table, $values) {
            $colunm_names = array_keys($values);
            $colunm_bind_names = array_map(function($value) {
                return ':' . $value;
            }, $colunm_names);

            $sql = 'INSERT INTO ' . $table . ' (' . implode(',', $colunm_names) . ')
                    VALUES (' . implode(',', $colunm_bind_names) . ')';

            $stmt = $this->prepare($sql);

            foreach ($values as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }

            return $stmt;
        }

        private function conditionsDefaults(&$conditions) {
            $defaults = [
                'relation' => 'OR'
            ];

            foreach ($defaults as $key => $value) {
                if (!isset($conditions[$key])) {
                    $conditions[$key] = $value;
                }
            }
        }

        /**
         * Devuelve la sentencia WHERE con las condiciones indicadas en el array
         *
         * @param array $conditions
         * @return string
         */
        private function buildWhereConditionsSql($conditions) {
            if (!empty($conditions)) {
                $this->conditionsDefaults($conditions);
                $conditions_sql = [];

                foreach ($conditions as $condition) {
                    if (is_array($condition)) {
                        $conditions_sql[] = $condition['key'] . ' ' . $condition['compare'] . ' ' . ':condition_' . $condition['key'];
                    }
                }

                return ' WHERE ' . implode(' ' . $conditions['relation'] . ' ', $conditions_sql);
            }

            return '';
        }

        /**
         * Prepara los valores en las condiciones de la sentencia WHERE en una consulta preparada
         *
         * @param array $conditions
         * @param \PDOStatement $stmt
         *
         * @return void
         */
        private function buildWhereValues($conditions, $stmt) {
            if (!empty($conditions)) {
                foreach ($conditions as $condition) {
                    if (is_array($condition)) {
                        $stmt->bindValue(':condition_' . $condition['key'], $condition['value']);
                    }
                }
            }
        }

        /**
         * Crea el codigo sql para actualizar una tabla
         *
         * @param string $table
         *      El nombre de la tabla
         * @param array $values
         *      Array asociativo con el nombre de la columna y el valor
         *      Ejemplo: ['columna' => 'valor']
         * @param array $conditions
         *      Array con las condiciones para actualizar solo unas filas
         *      Ejemplo: [
         *          'relation' => 'OR',
         *          [
         *              'key' => 'nombre columna',
         *              'compare' => '=',
         *              'value' => 'valor'
         *          ]
         *      ]
         *
         * @return bool|\PDOStatement
         */
        private function prepareUpdateSql($table, $values, $conditions) {
            $colunm_names = array_keys($values);
            $updates = array_map(function($value) {
                return $value . ' = ' . ':' . $value;
            }, $colunm_names);

            $sql = 'UPDATE ' . $table . ' SET ' . implode(',', $updates);
            $sql .= $this->buildWhereConditionsSql($conditions);

            $stmt = $this->prepare($sql);

            foreach ($values as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }

            $this->buildWhereValues($conditions, $stmt);
         // que el stmt se edita dentro de la funcion
            return $stmt;
        }


        public function addcomment($values) {
            $stmt = $this->prepareInsertSql('comment', $values);
            $status = $stmt->execute();

            if ($status) {
                return $this->lastInsertId();
            }

            return false;
        }


        public function addPost($values) {
            $stmt = $this->prepareInsertSql('post', $values);
            $status = $stmt->execute();

            if ($status) {
                return $this->lastInsertId();
            }

            return false;
        }





        public function editPost($values, $id) {
            $stmt = $this->prepareUpdateSql('post', $values, [
                [
                    'key' => 'id',
                    'compare' => '=',
                    'value' =>  $id
                ]
            ]);

            $status = $stmt->execute();

            return $status;
        }

        public function getAllByEnityName($table) {
            $sql = 'SELECT * FROM ' . $table;
            $stmt = $this->prepare($sql);
            $status = $stmt->execute();
            $result = [];

            if ($status) {
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }

            return $result;
        }

        public function getEntityByid($table, $id) {
            $sql = 'SELECT * FROM ' . $table . ' WHERE id = :id';
            $stmt = $this->prepare($sql);
            $stmt->bindValue(':id', $id);
            $status = $stmt->execute();

            if ($status) {
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                if (!empty($result)) {
                    $entity = reset($result);
                    return $entity;
                }
            }

            return false;
        }

        public function getEntitys($table, $conditions) {
            $sql = 'SELECT * FROM ' . $table;
            $sql .= $this->buildWhereConditionsSql($conditions);
            $result = [];

            $stmt = $this->prepare($sql);
            $this->buildWhereValues($conditions, $stmt);

            $status = $stmt->execute();

            if ($status) {
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }

            return $result;
        }

        public function getPosts() {
            $posts = $this->getAllByEnityName('post');

            foreach ($posts as $key => $post) {
                $category = $this->getCategory($post['category_id']);
                $posts[$key]['category'] = $category;
            }

            return $posts;
        }

        public function getCategory($id) {
            return $this->getEntityByid('category', $id);
        }


        public function getUser($id) {
            return $this->getEntityByid('user', $id);
        }




        public function getPost($id) {
            $post = $this->getEntityByid('post', $id);

            if ($post) {
                $category = $this->getCategory($post['category_id']);
                $comentarios = $this->getComments($post['id']);

                if ($category) {
                    $post['category'] = $category;
                }

                if ($comentarios) {
                    $post['comentarios'] = $comentarios;
                }


                return $post;
            }

            return false;
        }

        public function getComment($id) {
            $comment = $this->getEntityByid('comment', $id);

            if ($comment) {
                return $comment;
            }

            return false;
        }

        public function getComments($post_id) {
            $comments = $this->getEntitys('comment', [
                [
                    'key' => 'post_id',
                    'compare' => '=',
                    'value' => $post_id
                ]
            ]);

            if ($comments) {
                foreach ($comments as $key => $comment) {
                    $user = $this->getEntityByid('user', $comment['user_id']);

                    if ($user) {
                        $comments[$key]['user'] = $user;
                    }
                }

                return $comments;
            }

            return false;
        }

    }