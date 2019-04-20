<?php

namespace App\AuthToken;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use Illuminate\Support\Facades\Cache;

/**
 * 
 */
class JWToken
{
	
	protected $key = 'UsCzNQqTQIVXSBlRzbWoPGFnipURU4Fm';

	protected $minutosValidez = 1440;

	protected $emisor;
}