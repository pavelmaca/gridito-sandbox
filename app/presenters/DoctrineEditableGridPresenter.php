<?php

use Nette\Forms\Form;

/**
 * Doctine editable grid example presenter
 *
 * @author Pavel Máca
 * @license MIT
 */
class DoctrineEditableGridPresenter extends BasePresenter
{
	protected function createComponentGrid($name)
	{
		$grid = new Gridito\EditableGrid($this, $name);

		// model
		$em = Nette\Environment::getService("Doctrine\ORM\EntityManager");
		$model = new Model\UsersGriditoDoctrineModel($em);
		$grid->setEditableModel($model);

		// columns
		$grid->addColumn("id", "ID")
			->setSortable(true);
		
		$grid->addColumn("username", "Username")
			->setSortable(true)
			->setEditable();
		
		$grid->addColumn("name", "Name")
			->setSortable(true)
			->setEditable();
		
		$grid->addColumn("surname", "Surname")
			->setSortable(true)
			->setEditable();
		
		$grid->addColumn("mail", "E-mail", array(
			"sortable" => true,
			"renderer" => function ($row) {
				echo Nette\Web\Html::el("a")->href("mailto:$row->mail")->setText($row->mail);
			}
			//TODO editable dosn't work here
		));
		
		$grid->getColumn("mail")
			->setEditable()
				->addRule(Form::EMAIL, "E-mail is not valid.");
	
			
		$grid->addColumn("active", "Active")
			->setSortable(true)
			->setEditable('Nette\Forms\Checkbox');

		// toolbar buttons
		$grid->addAddButton("create", "Create new user", array(
			"icon" => "ui-icon-plusthick",
		));
		
		$grid->addToolbarButton("back", "Go back to examples", array(
			"link" => $this->link("Homepage:"),
			"icon" => "ui-icon-home",
		));

		// action buttons
		$grid->addRemoveButton("delete", "Delete", array(
			"icon" => "ui-icon-closethick",
			"confirmationQuestion" => function ($user) {
				if ($user->active) {
					return "Really delete use $user->name $user->surname?";
				} else {
					return null;
				}
			},
			"visible" => function ($user) {
				return !$user->isActive();
			},
		));
			
		$grid->addEditButton("edit", "Edit", array(
			"icon" => "ui-icon-pencil",
		));
	}

}