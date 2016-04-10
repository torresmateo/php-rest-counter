<?php

class CounterMapper extends Mapper
{
    public function getCounters() {
        $sql = 'SELECT c.id, c.name, c.password, c.value
            from counts c';
        $stmt = $this->db->query($sql);
        
        $results = [];
        while($row = $stmt->fetch()){
            $results[] = new CounterEntity($row);
        }

        return $results;
    }

    public function getCounterByName($name) {
        $sql = 'SELECT c.id, c.name, c.password, c.value
            from counts c
            where c.name = :name';
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['name' => $name]);

        if($result && $data = $stmt->fetch()){
            return new CounterEntity($data);
        }
    }

    public function getCounterByCredentials($name, $password) {
        $sql = 'SELECT c.id, c.name, c.password, c.value
            from counts c
            where c.name = :name and c.password = :password';
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(
            [
                'name' => $name,
                'password' => $password
            ]
        );

        if($result && $data = $stmt->fetch()){
            return new CounterEntity($data);
        }
    }

    public function insert(CounterEntity $count) {
        $sql = 'INSERT into counts (name, password, value)
            values (:name, :password, :value)';
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'name' => $count->getName(),
            'password' => $count->getPassword(),
            'value' => $count->getValue(),
        ]);

        if(!$result) {
            throw new Exception("could not save record");
        }
    }

    public function update(CounterEntity $count) {
        $sql = 'UPDATE counts c
            set c.name = :name, c.password = :password, c.value = :value
            where c.id = :id';

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id' => $count->getId(),
            'name' => $count->getName(),
            'password' => $count->getPassword(),
            'value' => $count->getValue(),
        ]);
    }
}
