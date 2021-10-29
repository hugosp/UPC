<?php

namespace UPC\Menus;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

class Composer extends AbstractMenu
{
	public function getMenu()
	{
		return  function (CliMenuBuilder $b) {
			$b->addAsciiArt($this->logo())
				->addStaticItem('UPC > Composer')
				->addLineBreak('-')
				->addSubMenu('install', $this->selectSitesMenu($this->callback('install')))
				->addSubMenu('upgrade', $this->selectSitesMenu($this->callback('upgrade')))
				->addSubMenu('PHPstan', $this->selectSitesMenu($this->callback('analyse')))
				->addItem('Remove alla vendor-folders', $this->callback('removeVendors'))
				->addLineBreak('-');
		};
	}

	protected function install()
	{
		$this->runComposer('install');
	}

	protected function update()
	{
		$this->runComposer('update');
	}

	protected function analyse()
	{
		$this->runComposer('run analyse');
	}

	protected function removeVendors()
	{
	}

	/**
	 * Custom Callback från subfunktion
	 *
	 * @return Callable
	 */
	private function runComposer($what)
	{
		return function (CliMenu $menu) use ($what) {

			$action = $menu->getSelectedItem()->getText();
			$menu->close();

			$folders = $this->selected_sites;

			if ($action == 'RUN ALL') {
				$folders = array_merge($this->sites, $this->packages);
			}

			foreach ($folders as $folder) {
				echo 'RUNNING ' . $folder . PHP_EOL;
				exec("cd $folder && composer $what --no-interaction");
			}

			readline('Press Any Key');
			$menu->open();
		};
	}
}
