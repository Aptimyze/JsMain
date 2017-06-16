<?php

class billing_BANK extends TABLE
{
    public function __construct($szDBName = "")
    {
        parent::__construct($szDBName);
    }

    public function getName()
    {
        try {
            $sql  = "SELECT NAME FROM billing.BANK";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            $i = 0;
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $bank[$i] = $result['NAME'];
                $i++;
            }
            return $bank;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
}
