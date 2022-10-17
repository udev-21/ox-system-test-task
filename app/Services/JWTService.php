<?php

namespace App\Services;

use App\ViewModels\Responses\JWT;
use DateTimeImmutable;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

class JWTService
{
    protected Parser $parser;
    protected Signer $algorithm;
    protected Key $accessKey;
    protected Key $refreshKey;
    
    protected int $accessTokenTL;
    protected int $refreshTokenTL;

    public function __construct(
        protected JwtFacade $jwtFacade
    ) {
        $this->algorithm = new Sha256();
        $this->accessKey = InMemory::plainText(config('jwt.access_secret'));
        $this->refreshKey = InMemory::plainText(config('jwt.refresh_secret'));
        
        $this->accessTokenTL = intval(config('jwt.access_token_tl'));
        $this->refreshTokenTL = intval(config('jwt.refresh_token_tl'));

        $this->parser = new Parser(new JoseEncoder());
    }

    public function issue(
        string $userID
    ) : JWT {
        $access = $this->jwtFacade->issue($this->algorithm, $this->accessKey, function($builder, $now) use ($userID) {
            $builder->issuedBy($userID)->expiresAt($now->setTimestamp(time() + $this->accessTokenTL));
            return $builder;
        });

        $refresh = $this->jwtFacade->issue($this->algorithm, $this->refreshKey, function($builder, $now) use($userID) {
            $builder->issuedBy($userID)->expiresAt($now->setTimestamp(time() + $this->refreshTokenTL));
            return $builder;
        });
        $response = new JWT;
        $response->accessToken = $access->toString();
        $response->refreshToken = $refresh->toString();
        return $response;
    }

    public function verifyAccess(string $token) {
        try {
            return $this->jwtFacade->parse($token, new SignedWith($this->algorithm, $this->accessKey), new LooseValidAt(new FrozenClock( new DateTimeImmutable)));            
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function verifyRefresh(string $token) {
        try {
            return  $this->jwtFacade->parse($token, new SignedWith($this->algorithm, $this->refreshKey), new LooseValidAt(new FrozenClock( new DateTimeImmutable)));   
        } catch (\Throwable $e) {
            return null;
        }
    }
}