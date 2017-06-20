<?php

namespace App\Http\Controllers;

use App\Http\Requests\Secret\ShowRequest;
use App\Http\Requests\Secret\StoreRequest;
use App\Models\Secret;
use App\Repositories\SecretRepository;

class SecretController extends Controller
{
    private $repository;

    /**
     * SecretController constructor.
     * @param SecretRepository $repository
     */
    public function __construct(SecretRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ShowRequest $request
     * @param Secret $secret
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request, Secret $secret)
    {
        $password = $request->get('password');

        // FIXME: erhhh...
        if ($secret->needsDeletion()) {
            $secret->delete();
            return $this->error('Secret expired');
        }

        $text = $this->repository->decipher($secret, $password);

        if ($text === false) {
            return $this->error('Wrong password', ['attempts_left' => $secret->getAttemptsLeft()]);
        }

        return response()->json(['text' => $text]);
    }

    /**
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $request->only(['expire_sec', 'secret', 'password', 'ip']);

        $secret = $this->repository->create($data);

        return response()
            ->json(['uuid' => $secret->uuid])
            ->setStatusCode(201);
    }
}
