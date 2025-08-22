<?php

use Core\App\Models\MainModel;
use Core\App\Response;

class personelController extends Controller
{
    public function personel()
    {
        Plugin::load('response, models');
        $response = new Response;

        $models = new MainModel;
        $models->CallModel('personel');

        $personel = $models->Select([
            'limit' => 10,
            'offset' => 0
        ]);

        $response->Send('json', [
            'status' => true,
            'msg' => 'successall',
            'data' => $personel
        ]);
    }

    public function personelById()
    {

        Plugin::load('response, models');

        $response = new Response;
        $models = new MainModel;

        $models->CallModel('personel');

        $personel = $models->Select([
            'values' => [
                'normal' => [
                    'id' => $this->params[0]
                ]
            ]
        ]);

        if (count($personel) < 1) {
            $response->SetCode(404);
            $response->Send('json', [
                'status' => false,
                'msg' => 'notfound'
            ]);
            return;
        }

        $response->Send('json', [
            'status' => true,
            'msg' => 'success',
            'data' => $personel
        ]);
    }

    public function searchPersonel()
    {
        Plugin::load('response, models');

        $response = new Response;

        $models = new MainModel;

        $data = json_decode(file_get_contents("php://input"), true);
        $models->CallModel('personel');

        $searchResults = $models->Select([
            'values' => [
                'contains' => $data
            ]
        ]);

        $response->Send('json', [
            'status' => true,
            'msg' => 'success',
            'data' => $searchResults
        ]);
    }
}
