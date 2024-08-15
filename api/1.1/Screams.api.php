<?php

	namespace Api1_1;

	use OpenApi\Annotations as OA;

	use Stoic\Log\Logger;
	use Stoic\Utilities\ParameterHelper;
	use Stoic\Web\Api\Response;
	use Stoic\Web\Api\Stoic;
	use Stoic\Web\Request;
	use Stoic\Web\Resources\HttpStatusCodes;

	use Zibings\ApiController;
	use Zibings\RoleStrings;
	use Zibings\Scream;
	use Zibings\Screams as ScreamsRepo;
	use Zibings\UserProfile;

	/**
	 * API controller that deals with user screams endpoints.
	 *
	 * @OA\Tag(
	 *   name="Screams",
	 *   description="Scream operations controller"
	 * )
	 * 
	 * @package Zibings\Api1_1
	 */
	class Screams extends ApiController {
		/**
		 * Instantiates a new Screams object.
		 *
		 * @param Stoic $stoic Stoic API instance for internal use.
		 * @param \PDO $db PDO instance for internal use.
		 * @param Logger|null $log Optional Logger object for internal use, new instance created if not provided.
		 * @param ScreamsRepo|null $screams Optional ScreamsRepo object for internal use, new instance created if not provided.
		 * @return void
		 */
		public function __construct(
			Stoic $stoic,
			\PDO $db,
			Logger $log                         = null,
			protected null|ScreamsRepo $screams = null
		) {
			parent::__construct($stoic, $db, $log);

			if ($this->screams === null) {
				$this->screams = new ScreamsRepo($this->db, $this->log);
			}

			return;
		}

		/**
		 * Attempts to create a new scream for the current user.
		 *
		 * @param Request $request The current request which routed to this endpoint.
		 * @param array|null $matches An array of matches returned by this endpoint's regex pattern.
		 * @return Response
		 */
		public function screamAtVoid(Request $request, array $matches = null) : Response {
			$user   = $this->getUser();
			$ret    = $this->newResponse();
			$params = $request->getInput();

			if (!$params->hasAll('body')) {
				$ret->setAsError("Missing required parameters");

				return $ret;
			}

			$scream         = new Scream($this->db, $this->log);
			$scream->body   = $params->getString('body');
			$scream->userId = $user->id;
			$create         = $scream->create();

			if ($create->isBad()) {
				$this->assignReturnHelperError($ret, $create, "You failed to scream into the void");

				return $ret;
			}

			$ret->setData($scream);

			return $ret;
		}

		/**
		 * Retrieves the list of screams for the given user.
		 *
		 * @param Request $request The current request which routed to this endpoint.
		 * @param array|null $matches An array of matches returned by this endpoint's regex pattern.
		 * @return Response
		 */
		public function getUserScreams(Request $request, array $matches = null) : Response {
			$userId      = 0;
			$user        = $this->getUser();
			$ret         = $this->newResponse();
			$params      = $request->getInput();
			$displayName = count($matches) > 1 ? $matches[1][0] : null;

			if ($displayName === null && $user->id < 1) {
				$ret->setAsError("User not found", HttpStatusCodes::NOT_FOUND);

				return $ret;
			}

			$userId = $user->id;

			if ($displayName !== null) {
				$profile = UserProfile::fromDisplayName($displayName, $this->db, $this->log);

				if ($profile->userId > 0) {
					$userId = $profile->userId;
				}
			}

			if ($userId < 1) {
				$ret->setAsError("User not found", HttpStatusCodes::NOT_FOUND);

				return $ret;
			}

			$orderColumn    = $params->getString('orderColumn', null);
			$orderDirection = $params->getString('orderDirection', null);
			$offset         = $params->getInt('offset', null);
			$limit          = $params->getInt('limit', null);

			$ret->setData($this->screams->getUserScreams($userId, $orderColumn, $orderDirection, $offset, $limit));

			return $ret;
		}

		/**
		 * Registers the controller endpoints.
		 *
		 * @return void
		 */
		protected function registerEndpoints() : void {
			$this->registerEndpoint('PATCH', '/^\/?Screams\/([0-9]+)\/?$/i',        'updateScream',   true);
			$this->registerEndpoint('POST',  '/^\/?Screams\/?$/i',                  'screamAtVoid',   true);
			$this->registerEndpoint('GET',   '/^\/?Screams\/([a-z0-9-_.]+)\/?$/i',  'getUserScreams', null);
			$this->registerEndpoint('GET',   '/^\/?Screams\/?$/i',                  'getUserScreams', true);

			return;
		}

		/**
		 * Attempts to modify an existing scream for the current user.
		 *
		 * @param Request $request 
		 * @param array|null $matches 
		 * @return Response
		 */
		public function updateScream(Request $request, array $matches = null) : Response {
			$user   = $this->getUser();
			$ret    = $this->newResponse();
			$scream = Scream::fromId(intval($matches[1][0]), $this->db, $this->log);

			if ($scream->id < 1) {
				$ret->setAsError("Scream not found", HttpStatusCodes::NOT_FOUND);

				return $ret;
			}

			return $ret;
		}
	}
