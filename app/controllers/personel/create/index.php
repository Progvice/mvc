<?php

use Core\App\Response;

class personelController extends Controller
{
    public function personel()
    {
        Plugin::load('response, models');
        $response = new Response;

        $data = json_decode(file_get_contents("php://input"), true);

        $models = new Core\App\Models\MainModel;

        $models->CallModel('personel');

        $requiredFields = $models->GetRequiredFields();

        if (isset($requiredFields['status']) && $requiredFields['status'] === false) {
            $response->Send('json', $requiredFields);
            return;
        }

        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                $missingFields[] = $field;
            }
        }

        if (count($missingFields) > 0) {
            $response->SetCode(422);
            $response->Send('json', [
                'status' => false,
                'msg' => 'missing_fields',
                'fields' => $missingFields
            ]);
            return;
        }

        $allFields = $models->GetFields();

        $data = array_intersect_key($data, array_flip($allFields));

        $personelInsert = $models->Insert($data);

        if (!$personelInsert['status']) {
            $response->Send('json', $personelInsert);
            return;
        }

        if (isset($personelInsert['id'])) {
            $personel = $models->Select([
                'values' => [
                    'normal' => [
                        'id' => $personelInsert['id']
                    ]
                ]
            ]);
        }

        $response->Send('json', [
            'status' => true,
            'msg' => 'insertsuccess',
            'data' => $personel ?? null
        ]);
    }
}
