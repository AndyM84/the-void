<?php

	namespace Zibings;

	use Stoic\Log\Logger;
	use Stoic\Pdo\BaseDbTypes;
	use Stoic\Pdo\PdoHelper;
	use Stoic\Pdo\StoicDbModel;
	use Stoic\Utilities\ReturnHelper;

	/**
	 * Represents a point-in-time snapshot of a Scream object, stored for historical purposes.
	 *
	 * @package Zibings
	 */
	class ScreamHistory extends StoicDbModel {
		public string $body;
		public \DateTimeInterface $dateCreated;
		public null|\DateTimeInterface $dateEdited;
		public \DateTimeInterface $dateRecorded;
		public int $screamId;
		public int $parentId;
		public int $replyToId;
		public int $sourceAppId;
		public int $totalViews;
		public int $userId;


		/**
		 * Static method to create a new ScreamHistory object from a Scream object.  Returns an empty ScreamHistory if the
		 * operation fails.
		 *
		 * @param Scream $scream Scream object to create the history from.
		 * @param PdoHelper $db PdoHelper instance for internal use.
		 * @param Logger|null $log Optional Logger instance for internal use, new instance created if not provided.
		 * @return ScreamHistory
		 */
		public static function createFromScream(Scream $scream, PdoHelper $db, Logger $log = null) : ScreamHistory {
			$ret = new ScreamHistory($db, $log);

			if ($scream->id < 1) {
				return $ret;
			}

			$ret->body         = $scream->body;
			$ret->dateCreated  = $scream->dateCreated;
			$ret->dateEdited   = $scream->dateEdited;
			$ret->dateRecorded = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
			$ret->screamId     = $scream->id;
			$ret->parentId     = $scream->parentId;
			$ret->replyToId    = $scream->replyToId;
			$ret->sourceAppId  = $scream->sourceAppId;
			$ret->totalViews   = $scream->totalViews;
			$ret->userId       = $scream->userId;

			if ($ret->create()->isBad()) {
				$ret = new ScreamHistory($db, $log);
			}

			return $ret;
		}


		/**
		 * Determines if the current object can be created in the database.
		 *
		 * @return bool|ReturnHelper
		 */
		protected function __canCreate() : bool|ReturnHelper {
			$ret = new ReturnHelper();

			if ($this->screamId < 1 || empty($this->body) || $this->userId < 1) {
				$ret->addMessage('Invalid scream history data.');

				return $ret;
			}

			$ret->makeGood();

			return $ret;
		}

		/**
		 * Determines if the current object can be deleted from the database.
		 *
		 * @return bool|ReturnHelper
		 */
		protected function __canDelete() : bool|ReturnHelper {
			return false;
		}

		/**
		 * Determines if the current object can be read from the database.
		 *
		 * @return bool|ReturnHelper
		 */
		protected function __canRead() : bool|ReturnHelper {
			return false;
		}

		/**
		 * Determines if the current object can be updated in the database.
		 *
		 * @return bool|ReturnHelper
		 */
		protected function __canUpdate() : bool|ReturnHelper {
			return false;
		}

		/**
		 * Sets up the model with the necessary database information.
		 *
		 * @throws \Exception
		 * @return void
		 */
		protected function __setupModel() : void {
			$this->setTableName('Scream');
			$this->setColumn('body', 'Body', BaseDbTypes::STRING, false, true, true);
			$this->setColumn('dateCreated', 'DateCreated', BaseDbTypes::DATETIME, false, true, false);
			$this->setColumn('dateEdited', 'DateEdited', BaseDbTypes::DATETIME, false, true, true, true);
			$this->setColumn('dateRecorded', 'DateRecorded', BaseDbTypes::DATETIME, false, true, false);
			$this->setColumn('screamId', 'ScreamID', BaseDbTypes::INTEGER, true, true, false);
			$this->setColumn('parentId', 'ParentID', BaseDbTypes::INTEGER, false, true, true);
			$this->setColumn('replyToId', 'ReplyToID', BaseDbTypes::INTEGER, false, true, true);
			$this->setColumn('sourceAppId', 'SourceAppID', BaseDbTypes::INTEGER, false, true, true);
			$this->setColumn('totalViews', 'TotalViews', BaseDbTypes::INTEGER, false, true, true);
			$this->setColumn('userId', 'UserID', BaseDbTypes::INTEGER, false, true, false);

			$this->body         = null;
			$this->dateCreated  = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
			$this->dateEdited   = null;
			$this->dateRecorded = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
			$this->screamId     = 0;
			$this->parentId     = 0;
			$this->replyToId    = 0;
			$this->sourceAppId  = 0;
			$this->totalViews   = 0;
			$this->userId       = 0;

			return;
		}
	}

	/**
	 * Represents a single Scream in the database.
	 *
	 * @package Zibings
	 */
	class Scream extends StoicDbModel {
		public string $body;
		public \DateTimeInterface $dateCreated;
		public null|\DateTimeInterface $dateEdited;
		public int $id;
		public int $parentId;
		public int $replyToId;
		public int $sourceAppId;
		public int $totalViews;
		public int $userId;


		/**
		 * Static method to retrieve a Scream object from the database by its ID.  Returns an empty Scream object if the
		 * operation fails.
		 *
		 * @param int $id Identifier of the Scream to retrieve.
		 * @param PdoHelper $db PdoHelper instance for internal use.
		 * @param Logger|null $log Optional Logger instance for internal use, new instance created if not provided.
		 * @return Scream
		 */
		public static function fromId(int $id, PdoHelper $db, Logger $log = null) : Scream {
			$ret     = new Scream($db, $log);
			$ret->id = $id;

			if ($ret->read()->isBad()) {
				$ret->id = 0;
			}

			return $ret;
		}


		/**
		 * Determines if the current object can be created in the database.
		 *
		 * @return bool|ReturnHelper
		 */
		protected function __canCreate() : bool|ReturnHelper {
			$ret = new ReturnHelper();

			if ($this->id > 0 || empty($this->body) || $this->userId < 1) {
				$ret->addMessage('Invalid scream data.');

				return $ret;
			}

			$this->body        = trim(htmlspecialchars($this->body, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5));
			$this->dateCreated = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

			$ret->makeGood();

			return $ret;
		}

		/**
		 * Determines if the current object can be deleted from the database.
		 *
		 * @return bool|ReturnHelper
		 */
		protected function __canDelete() : bool|ReturnHelper {
			$ret = new ReturnHelper();

			if ($this->id < 1) {
				$ret->addMessage('Invalid scream data.');

				return $ret;
			}

			$ret->makeGood();

			return $ret;
		}

		/**
		 * Determines if the current object can be read from the database.
		 *
		 * @return bool|ReturnHelper
		 */
		protected function __canRead() : bool|ReturnHelper {
			$ret = new ReturnHelper();

			if ($this->id < 1) {
				$ret->addMessage('Invalid scream data.');

				return $ret;
			}

			$ret->makeGood();

			return $ret;
		}

		/**
		 * Determines if the current object can be updated in the database.
		 *
		 * @return bool|ReturnHelper
		 */
		protected function __canUpdate() : bool|ReturnHelper {
			$ret = new ReturnHelper();

			if ($this->id < 1 || empty($this->body) || $this->userId < 1) {
				$ret->addMessage('Invalid scream data.');

				return $ret;
			}

			ScreamHistory::createFromScream(self::fromId($this->id, $this->db, $this->log), $this->db, $this->log);

			$this->dateEdited = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

			$ret->makeGood();

			return $ret;
		}

		/**
		 * Sets up the model with the necessary database information.
		 *
		 * @throws \Exception
		 * @return void
		 */
		protected function __setupModel() : void {
			$this->setTableName('Scream');
			$this->setColumn('body', 'Body', BaseDbTypes::STRING, false, true, true);
			$this->setColumn('dateCreated', 'DateCreated', BaseDbTypes::DATETIME, false, true, false);
			$this->setColumn('dateEdited', 'DateEdited', BaseDbTypes::DATETIME, false, false, true, true);
			$this->setColumn('id', 'ID', BaseDbTypes::INTEGER, true, false, false, false, true);
			$this->setColumn('parentId', 'ParentID', BaseDbTypes::INTEGER, false, true, true);
			$this->setColumn('replyToId', 'ReplyToID', BaseDbTypes::INTEGER, false, true, true);
			$this->setColumn('sourceAppId', 'SourceAppID', BaseDbTypes::INTEGER, false, true, true);
			$this->setColumn('totalViews', 'TotalViews', BaseDbTypes::INTEGER, false, true, true);
			$this->setColumn('userId', 'UserID', BaseDbTypes::INTEGER, false, true, false);

			$this->body        = null;
			$this->dateCreated = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
			$this->dateEdited  = null;
			$this->id          = 0;
			$this->parentId    = 0;
			$this->replyToId   = 0;
			$this->sourceAppId = 0;
			$this->totalViews  = 0;
			$this->userId      = 0;

			return;
		}
	}
