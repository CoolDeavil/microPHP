<?php


namespace API\Interfaces;


use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ResourceInterface
{
    public function index(ServerRequestInterface $request, ResponseInterface $response) : Response;
    public function create(ServerRequestInterface $request, ResponseInterface $response) : Response;
    public function edit(ServerRequestInterface $request, ResponseInterface $response) : Response;
    public function show(ServerRequestInterface $request, ResponseInterface $response) : Response;
    public function store(ServerRequestInterface $request);
    public function update(ServerRequestInterface $request);
    public function destroy(ServerRequestInterface $request);
}