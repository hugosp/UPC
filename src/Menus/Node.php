<?php

namespace UPC\Menus;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

class Node extends AbstractMenu
{
	public function getMenu()
	{
		return  function (CliMenuBuilder $b) {
			$b->addAsciiArt($this->logo())
				->addStaticItem('UPC > Node/npm')
				->addLineBreak('-')
				->addSubMenu('install', $this->selectSitesMenu($this->callback('install')))
				->addSubMenu('build', $this->selectSitesMenu($this->callback('build')))
				->addSubMenu('watch', $this->selectSitesMenu($this->callback('watch')))
				->addLineBreak('-');
		};
	}

	protected function install()
	{
		$this->runNpm('install');
	}

	protected function build()
	{
		$this->runNpm('build');
	}

	protected function watch()
	{
		$this->runNpm('watch');
	}

	/**
	 * Custom Callback från subfunktion
	 *
	 * @return Callable
	 */
	private function runNpm($what)
	{
		return function (CliMenu $menu) use ($what) {

			$action = $menu->getSelectedItem()->getText();
			$menu->close();

			$folders = $this->selected_sites;

			dump($folders);

			// if ($action == 'RUN ALL') {
			// 	$folders = array_merge($this->sites, $this->packages);
			// }

			// foreach ($folders as $folder) {
			// 	echo 'RUNNING ' . $folder . PHP_EOL;
			// 	exec("cd $folder && composer $what --no-interaction");
			// }

			readline('Press Any Key');
			$menu->open();
		};
	}
}
