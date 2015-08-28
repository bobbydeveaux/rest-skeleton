<?php

namespace DVO\Controller;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use DVO\Entity\User\UserFactory;

class UserController extends AbstractController
{
    protected $userFactory;
    /**
     * UserController constructor.
     *
     * @param BrokerFactory $userFactory The UserFactory factory.
     */
    public function __construct(UserFactory $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    /**
     * Handles the HTTP GET.
     *
     * @param Request     $request The request.
     * @param Application $app     The app.
     *
     * @return JsonResponse
     */
    public function indexJsonAction(Request $request, Application $app)
    {
        $search = $request->query->all();

        if (true === isset($search['id'])) {
            unset($search['id']);
        }

        if (true === isset($search['network'])) {
            unset($search['network']);
        }

        if ($userId = $request->attributes->get('id')) {
            $search['id'] = $userId;
        }

        try {
            $users  = $this->userFactory->getUsers($search);
        } catch (\DVO\Entity\EntityAbstract\EntityAbstractGateway\Exception $e) {
            return $this->errorJsonResponse(['exception' => $e->getMessage()]);
        }

        /* @codingStandardsIgnoreStart */
        $users = array_map(function($user) use ($request, $userId) {
            $s                           = [];
            $s['_links']['self']['href'] = $request->getPathInfo();
            $s['id']                     = $user->getId();
            $s['username']               = $user->getUsername();
            $s['full']                   = $user->getData();
            if (true === empty($userId)) {
                $s['_links']['self']['href'] .= '/' . $user->getId();
            }
            return $s;
        }, $users);
        /* @codingStandardsIgnoreEnd */

        $response = [];
        $response['_links']['self']['href'] = $request->getPathInfo();
        $response['_embedded']['users']     = $users;
        $response['count']                  = count($users);
        $response['total']                  = $this->userFactory->getGateway()->countUsers();

        return new JsonResponse(
            $response,
            200,
            [
                'ETag'          => 'PUB' . time(),
                'Last-Modified' => gmdate("D, d M Y H:i:s", time()) . " GMT",
                'Cache-Control' => 'maxage=3600, s-maxage=3600, public',
                'Expires'       => time()+3600
            ]
        );
    }

    /**
     * Create a new user with a POST request
     *
     * @param  Request      $request
     * @param  Application  $app
     * @return JsonResponse
     */
    public function createJsonAction(Request $request, Application $app)
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->userFactory->create($data);

        try {
            $id = $this->userFactory->getGateway()->insertUser($user);
            if (true === empty($id)) {
                return $this->errorJsonResponse(['error' => 'User could not be inserted.']);
            }

            $request->attributes->set('id', $id);

            return $this->indexJsonAction($request, $app);
        } catch (\DVO\Entity\EntityAbstract\EntityAbstractGateway\Exception $e) {
            return $this->errorJsonResponse(['exception' => $e->getMessage()]);
        }
    }

    /**
     * Update an existing user with a PUT request
     *
     * @param  Request      $request
     * @param  Application  $app
     * @return JsonResponse
     */
    public function updateJsonAction(Request $request, Application $app)
    {
        $data   = json_decode($request->getContent(), true);
        $userId = (int) $request->attributes->get('id');

        $search = [];
        if (isset($userId) === false) {
            return $this->errorJsonResponse(['error' => 'User ID must be provided and an integer.']);
        } else {
            $search['id'] = $userId;
        }

        $users = $this->userFactory->getUsers($search);

        if (count($users) !== 1) {
            return $this->errorJsonResponse(['error' => 'Unexpected number of responses']);
        }

        $user = reset($users);

        try {
            if (true === $this->userFactory->getGateway()->updateUser($user, $data)) {
                $request->attributes->set('id', $userId);

                return $this->indexJsonAction($request, $app);
            } else {
                return $this->errorJsonResponse(['error' => 'User was not successfully updated']);
            }
        } catch (\DVO\Entity\EntityAbstract\EntityAbstractGateway\Exception $e) {
            return $this->errorJsonResponse(['exception' => $e->getMessage()]);
        }
    }

    /**
     * Deleted an existing user with a PUT request
     *
     * @param  Request      $request
     * @param  Application  $app
     * @return JsonResponse
     */
    public function deleteJsonAction(Request $request, Application $app)
    {
        $data   = ['deleted' => 1];
        $userId = (int) $request->attributes->get('id');

        $search = [];
        if (isset($userId) === false) {
            return $this->errorJsonResponse(['error' => 'User ID must be provided and an integer.']);
        } else {
            $search['id'] = $userId;
        }

        $users = $this->userFactory->getUsers($search);

        if (count($users) !== 1) {
            return $this->errorJsonResponse(['error' => 'Unexpected number of responses']);
        }

        $user = reset($users);

        try {
            if (true === $this->userFactory->getGateway()->updateUser($user, $data)) {
                $request->attributes->set('id', $userId);

                return $this->indexJsonAction($request, $app);
            } else {
                return $this->errorJsonResponse(['error' => 'User was not successfully updated']);
            }
        } catch (\DVO\Entity\EntityAbstract\EntityAbstractGateway\Exception $e) {
            return $this->errorJsonResponse(['exception' => $e->getMessage()]);
        }
    }

    /**
     * Handle unexpected responses
     *
     * @param  array        $error
     * @return JsonResponse
     */
    public function errorJsonResponse(array $error)
    {
        return new JsonResponse($error);
    }
}
