<?php

class Api extends Controller {
    private $leadModel;

    public function __construct()
    {
        $this->leadModel = $this->model('Lead');
    }

    /**
     * GET /api/lead/<token>
     *
     * Returns the lead information associated with the given token.
     * Responds with JSON in all cases.
     */
    public function lead($token = null)
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed.'
            ]);
            return;
        }

        if (empty($token) || !ctype_xdigit($token) || strlen($token) !== 64) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'A valid token is required.'
            ]);
            return;
        }

        $lead = $this->leadModel->getByToken($token);

        if (empty($lead)) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'No lead found for the provided token.'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'data'    => $lead
        ]);
    }
}
