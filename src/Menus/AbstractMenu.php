<?php

namespace UPC\Menus;

use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

abstract class AbstractMenu
{
	protected $settings = [
		'sites' => 'tmp/sites/',
		'packages' => 'tmp/packages/'
	];

	protected $sites;
	protected $packages;
	protected $root;
	protected $selected_sites = [];

	protected $input_style;

	abstract public function getMenu();

	public function __construct()
	{
		$this->root = '/' . trim(realpath(__DIR__ . '/../../'), '/') . '/';
		$this->packages = array_filter(glob($this->root . $this->settings['packages'] . '*'), 'is_dir');
		$this->sites = array_filter(glob($this->root . $this->settings['sites'] . '*'), 'is_dir');

		$this->input_style = (new MenuStyle())
			->setBg('23')
			->setFg('black');
	}

	protected function callback($key): callable
	{
		return function (CliMenu $menu) use ($key) {
			$this->$key($menu);
		};
	}

	protected function runInDocker(CliMenu $menu, string $container, string $command)
	{
		$menu->close();
		exec('docker-compose exec ' . $container . ' bash -c "' . $command . '"');
		readline('Press Any Key');
		$menu->open();
	}

	protected function selectSitesMenu($callback)
	{
		return function (CliMenuBuilder $b) use ($callback) {
			$b->addAsciiArt($this->logo())

				->addLineBreak('-')
				->addStaticItem('SITES');

			foreach ($this->sites as $val) {
				$b->addCheckboxItem(str_replace($this->root, '', $val), $this->selectSitesCallback());
			}

			$b->addLineBreak('-');
			$b->addStaticItem('PACKAGES');
			foreach ($this->packages as $val) {
				$b->addCheckboxItem(str_replace($this->root, '', $val), $this->selectSitesCallback());
			}
			$b->addLineBreak('-');
			$b->addItem('RUN ALL', $callback);
			$b->addItem('RUN SELECTED', $callback);
			$b->addLineBreak('-');
		};
	}

	private function selectSitesCallback()
	{
		return function (CliMenu $menu) {
			$this->selected_sites = [];

			foreach ($menu->getItems() as $item) {
				/** @var \PhpSchool\CliMenu\MenuItem\CheckboxItem $item */
				if (get_class($item) == 'PhpSchool\CliMenu\MenuItem\CheckboxItem' && $item->getChecked()) {
					$this->selected_sites[] = $item->getText();
				}
			}
		};
	}

	protected function input(CliMenu $menu, string $title, $placeholder = '')
	{
		$response = $menu->askText()
			->setPromptText($title)
			->setPlaceholderText($placeholder)
			->ask();
		return $response->fetch();
	}

	protected function logo()
	{
		$art = <<<ART
____ ______________________
|    |   \______   \_   ___ \
|    |   /|     ___/    \  \/
|    |  / |    |   \     \____
|______/  |____|    \______  /
                           \/
UNITED PROFILE COMMANDCENTER

ART;
		return $art;
	}
}
