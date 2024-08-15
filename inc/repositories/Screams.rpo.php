<?php

	namespace Zibings;

	use Stoic\Pdo\BaseDbQueryTypes;
	use Stoic\Pdo\StoicDbClass;

	/**
	 * Repository methods for dealing with Screams.
	 *
	 * @package Zibings
	 */
	class Screams extends StoicDbClass {
		protected Scream $screamObj;


		/**
		 * Initializes the internal Scream instance.
		 *
		 * @return void
		 */
		protected function __initialize() : void {
			$this->screamObj = new Scream($this->db, $this->log);

			return;
		}

		/**
		 * Retrieves all ScreamHistory records for the given Scream.
		 *
		 * @param int $screamId Integer identifier for the Scream in question.
		 * @return ScreamHistory[]
		 */
		public function getScreamHistory(int $screamId) : array {
			$ret = [];
			$this->tryPdoExcept(function () use ($screamId, &$ret) {
				$stmt = $this->db->prepare($this->screamObj->generateClassQuery(BaseDbQueryTypes::SELECT, false) . " WHERE `ScreamID` = :screamId");
				$stmt->bindValue(':screamId', $screamId, \PDO::PARAM_INT);

				if ($stmt->execute() && $stmt->rowCount() > 0) {
					while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
						$ret[] = ScreamHistory::fromArray($row, $this->db, $this->log);
					}
				}

				return;
			}, "Failed to retrieve Scream history");

			return $ret;
		}

		/**
		 * Retrieves the number of Screams for the given user.
		 *
		 * @param int $userId Identifier for the user to retrieve screams for.
		 * @return int
		 */
		public function getUserScreamCount(int $userId) : int {
			$ret = 0;
			$this->tryPdoExcept(function () use ($userId, &$ret) {
				$stmt = $this->db->prepare("SELECT COUNT(*) AS `Count` FROM `Screams` WHERE `UserID` = :userId");
				$stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);

				if ($stmt->execute() && $stmt->rowCount() > 0) {
					$ret = intval($stmt->fetch(\PDO::FETCH_ASSOC)['Count']);
				}

				return;
			}, "Failed to retrieve user scream count");

			return $ret;
		}

		/**
		 * Retrieves all ScreamHistory records for the given Scream.  Optionally allows for ordering and limiting.
		 *
		 * @param int $userId Identifier for the user to retrieve screams for.
		 * @param string|null $orderColumn Optional column to sort on, defaults to Scream identifier. 
		 * @param string|null $orderDirection Optional direction to sort in, defaults to descending.
		 * @param int|null $offset Optional offset for paging.  Only used if limit is also provided.
		 * @param int|null $limit Optional limit for paging.  Only used if offset is also provided.
		 * @return Scream[]
		 */
		public function getUserScreams(int $userId, null|string $orderColumn = null, null|string $orderDirection = null, null|int $offset = null, null|int $limit = null) : array {
			$ret = [];
			$this->tryPdoExcept(function () use ($userId, $orderColumn, $orderDirection, $offset, $limit, &$ret) {
				$sql = $this->screamObj->generateClassQuery(BaseDbQueryTypes::SELECT, false) . " WHERE `UserID` = :userId";

				if ($orderColumn !== null) {
					$orderDirection = $orderDirection === null ? "DESC" : $orderDirection;
					$sql           .= " ORDER BY `{$orderColumn}` {$orderDirection}";
				}

				if ($offset !== null && $limit !== null) {
					$sql .= " LIMIT {$offset},{$limit}";
				}

				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);

				if ($stmt->execute() && $stmt->rowCount() > 0) {
					while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
						$ret[] = Scream::fromArray($row, $this->db, $this->log);
					}
				}

				return;
			}, "Failed to retrieve user screams");

			return $ret;
		}
	}
