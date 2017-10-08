<?php

namespace App\Http\Controllers;

use App\Http\Requests\Secret\ShowRequest;
use App\Http\Requests\Secret\StoreRequest;
use App\Models\Redis\Secret;
use App\Services\SecretService;

class SecretController extends Controller
{
    private $service;

    public function __construct(SecretService $secretService)
    {
        $this->service = $secretService;
    }

    public function show(ShowRequest $request, Secret $secret)
    {
        $password = $request->get('password');

        // FIXME: erhhh...
        if ($secret->needsDeletion()) {
            $secret->delete();
            return $this->error('Secret expired');
        }

        $text = $this->service->decipher($secret, $password);

        if ($text === false) {
            return $this->error('Wrong password', ['attempts_left' => $secret->getAttemptsLeft()]);
        }

        return response()->json(['text' => $text]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->only(['expire_sec', 'secret', 'password', 'ip']);

        $secret = $this->service->create($data);

        return response()
            ->json(['uuid' => $secret->uuid])
            ->setStatusCode(201);
    }
}
