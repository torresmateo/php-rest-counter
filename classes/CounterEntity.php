<?php 

class CounterEntity
{
    protected $id;
    public $name;
    protected $password;
    public $value;

    /**
     * Accept an array of data matching properties of this class
     * and create the class
     *
     * @param array $data The data to use to create
     */
    public function __construct(array $data) {
        // no id if we're creating
        if(isset($data['id'])) {
            $this->id = $data['id'];
        }
        $this->name = $data['name'];
        $this->password = $data['password'];
        $this->value = 0;
        if(isset($data['value'])) {
            $this->value = $data['value'];
        }
    }


    public function getId(){ return $this->id; }
    public function getName(){ return $this->name; }
    public function getPassword(){ return $this->password; }
    public function getValue(){ return $this->value; }
}
