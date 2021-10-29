<?php

namespace UPC\Menus;

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

class Setup extends AbstractMenu
{
	public function getMenu()
	{
		return  function (CliMenuBuilder $b) {
			$b->addAsciiArt($this->logo())
				->addStaticItem('UPC > Setup')
				->addLineBreak('-')
				->addItem('init (build,composer,npm,permissions)', $this->callback('init'))
				->addLineBreak('-')
				->addItem('Install DB from Backup', $this->callback('installDB'))
				->addItem('Folder-permissions', $this->callback('folders'))
				->addLineBreak('-')
				->addItem('cert install', $this->callback('cert'))
				->addItem('cert instructions', $this->callback('certInstructions'))
				->addLineBreak('-');
		};
	}


	protected function init()
	{
	}
	protected function installDB()
	{
	}
	protected function folders()
	{
	}
	protected function cert()
	{
	}
	protected function certInstructions()
	{
	}
}
