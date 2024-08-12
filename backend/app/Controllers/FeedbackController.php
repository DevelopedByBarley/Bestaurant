<?php

namespace App\Controllers;

use App\Models\Feedback;
use Exception;
use PDO;

class FeedbackController extends Controller
{
    private $Feedback;

    public function __construct()
    {
        parent::__construct();
        $this->Feedback = new Feedback();
    }


    public function feedback()
    {
        try {
            $ip = $this->getIpByUser();
            $isExist = $this->Model->selectByRecord('feedbacks', 'user_ip', $ip, PDO::PARAM_STR) || isset($_COOKIE['rating_denied']);

            echo json_encode(
                ['isExist' => $isExist]
            );
        } catch (Exception $e) {
            http_response_code(500);
            echo "Internal Server Error" . $e->getMessage();
            exit;
        }
    }

    public function storeFeedback()
    {
        try {
            // Elérjük a POST adatokat php://input segítségével
            $inputJSON = file_get_contents('php://input');
            // Dekódoljuk a JSON adatot asszociatív tömbbé
            $body = json_decode($inputJSON, true);

            // Ellenőrizzük, hogy sikerült-e a dekódolás és hogy megvannak-e a szükséges adatok
            if (json_last_error() !== JSON_ERROR_NONE || !isset($body['feedback'])) {
                throw new Exception('Invalid JSON data');
            }

            // További adatok feldolgozása
            $ip = $this->getIpByUser();



            $feedback = isset($body["feedback"]) && filter_var($body["feedback"], FILTER_VALIDATE_INT) !== false
                ? filter_var($body["feedback"], FILTER_VALIDATE_INT)
                : 0;

            $content = isset($body["content"]) ? filter_var($body["content"], FILTER_SANITIZE_SPECIAL_CHARS) : '';

            if ($feedback === 0) {
                $this->setCookieWithExpiry('rating_denied', 1, 1 * 24 *60*60); 
                return;
            }
            $this->Feedback->store($feedback, $content, $ip);
            http_response_code(200);

            echo json_encode(['isSuccess' => true]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo "Internal Server Error: " . $e->getMessage();
            exit;
        }
    }
}
