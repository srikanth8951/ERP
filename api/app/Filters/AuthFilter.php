<?php
    namespace App\Filters;

    use CodeIgniter\Filters\FilterInterface;
    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use App\Libraries\Auth\User as UserAuth;

    class AuthFilter implements FilterInterface
    {

        public function before(RequestInterface $request, $arguments = null)
        {
			$user_id = UserAuth::isLogged();
			if (! $user_id) {
				$response = \Config\Services::response();

                $responseData = array(
                    'status' => 'error',
                    'message' => lang('Common.error_login')
                );
            
                return $response->setJSON($responseData)->setStatusCode(401);
			}
        }

        public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
        {

        }
    }