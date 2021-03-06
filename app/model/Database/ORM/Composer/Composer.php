<?php

namespace App\Model\Database\ORM\Composer;

use App\Model\Database\Helpers\ComposerLinker;
use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use Nette\Utils\DateTime;

/**
 * @property int $id                            {primary}
 * @property Addon $addon                       {1:1 Addon::$composer, isMain=true}
 * @property string $name
 * @property string|NULL $description
 * @property string|NULL $type
 * @property int|NULL $downloads
 * @property string|NULL $keywords
 * @property DateTime $crawledAt                {default now}
 *
 * @property ComposerLinker $linker             {virtual}
 */
class Composer extends AbstractEntity
{

	/** @var ComposerLinker */
	private $linker;

	/**
	 * VIRTUAL *****************************************************************
	 */

	/**
	 * @return ComposerLinker
	 */
	protected function getterLinker()
	{
		if (!$this->linker) {
			$this->linker = new ComposerLinker($this);
		}

		return $this->linker;
	}

	/**
	 * Called before persist to storage
	 *
	 * @return void
	 */
	protected function onBeforePersist()
	{
		parent::onBeforePersist();

		$this->crawledAt = new DateTime();
	}

}
