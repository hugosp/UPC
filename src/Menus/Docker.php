<?php

namespace UPC\Menus;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

class Docker extends AbstractMenu
{
	public function getMenu()
	{
		return  function (CliMenuBuilder $b) {
			$b->addAsciiArt($this->logo())
				->addStaticItem('UPC > Docker')
				->addLineBreak('-')
				->addItem('start', $this->callback('dockerStart'))
				->addItem('stop', $this->callback('dockerStop'))
				->addItem('restart', $this->callback('dockerRestart'))
				->addItem('build', $this->callback('dockerBuild'))
				->addLineBreak('-')
				->addItem('bash', $this->callback('dockerBash'))
				->addItem('tail logs', $this->callback('dockerLogs')) // ska även ha med slow-log från mysql
				->addItem('Show running containers', $this->callback('dockerPs'))
				->addLineBreak('-')
				->addItem('destroy', $this->callback('dockerDestroy'))
				->addLineBreak('-');
		};
	}

	protected function dockerStart()
	{
		$this->runDockerCompose('start');
	}
	protected function dockerStop()
	{
		$this->runDockerCompose('stop');
	}
	protected function dockerRestart()
	{
		$this->runDockerCompose('restart');
	}
	protected function dockerPs()
	{
		$this->runDockerCompose('ps');
	}

	/**
	 * Custom Callback från subfunktion
	 *
	 * @return Callable
	 */
	private function runDockerCompose($what)
	{
		return function (CliMenu $menu) use ($what) {

			// $action = $menu->getSelectedItem()->getText();
			$menu->close();

			// $folders = $this->selected_sites;

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
