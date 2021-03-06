<?php

namespace App\Model\Database\ORM\Addon;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Composer\Composer;
use App\Model\Database\ORM\ComposerStatistics\ComposerStatistics;
use App\Model\Database\ORM\Github\Github;
use App\Model\Database\ORM\Tag\Tag;
use Nette\Utils\DateTime;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;

/**
 * @property int $id                    {primary}
 * @property string $type               {enum self::TYPE_*} {default self::TYPE_UNKNOWN}
 * @property string $state              {enum self::STATE_*} {default self::STATE_QUEUED}
 * @property string $author
 * @property string $name
 * @property int|NULL $rating
 * @property DateTime $createdAt        {default now}
 * @property DateTime|NULL $updatedAt
 *
 * @property string $fullname                                     {virtual}
 * @property string $isComposer                                   {virtual}
 * @property string $isBower                                      {virtual}
 * @property ComposerStatistics|NULL $composerLatestStatistics    {virtual}
 *
 * @property Github|NULL $github                                    {1:1 Github::$addon}
 * @property Composer|NULL $composer                                {1:1 Composer::$addon}
 * @property ComposerStatistics[]|OneHasMany $composerStatistics    {1:m ComposerStatistics::$addon, orderBy=[id=DESC]}
 * @property ManyHasMany|Tag[] $tags                                {m:n Tag::$addons, isMain=true}
 */
class Addon extends AbstractEntity
{

	// Types
	const TYPE_COMPOSER = 'COMPOSER';
	const TYPE_BOWER = 'BOWER';
	const TYPE_UNTYPE = 'UNTYPE';
	const TYPE_UNKNOWN = 'UNKNOWN';

	// States
	const STATE_ACTIVE = 'ACTIVE';
	const STATE_ARCHIVED = 'ARCHIVED';
	const STATE_QUEUED = 'QUEUED';

	// Github scheme
	const GITHUB_REGEX = '^(?:(?:https?:\/\/)?(?:www\.)?github\.com\/)?([\w\d-\.]+)\/([\w\d-\.]+)$';

	/**
	 * @return string
	 */
	protected function getterFullname()
	{
		return $this->author . '/' . $this->name;
	}

	/**
	 * @return bool
	 */
	protected function getterIsComposer()
	{
		return $this->type === self::TYPE_COMPOSER;
	}

	/**
	 * @return bool
	 */
	protected function getterIsBower()
	{
		return $this->type === self::TYPE_BOWER;
	}

	/**
	 * @return ComposerStatistics
	 */
	protected function getterComposerLatestStatistics()
	{
		return $this->composerStatistics->get()->limitBy(1)->fetch();
	}

}
