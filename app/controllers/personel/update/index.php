<?php

use Core\App\Response;

class personelController extends Controller
{
    public function personel()
    {
        Plugin::load('response, models');
        $response = new Response;
        $models = new Core\App\Models\MainModel;

        $models->CallModel('personel');
        $data = json_decode(file_get_contents("php://input"), true);
        $fields = $models->GetFields();
        $updateableData = array_intersect_key($data, array_flip($fields));

        $updatePersonel = $models->Update([
            'where' => [
                'id' => $this->params[0]
            ],
            'data' => $updateableData
        ]);

        if (!$updatePersonel['status']) {
            $response->SetCode(404);
            $response->Send('json', [
                'status' => false,
                'msg' => 'notfound',
                'data' => $updatePersonel
            ]);
        }

        if ($updatePersonel['rowcount'] < 1) {
            $response->SetCode(404);
            $response->Send('json', [
                'status' => false,
                'msg' => 'notfound',
            ]);
            return;
        }

        $personnel = $models->Select([
            'values' => [
                'normal' => [
                    'id' => $this->params[0]
                ]
            ]
        ]);

        $response->Send('json', [
            'status' => true,
            'message' => 'success',
            'data' => $personnel
        ]);
    }
}
