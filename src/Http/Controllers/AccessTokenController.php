<?php

namespace Laravel\Passport\Http\Controllers;

use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use Zend\Diactoros\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\AuthorizationServer;

class AccessTokenController
{
    use HandlesOAuthErrors;

    /**
     * The authorization server.
     *
     * @var AuthorizationServer
     */
    protected $server;

    /**
     * The token repository instance.
     *
     * @var TokenRepository
     */
    protected $tokens;

    /**
     * The JWT parser instance.
     *
     * @var JwtParser
     */
    protected $jwt;

    /**
     * Create a new controller instance.
     *
     * @param  AuthorizationServer  $server
     * @param  TokenRepository  $tokens
     * @param  JwtParser  $jwt
     * @return void
     */
    public function __construct(
        AuthorizationServer $server,
        TokenRepository $tokens,
        JwtParser $jwt
    ) {
        $this->jwt = $jwt;
        $this->server = $server;
        $this->tokens = $tokens;
    }

    /**
     * Authorize a client to access the user's account.
     *
     * @param  ServerRequestInterface  $request
     * @return Response
     */
    public function issueToken(ServerRequestInterface $request)
    {
        if (Passport::$useClientUUIDs) {
            $rq = request();

            $client = Client::where('uuid', $rq->input('client_id'))->firstOrFail();

            $formData = $request->getParsedBody();

            array_set($formData, 'client_id', $client->id);

            $request = $request->withParsedBody($formData);
        }

        return $this->withErrorHandling(function () use ($request) {
            return $this->server->respondToAccessTokenRequest($request, new Psr7Response);
        });
    }
}
