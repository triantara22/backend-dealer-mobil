<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Config\Services;

class AuthFilter implements FilterInterface
{
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
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return Services::response()->setJSON(['message' => 'Token tidak ditemukan'])->setStatusCode(401);
        }

        $token = $matches[1];
        $key = getenv('token_secret');

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
        } catch (ExpiredException $e) {
            return Services::response()->setJSON(['message' => 'Token kadaluarsa'])->setStatusCode(401);
        } catch (\Exception $e) {
            return Services::response()->setJSON(['message' => 'Token tidak valid'])->setStatusCode(401);
        }

        // Validasi role jika diberikan
        if ($arguments && !in_array($decoded->data->role, $arguments)) {
            return Services::response()->setJSON(['message' => 'Akses ditolak: role tidak sesuai'])->setStatusCode(403);
        }

        // Inject ke request jika perlu (opsional)
        $request->user = $decoded->data;

        return null;
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
