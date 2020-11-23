<?php
/**
* @author SJSU Students
*/
namespace sjsustudents\hw3\views\helpers;

require_once('Helper.php');

/**
* Helper class for drawing unordered lists for the landing and sub list pages
*/
class UnorderedList extends Helper
{
	/**
    *   Constructor for the UnorderedList class
    */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	*	Renders the unordered list on the web browser
	*	@param String $type (the type of unordered list to draw, value 'list' for drawing the child lists list, value 'note' for drawing the note list)
	*	@param array $dataArray (the array containing the names and IDs of the objects to be displayed)
	*/
	public function draw($type, $dataArray)
	{
		//if the type is 'list', draw the child list
		if(strcmp($type, "list") == 0)
		{
			$this->draw_child_list($dataArray);
		}
		//if the type is 'note', draw the note list
		else if (strcmp($type, "note") == 0)
		{
			$this->draw_note_list($dataArray);
		}
		//defualt case if type entered is not supported
		else
		{
			$this->no_list();
		}
	}

	/**
	*	helper function to render the child list
	*	@param array $data (the array containing the names and IDs of the children lists to be displayed)
	*/
	private function draw_child_list($data)
	{
		?>
		<ul>
			<li>[<a href= "index.php?c=FormController&m=show_form&arg1=list&arg2=<?= $data['subListID']; ?>" >New List</a>]</li>
			<?php
			if (!empty($data['arrayLists']))
			{
				foreach ($data['arrayLists'] as $list) {
					$name = $list['name'];
					$id = $list['id'];
					?><li><a href="index.php?c=ListController&m=direct&arg1=<?=$id?>"><?=$name?></a></li><?php
				}
			}
			?>
        </ul>
		<?php
	}

	/**
	*	helper function to render the note list
	*	@param array $data (the array containing the names and IDs of the notes to be displayed)
	*/
	private function draw_note_list($data)
	{
		?>
		<ul>
			<li>[<a href="index.php?c=FormController&m=show_form&arg1=note&arg2=<?= $data['subListID']; ?>">New Note</a>]</li>
			<?php
			if (!empty($data['arrayNotes']))
			{
				foreach ($data['arrayNotes'] as $note) {
					$name = $note['name'];
					$date = $note['date'];
					$date = str_replace("-", "&#8209;", $date);
					$id = $note['id'];
					?>
					<li>
						<a href="index.php?c=NoteController&m=present_note&arg1=<?=$id?>&arg2=<?=$data['subListID']?>">
							<?=$name?>
						</a>
						<?=$date?>
					</li>
					<?php
				}
			}
			?>
		</ul>
		<?php
	}

	/**
	*	helper function to render a non attribute list caused by an unsuppoerted type
	*/
	private function no_list()
	{
		?>
			<ul>
				<li>No List Attribute</li>
			</ul>
		<?php
	}
}
