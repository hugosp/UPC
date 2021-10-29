<?php

namespace UPC\Menus;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

class Main extends AbstractMenu
{
	public function getMenu()
	{
		$menu = (new CliMenuBuilder)
			->addAsciiArt($this->logo())
			->enableAutoShortcuts()
			->addStaticItem('INFRASTRUCTURE')
			->addLineBreak('-')
			->addSubMenu('[D]ocker', (new Docker)->getMenu())
			->addSubMenu('[C]omposer', (new Composer)->getMenu())
			->addSubMenu('[N]ode/npm', (new Node)->getMenu())
			->addSubMenu('[S]etup', (new Setup)->getMenu())

			->addLineBreak()
			->addStaticItem('TOOLS')
			->addLineBreak('-')
			->addItem('[Q]ueue run', $this->callback('queueRun'))
			->addItem('[E]Ssync run', $this->callback('esSyncRun'))
			->addLineBreak()
			->addLineBreak('-')
			->setBackgroundColour('237')
			->setForegroundColour('156')
			->setBorder(0, 0, 0, 2, '23')
			->setMarginAuto()
			->build();

		$menu->addCustomControlMapping('x', fn () => exit);
		$menu->open();
	}

	protected function queueRun(CliMenu $menu)
	{
		$result = $menu->askNumber($this->input_style)
			->setPromptText('Enter queueID')
			->setValidationFailedText('Invalid number, try again')
			->ask();

		$queue_id = $result->fetch();

		$this->runInDocker($menu, 'php', 'cd sites/queue.corp.unitedprofile.com/bin && ./queue -s ' . $queue_id);
	}

	protected function esSyncRun(CliMenu $menu)
	{
		$type = ($menu->askNumber($this->input_style)
			->setPromptText('Enter Type 0:Full, 1:Update, 2:Important')
			->setValidationFailedText('Invalid number, try again')
			->ask())->fetch();

		$uid = ($menu->askNumber($this->input_style)
			->setPromptText('Enter UserID')
			->setValidationFailedText('Invalid number, try again')
			->ask())->fetch();

		$this->runInDocker($menu, 'php', 'cd sites/essync.corp.unitedprofile.com/bin && ./usersync -t ' . $type . ' -u ' . $uid);
	}
}
