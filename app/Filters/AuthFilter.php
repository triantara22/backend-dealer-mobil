<?php
namespace App\Filters;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

class AuthFilter implements FilterInterface
{
    use ResponseTrait;
    protected $response;
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */

    public function before(RequestInterface $request, $arguments = null)
    {
        $this->response = Services::response();

        $key    = getenv('token_secret');
        $header = $request->getServer('HTTP_AUTHORIZATION');

        // 1. Check for Authorization header
        if (! $header) {
            return $this->failUnauthorized('Token required');
        }

        // 2. Extract token from header
        $tokenParts = explode(' ', $header);
        if (count($tokenParts) !== 2 || $tokenParts[0] !== 'Bearer') {
            return $this->failUnauthorized('Format token tidak valid. Gunakan: Bearer <token>');
        }
        $token = $tokenParts[1];

        try {
            // 3. Decode and validate token
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            // 4. Check role claim
            if (! isset($decoded->data->role) || $decoded->data->role !== 'admin') {
                throw new Exception('Akses ditolak: Hanya admin yang diizinkan');
            }

            // 5. Store user data in request
            $request->user = $decoded->data;

        } catch (ExpiredException $e) {
            return $this->failUnauthorized('Token kadaluarsa');
        } catch (SignatureInvalidException $e) {
            return $this->failUnauthorized('Token tidak valid');
        } catch (Exception $e) {
            return $this->failUnauthorized($e->getMessage());
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
