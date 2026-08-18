<?php

use Core\App\Response;

class PersonelDeleteController extends Controller
{
    public function personelDelete()
    {
        Plugin::load('response, models');
        $response = new Response;

        $models = new Core\App\Models\MainModel;

        $models->CallModel('personel');

        $personelDelete = $models->Delete([
            'where' => [
                'id' => $this->params[0]
            ]
        ]);

        if (!$personelDelete['status']) {
            $response->SetCode(404);
            $response->Send('json', $personelDelete);
            return;
        }

        if ($personelDelete['rowcount'] < 1) {
            $response->SetCode(404);
            $response->Send('json', [
                'status' => false,
                'msg' => 'notfound'
            ]);
            return;
        }

        $response->Send('json', [
            'status' => true,
            'msg' => 'success'
        ]);
    }
}
