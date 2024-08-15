<?php

	namespace Api1_1;

	use OpenApi\Annotations as OA;

	use Stoic\Log\Logger;
	use Stoic\Utilities\ParameterHelper;
	use Stoic\Web\Api\Response;
	use Stoic\Web\Api\Stoic;
	use Stoic\Web\Request;

	use Zibings\ApiController;
	use Zibings\RoleStrings;
	use Zibings\Scream;
	use Zibings\Screams as ScreamsRepo;

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
		 * Retrieves the list of screams for the given user.
		 *
		 * @param Request $request The current request which routed to this endpoint.
		 * @param array|null $matches An array of matches returned by this endpoint's regex pattern.
		 * @return Response
		 */
		public function getUserScreams(Request $request, array $matches = null) : Response {
			$ret = $this->newResponse();

			return $ret;
		}

		protected function registerEndpoints() : void {
			$this->registerEndpoint('POST', '/^\/?Screams\/?$/i', 'createScream',   true);
			$this->registerEndpoint('GET',  '/^\/?Screams\/?$/i', 'getUserScreams', null);

			return;
		}
	}
