<?php

namespace ED\CS\Config\Test;

use ED\CS\Config\Filter\GitFilter;
use Symfony\CS\Finder\DefaultFinder;

class GitHelperTest extends TestCase {

	/** @var DefaultFinder */
	protected $finder;

	/** @var GitFilter */
	protected $helper;

	public function setUp() {
		$this->finder = new DefaultFinder();

		$gitFilter = new GitFilter();
		$gitFilter->apply($this->finder);
	}

	public function testFiles() {
		$files = $this->getFinderRelativePaths($this->finder);

		$this->assertContains($this->path('src/ED.php'), $files, "Must contain src/ED.php");

		$unique = array_unique($files);
		$this->assertEquals($unique, $files, "File list does not contain duplicate entries");

		$this->assertNotContains($this->path('.idea/workspace.xml'), $files);
		$this->assertNotContains($this->path('vendor/autoload.php'), $files);
	}
}