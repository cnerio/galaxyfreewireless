<?php

class Page {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Returns the GTW and AMBT coverage flags for a given state abbreviation.
     * TX is a special case for GTW: coverage is determined at the ZIP code level
     * using the lifeline_zipcodes table instead of the state-level table.
     * Returns ['GTW' => 0, 'AMBT' => 0] when no coverage is found.
     */
    public function getStateConfig($stateAbrv, $zipcode = '')
    {
        $state = strtoupper($stateAbrv);

        // --- 1. Check GTW first (highest priority) ---
        // TX: ZIP-level check; all other states: state-level check
        if ($state === 'TX') {
            $this->db->query(
                'SELECT 1 FROM lifeline_zipcodes WHERE zipcode = :zipcode AND GTW = 1 LIMIT 1'
            );
            $this->db->bind(':zipcode', $zipcode);
            $gtw = $this->db->single() ? 1 : 0;
        } else {
            $this->db->query('SELECT GTW FROM lifeline_states WHERE abrv = :abrv LIMIT 1');
            $this->db->bind(':abrv', $state);
            $row = $this->db->single();
            $gtw = $row ? (int)$row['GTW'] : 0;
        }

        // --- 2. Only check AMBT when GTW has no coverage ---
        $ambt = 0;
        if ($gtw === 0) {
            $this->db->query('SELECT AMBT FROM lifeline_states WHERE abrv = :abrv LIMIT 1');
            $this->db->bind(':abrv', $state);
            $row = $this->db->single();
            $ambt = $row ? (int)$row['AMBT'] : 0;
        }

        return ['GTW' => $gtw, 'AMBT' => $ambt];
    }

    /**
     * Persists a lead row into freewireless_records.
     * Returns the new auto-increment ID.
     */
    public function saveLead($data)
    {
        $this->db->insertQuery('freewireless_records', $data);
        return (int)$this->db->lastinsertedId();
    }
}