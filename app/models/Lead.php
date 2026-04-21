<?php

class Lead {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Retrieve a lead record by its token.
     * Sensitive fields (ssn, etc.) are intentionally excluded.
     *
     * @param string $token
     * @return array|false
     */
    public function getByToken($token)
    {
        $this->db->query(
            "SELECT
                email,
                city,
                state,
                zipcode
             FROM lifeline_records
             WHERE lead_token = :token
             LIMIT 1"
        );
        $this->db->bind(':token', $token);
        return $this->db->single();
    }
}
