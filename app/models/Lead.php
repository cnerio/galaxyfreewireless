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
                customer_id,
                first_name,
                second_name,
                dob,
                email,
                phone_number,
                address1,
                address2,
                city,
                state,
                zipcode,
                shipping_address1,
                shipping_address2,
                shipping_city,
                shipping_state,
                shipping_zipcode,
                program_benefit,
                phone_type,
                order_step,
                URL,
                utms,
                company,
                ETC,
                datetimeConsent,
                created_at
             FROM lifeline_records
             WHERE lead_token = :token
             LIMIT 1"
        );
        $this->db->bind(':token', $token);
        return $this->db->single();
    }
}
