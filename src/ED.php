<?php

namespace ED\CS\Config;

use Symfony\CS\Config\Config;
use Symfony\CS\FixerInterface;

class ED extends Config {
	protected $usingCache = true;

	/**
	 * @var bool
	 */
	private $use_git_filter = true;

	/**
	 * Private copy of finder.
	 *
	 * @see getFinder;
	 * @var \Symfony\CS\Finder\DefaultFinder|\Symfony\CS\FinderInterface|\Traversable
	 */
	private $finderInstance;

	public function __construct() {
		parent::__construct('ED', 'The configuration for ED PHP applications');

		$this->level = FixerInterface::NONE_LEVEL;
		$this->fixers = $this->getRules();

	}

	/**
	 * Allow enable/disabling git filter.
	 * Mostly useful for testing.
	 *
	 * @param boolean $enable
	 */
	public function useGitFilter($enable) {
		$this->use_git_filter = $enable;
	}

	/**
	 * Hook to configure finder object after constructor but before CS-Fixer uses it.
	 *
	 * This allows to enable/disable git filter.
	 *
	 * @return \Symfony\CS\Finder\DefaultFinder|\Symfony\CS\FinderInterface|\Traversable
	 */
	public function getFinder() {
		// configure finder once.
		if ($this->finderInstance) {
			return $this->finderInstance;
		}

		$finder = parent::getFinder();

		if ($this->use_git_filter) {
			$gitHelper = new Helper\GitHelper($finder);
			$gitHelper->addGitFilter();
		}

		// revert *.xml from DefaultFinder
		$finder
			->notPath('{^\.idea/.+\.xml$}');

		// because php-cs-fixer maintainers are idiots
		// https://github.com/FriendsOfPHP/PHP-CS-Fixer/issues/1027
		$finder
			->ignoreDotFiles(false)
			->name('.php_cs');

		return $this->finderInstance = $finder;
	}

	public function getRules() {
		return array(
			'controls_spaces',
			'-eof_ending',
			'function_declaration',
			'include',
			'linefeed',
			'php_closing_tag',
			'return',
			'trailing_spaces',
			'unused_use',
			'visibility',
			'elseif',
			'extra_empty_lines',
			'short_tag',
		);
	}
}
