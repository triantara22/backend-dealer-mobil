<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CorsFilters implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Tangani request preflight (OPTIONS)
        if ($request->getMethod(true) === 'OPTIONS') {
            $response = service('response');
            return $response
                ->setStatusCode(200)
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE, PATCH')
                ->setHeader('Access-Control-Allow-Headers', 'X-API-KEY, Origin, Content-Type, Authorization, X-Requested-With, Accept')
                ->setHeader('Access-Control-Allow-Credentials', 'true')
                ->setBody(''); // kosongkan isi agar cepat selesai
        }

        // Penting: jangan return apa pun jika bukan OPTIONS
        // biarkan lanjut ke controller dan after filter
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE, PATCH')
            ->setHeader('Access-Control-Allow-Headers', 'X-API-KEY, Origin, Content-Type, Authorization, X-Requested-With, Accept')
            ->setHeader('Access-Control-Allow-Credentials', 'true');
    }
}
