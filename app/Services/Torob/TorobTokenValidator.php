<?php

namespace App\Services\Torob;

use Illuminate\Http\Request;
use SodiumException;

class TorobTokenValidator
{
    public function validate(Request $request): void
    {
        if (config('torob.skip_auth')) {
            return;
        }

        $token = $request->header('X-Torob-Token');
        if (empty($token)) {
            throw new TorobAuthException('X-Torob-Token header is missing');
        }

        $version = (string) $request->header('X-Torob-Token-Version', '');
        if ($version !== (string) config('torob.token_version')) {
            throw new TorobAuthException('Invalid X-Torob-Token-Version');
        }

        $audience = config('torob.audience') ?: $this->resolveAudience($request);
        $payload = $this->decodeAndVerify($token);

        if (!isset($payload['aud'])) {
            throw new TorobAuthException('Token audience claim is missing');
        }

        if ($payload['aud'] !== $audience) {
            throw new TorobAuthException('Invalid token audience');
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeAndVerify(string $jwt): array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            throw new TorobAuthException('Malformed JWT');
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;
        $header = json_decode($this->base64UrlDecode($encodedHeader), true);
        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);

        if (!is_array($header) || !is_array($payload)) {
            throw new TorobAuthException('Malformed JWT payload');
        }

        if (($header['alg'] ?? null) !== 'EdDSA') {
            throw new TorobAuthException('Unsupported JWT algorithm');
        }

        if (($header['typ'] ?? null) !== 'JWT') {
            throw new TorobAuthException('Unsupported JWT type');
        }

        if (!function_exists('sodium_crypto_sign_verify_detached')) {
            throw new TorobAuthException('Sodium extension is required for Torob JWT validation');
        }

        try {
            $valid = sodium_crypto_sign_verify_detached(
                $this->base64UrlDecode($encodedSignature),
                $encodedHeader . '.' . $encodedPayload,
                $this->publicKeyBytes()
            );
        } catch (SodiumException $e) {
            throw new TorobAuthException('Invalid Torob token signature', 0, $e);
        }

        if (!$valid) {
            throw new TorobAuthException('Invalid Torob token signature');
        }

        $now = time();

        if (!isset($payload['exp'])) {
            throw new TorobAuthException('Token expiration claim is missing');
        }

        if ($now > (int) $payload['exp']) {
            throw new TorobAuthException('Token has expired');
        }

        if (isset($payload['nbf']) && $now < (int) $payload['nbf']) {
            throw new TorobAuthException('Token is not yet valid');
        }

        return $payload;
    }

    private function publicKeyBytes(): string
    {
        $decoded = base64_decode(config('torob.public_key'), true);
        if ($decoded === false || strlen($decoded) < 32) {
            throw new TorobAuthException('Invalid Torob public key configuration');
        }

        return substr($decoded, -32);
    }

    private function base64UrlDecode(string $value): string
    {
        $remainder = strlen($value) % 4;
        if ($remainder > 0) {
            $value .= str_repeat('=', 4 - $remainder);
        }

        $decoded = base64_decode(strtr($value, '-_', '+/'), true);
        if ($decoded === false) {
            throw new TorobAuthException('Invalid base64 in JWT');
        }

        return $decoded;
    }

    private function resolveAudience(Request $request): string
    {
        $host = $request->getHost();
        $port = $request->getPort();

        if ($port && !in_array($port, [80, 443], true)) {
            return $host . ':' . $port;
        }

        return $host;
    }
}
